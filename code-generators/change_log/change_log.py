import re
import json
from datetime import datetime, timedelta

from qc_docs import QCDocs
from github import GitHub
from openai_ import OpenAI_


class ChangeLog:

    def __init__(
            self, github_access_token, qc_docs_path, openai_model, 
            openai_api_key):
        self._github = GitHub(github_access_token)
        self._github.owner = 'QuantConnect'
        self._github.repo = 'Documentation'
        self._qc_docs = QCDocs(qc_docs_path)
        system_prompt = """Summarize this into the most significant \
        changes. Output the result in JSON format, including the summary and \
        URL.
        [{'summary': 'some text', 'url': 'hyperlink'}]
        """
        self._openai = OpenAI_(openai_api_key, openai_model, system_prompt)

    def update(self, lookback_days, output_path):
        # Get the daily changes.
        changes_by_date = {}
        today = datetime.today().date()
        for i in range(lookback_days, 0, -1):
            date = today - timedelta(days=i)
            changes_by_date[date] = self._get_daily_changes(date)
        # Save the daily changes to a JSON file.
        with open(output_path, 'w') as file:
            json.dump(changes_by_date, file, indent=4)

    def _get_daily_changes(self, date):
        commits = []
        self._pull_request_by_id = {}
        self._issues_by_repo = {}
        # Get the GitHub commits for the current day.
        raw_commits = self._github.get_commits(
            self._github.owner, self._github.repo, date
        )
        # Iterate through the GitHub commits (in chronological order).
        for commit in raw_commits[::-1]:
            # Trim down the commit object to just the data we need.
            commit = {
                'sha': commit['sha'],
                'message': commit['commit']['message'],
                'parents': [c['sha'] for c in commit['parents']],
            }
            # If the commit is part of a pull request...
            if 'Merge pull request ' in commit['message']:
                # Update the PR and Issue dictionaries, then get the 
                # pull number.
                pull_number = self._add_pr_and_issue_data(commit)
                # Iterate through previous commits we've seen and add
                # a reference to the PR when necessary.
                for sha in commit['parents'][1:]:
                    for c in commits:
                        if c['sha'] != sha:
                            continue
                        c['related_pull_request'] = pull_number
                continue 
            # Exclude "merge commits", since they contain changes from 
            # previous commits. Merge commits have more than 1 `parent`.
            if len(commit['parents']) > 1:
                continue
            # Add files changes for the commit.
            commit['files'] = list(self._get_files(commit['sha']))
            commits.append(commit)

        # Create the prompt.
        prompt = {
            'commits': [],
            'pull_requests': {},
            'issues': {}
        }
        # 1) Populate commits.
        for commit in commits:
            # Skip commits that have no file changes (QCDocs excluded
            # them).
            if not commit['files']:
                continue
            # Select the important properties of the commit object.
            clean_commit = {}
            for key in ['message', 'files', 'related_pull_request']:
                if key in commit:
                    clean_commit[key] = commit[key]
            prompt['commits'].append(clean_commit)
        # 2) Populate pull requests.
        for commit in prompt['commits']:
            pull_number = commit.get('related_pull_request', 0)
            if not pull_number:
                continue
            prompt['pull_requests'][pull_number] = self._pull_request_by_id[pull_number]
        # 3) Populate Issues.
        for pr in prompt['pull_requests'].values():
            for repo, issue_number in pr.get('related_issues', []):
                if repo not in prompt['issues']:
                    prompt['issues'][repo] = {}
                prompt['issues'][repo][issue_number] = self._issues_by_repo[repo][issue_number]

        # Create the list of changes for the change log using the AI 
        # model.
        return self._openai.create_prompt(json.dumps(prompt))

    def _add_pr_and_issue_data(self, commit):
        # Get the PR number from the commit message.
        pattern = r'Merge pull request #(\d+)'
        pull_number = int(re.search(pattern, commit['message']).group(1))
        if pull_number in self._pull_request_by_id:
            return pull_number
        # Get information about the PR.
        pr = self._github.get_pull_request(
            self._github.owner, self._github.repo, pull_number
        )
        pr = {'title': pr['title'], 'body': pr['body']}
        # Find GitHub Issues in the QC organization that are 
        # mentioned in the PR text.
        related_issues = re.findall(
            r'https://github\.com/QuantConnect/([^/]+)/issues/(\d+)', 
            pr['body']
        )
        # Cast the Issue number to an integer.
        related_issues = [(i[0], int(i[1])) for i in related_issues]
        # Get information about each related issue.
        for repo, issue_number in related_issues:
            if repo not in self._issues_by_repo:
                self._issues_by_repo[repo] = {}
            if issue_number in self._issues_by_repo[repo]:
                continue
            issue = self._github.get_issue(
                self._github.owner, repo, issue_number
            )
            self._issues_by_repo[repo][issue_number] = issue['body']
        # Save the PR and Issue information for later.
        if related_issues:
            pr['related_issues'] = related_issues
        self._pull_request_by_id[pull_number] = pr
        return pull_number

    def _get_files(self, sha):
        # Determine which files in the main Documentation products 
        # (Cloud Platform, Writing Algorithms, etc...) were 
        # added/changed in the commit.
        commit_files = self._github.get_commit_files(
            self._github.owner, self._github.repo, sha
        )
        # Iterate through the files of the commit.
        for f in commit_files:
            name = f['filename']
            # If the file is a Resource, get all the files that 
            # include that Resource.
            if self._qc_docs.is_resource(name):
                file_paths = self._qc_docs.get_files_that_include_resource(name)
            else:  # Otherwise, just use the original file path.
                file_paths = [name]
            # Iterate through the paths of the affected files.
            for file_path in file_paths:
                if self._qc_docs.should_report_changes(file_path):
                    result = {
                        'status': f['status'],
                        'filename': file_path,
                        'url': self._qc_docs.get_url(file_path),
                    }
                    for key in ['previous_filename', 'patch']:
                        if key in f:
                            result[key] = f[key]
                    yield result
