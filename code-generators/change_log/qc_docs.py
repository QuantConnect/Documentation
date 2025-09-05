import os


class QCDocs:

    _base_url = 'https://www.quantconnect.com/docs/v2'
    _public_directories = [
        '01 Cloud Platform',
        '02 Local Platform',
        '03 Writing Algorithms',
        '04 Research Environment',
        '05 Lean CLI',
        '06 LEAN Engine',
        '09 AI Assistance'
    ]
    _files_to_exclude = [
        'metadata.json'
    ]
    _extension_to_exclude = [
        'png', 'jpg', 'csv'
    ]

    def __init__(self, path):
        self._path = path

    def get_url(self, file_path):
        url = self._base_url
        file_path = file_path.replace('\\', '/')
        for path_segment in file_path.split('/'):
            if path_segment in ['00.html', '01.html', '00.json']:
                break
            h4 = '.' in path_segment and ' ' in path_segment
            if not h4:
                # Remove the leading numbers, convert to lowercase, and 
                # replace whitespace for `-`.
                url += '/' + path_segment[3:].lower().replace(' ', '-')
            else:
                # Add the `#`, replace whitespace for `-`, and drop the
                # file extension.
                url += '#' + path_segment.replace(' ', '-').split('.')[0]
        return url

    def should_report_changes(self, file_path):
        path_segments = file_path.split('/')
        return (
            path_segments[0] in self._public_directories and
            path_segments[-1] not in self._files_to_exclude and
            path_segments[-1].split('.')[-1] not in self._extension_to_exclude
        )

    def is_resource(self, file_path):
        return 'Resources' == file_path.split('/')[0]

    def get_files_that_include_resource(self, resource):
        files_paths = []
        for root, dirs, files in os.walk(self._path):
            # Skip this root directory if its not a public directory.
            if not any(dir_ in root for dir_ in self._public_directories):
                continue
            for file_name in files:
                if (file_name in self._files_to_exclude or
                    file_name.split('.')[-1] in self._extension_to_exclude):
                    continue
                file_path = os.path.join(root, file_name)
                with open(file_path, 'r', encoding='utf-8') as f:
                    if resource in f.read():
                        files_paths.append(file_path[len(self._path)+1:])
        return files_paths
