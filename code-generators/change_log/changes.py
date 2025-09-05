import os

from change_log import ChangeLog

if __name__ == '__main__':
    ChangeLog(
        github_access_token=os.environ.get('GH_ACCESS_TOKEN'),
        qc_docs_path='.', 
        openai_model=os.environ.get('OPENAI_MODEL'),
        openai_api_key=os.environ.get('OPENAI_TOKEN'),
    ).update(30, 'change-log.json')
