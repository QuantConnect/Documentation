import requests
from datetime import timedelta
from ratelimit import limits, sleep_and_retry


class GitHub:

    def __init__(self, access_token):
        self._headers = {
            'Authorization': f'Bearer {access_token}',
            'X-GitHub-Api-Version': '2022-11-28'
        }

    def get_commits(self, owner, repo, date):
        # https://docs.github.com/en/rest/commits/commits?apiVersion=2022-11-28#list-commits
        return self._get_all_pages(
            self._get_base_url(owner, repo) + 'commits',
            {
                'since': self._strftime(date), 
                'until': self._strftime(date + timedelta(1))
            }
        )

    def get_commit_files(self, owner, repo, ref):
        # https://docs.github.com/en/rest/commits/commits?apiVersion=2022-11-28#get-a-commit
        return self._get_all_pages(
            f'{self._get_base_url(owner, repo)}commits/{ref}', key='files'
        )

    def get_pull_request(self, owner, repo, pull_number):
        # https://docs.github.com/en/rest/pulls/pulls?apiVersion=2022-11-28#get-a-pull-request
        return self._send_request(
            f'{self._get_base_url(owner, repo)}pulls/{pull_number}'
        )

    def get_issue(self, owner, repo, issue_number):
        # https://docs.github.com/en/rest/issues/issues?apiVersion=2022-11-28#get-an-issue
        return self._send_request(
            f'{self._get_base_url(owner, repo)}issues/{issue_number}'
        )

    def _get_all_pages(self, uri, params={}, key='', per_page=100):
        results = []
        page = 1
        while True:
            page_response = self._send_request(
                uri, params | {'per_page': per_page, 'page': page}
            )
            if key:
                page_response = page_response[key]
            if page_response:
                results.extend(page_response)
                if len(page_response) < per_page:
                    page += 1
                    continue
            return results

    def _get_base_url(self, owner, repo):
        return f'https://api.github.com/repos/{owner}/{repo}/'

    @sleep_and_retry
    @limits(calls=15_000, period=60*60) 
    def _send_request(self, uri, params={}):
        # Rate limit: 15K calls per 60 minutes 
        # https://docs.github.com/en/rest/using-the-rest-api/rate-limits-for-the-rest-api?apiVersion=2022-11-28#primary-rate-limit-for-authenticated-users
        response = requests.get(uri, headers=self._headers, params=params)
        response.raise_for_status()
        return response.json()

    def _strftime(self, dt, format_="%Y-%m-%dT%H:%M:%SZ"):
        return dt.strftime(format_)
