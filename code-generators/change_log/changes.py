import os

from change_log import ChangeLog

if __name__ == '__main__':
    ChangeLog(
        github_access_token=os.getenv('GH_ACCESS_TOKEN'),
        qc_docs_path='.', 
        openai_model=os.getenv('OPENAI_MODEL'),
        openai_api_key=os.getenv('OPENAI_TOKEN'),
    ).update(10, 'change-log.json')
