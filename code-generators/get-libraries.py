import json
import os
from os import chdir, path
from pathlib import Path
from urllib.request import urlopen

chdir(path.dirname(path.abspath(__file__)))
RESOURCE = Path('../Resources/libraries/')

JSON_URL = 'https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/environment-packages-{language}.json'

ENVIRONMENT_DIRS = [
    {
        'path': Path('../01 Cloud Platform/06 Projects/11 Package Environments'),
        'default_num': 3,
        'env_start_num': 4,
        'default_name': 'Default Environment',
        'env_suffix': 'Environment',
        'is_cloud_platform': True,
    },
    {
        'path': Path('../02 Local Platform/04 Development Environment/12 Packages and Libraries'),
        'default_num': 3,
        'env_start_num': 4,
        'default_name': 'Default Environment',
        'env_suffix': 'Environment',
        'is_cloud_platform': False,
    },
    {
        'path': Path('../03 Writing Algorithms/01 Key Concepts/12 Libraries'),
        'default_num': 2,
        'env_start_num': 3,
        'default_name': 'Default Environment Libraries',
        'env_suffix': 'Environment Libraries',
        'is_cloud_platform': True,
    },
    {
        'path': Path('../05 Lean CLI/06 Projects/08 Libraries/01 Third-Party Libraries'),
        'default_num': None,
        'env_start_num': None,
        'default_name': None,
        'env_suffix': None,
        'is_cloud_platform': False,
    },
]

def __fetch_json(url):
    with urlopen(url) as response:
        return json.loads(response.read().decode('utf-8'))

def __packages_to_dict(packages):
    libraries = {p['name']: p['version'] for p in packages}
    maxlen = len(max(libraries.keys(), key=len))
    return maxlen, libraries

def __library_to_code_block(maxlen, libraries):
    def format_output(key: str, value: str, maxlen: int) -> str:
        count = maxlen - len(key)
        return f'{key + " " * count} {value}'
    html = ''
    for key, value in sorted(libraries.items(), key=lambda x: x[0].lower()):
        html += format_output(key, value, maxlen) + '\n'
    return html

def __title_case(key):
    return key.replace('-', ' ').replace('_', ' ').title()

def __generate_default_environment_page(dir_config):
    num = str(dir_config['default_num']).zfill(2)
    filepath = dir_config['path'] / f'{num} {dir_config["default_name"]}.php'
    is_cloud = 'true' if dir_config['is_cloud_platform'] else 'false'
    content = '<p>The default environment supports the following libraries:</p>\n'
    content += '<?\n'
    content += f'$isCloudPlatform = {is_cloud};\n'
    content += 'include(DOCS_RESOURCES."/libraries/supported-libraries.php");\n'
    content += '?>\n'
    with open(filepath, mode='w', encoding='utf-8') as fp:
        fp.write(content)

def __generate_foundation_environment_page(dir_config, num, key):
    name = __title_case(key)
    num_str = str(num).zfill(2)
    filepath = dir_config['path'] / f'{num_str} {name} {dir_config["env_suffix"]}.php'
    content = f'<p class="csharp">This environment is only available for Python.</p>\n'
    content += f'<p class="python">The {name} environment provides the following libraries:</p>\n'
    content += f'<div class="python">\n'
    content += f'    <? include(DOCS_RESOURCES."/libraries/supported-libraries-foundation-{key}.html"); ?>\n'
    content += f'</div>\n'
    with open(filepath, mode='w', encoding='utf-8') as fp:
        fp.write(content)

def __clean_environment_pages(dir_config):
    """Remove old environment pages in the dynamic range."""
    start = dir_config['env_start_num']
    for filepath in dir_config['path'].glob('*.php'):
        try:
            file_num = int(filepath.name[:2])
        except ValueError:
            continue
        if start <= file_num < 98:
            os.remove(filepath)

if __name__ == '__main__':
    python_data = __fetch_json(JSON_URL.format(language='python'))
    csharp_data = __fetch_json(JSON_URL.format(language='csharp'))

    env_keys = [k for k in python_data if k != 'default']

    selected = {
        'tensorflow': {
            'name': 'TensorFlow',
            'url': 'https://www.tensorflow.org/',
            'code': 'import tensorflow',
            'language': 'Python',
            'example': 'Algorithm.Python/TensorFlowNeuralNetworkAlgorithm.py',
            'version': '',
            },
        'scikit-learn': {
            'name': 'SciKit Learn',
            'url': 'https://scikit-learn.org/stable/',
            'code': 'import sklearn',
            'language': 'Python',
            'example': 'Algorithm.Python/ScikitLearnLinearRegressionAlgorithm.py',
            'version': '',
            },
        'torch': {
            'name': 'Py Torch',
            'url': 'https://pytorch.org/',
            'code': 'import torch',
            'language': 'Python',
            'example': 'Algorithm.Python/PytorchNeuralNetworkAlgorithm.py',
            'version': '',
            },
        'keras': {
            'name': 'Keras',
            'url': 'https://keras.io/',
            'code': 'import keras',
            'language': 'Python',
            'example': 'Algorithm.Python/KerasNeuralNetworkAlgorithm.py',
            'version': '',
            },
        'theano': {
            'name': 'Theano',
            'url': 'http://deeplearning.net/software/theano/',
            'code': 'import theano',
            'language': 'Python',
            'example': '',
            'version': '',
            },
        'gplearn': {
            'name': 'gplearn',
            'url': 'https://gplearn.readthedocs.io/en/stable/intro.html',
            'code': 'import gplearn',
            'language': 'Python',
            'example': '',
            'version': '',
            },
        'hmmlearn': {
            'name': 'hmmlearn',
            'url': 'https://hmmlearn.readthedocs.io/en/latest/',
            'code': 'import hmmlearn',
            'language': 'Python',
            'example': '',
            'version': '',
            },
        'tsfresh': {
            'name': 'tsfresh',
            'url': 'https://tsfresh.readthedocs.io/en/latest/',
            'code': 'import tsfresh',
            'language': 'Python',
            'example': '',
            'version': '',
            },
        'stable_baselines3': {
            'name': 'Stable-Baselines3',
            'url': 'https://stable-baselines3.readthedocs.io/en/master',
            'code': 'from stable_baselines3 import *',
            'language': 'Python',
            'example': '',
            'version': '',
            },
        'fastai': {
            'name': 'fastai',
            'url': 'https://docs.fast.ai/',
            'code': 'import fastai',
            'language': 'Python',
            'example': '',
            'version': '',
            },
        'deap': {
            'name': 'Deap',
            'url': 'https://deap.readthedocs.io/en/master/overview.html',
            'code': 'import deap',
            'language': 'Python',
            'example': '',
            'version': '',
            },
        'xgboost': {
            'name': 'XGBoost',
            'url': 'https://xgboost.readthedocs.io/en/latest/',
            'code': 'import xgboost',
            'language': 'Python',
            'example': '',
            'version': '',
            },
        'mlfinlab' : {
            'name': 'mlfinlab',
            'url': 'https://github.com/hudson-and-thames/mlfinlab',
            'language': 'Python',
            'code': 'import mlfinlab',
            'example': '',
            'version': '1.6.0',
            },
        'Accord' : {
            'name': 'Accord',
            'url': 'http://accord-framework.net/',
            'code': 'using Accord.MachineLearning;',
            'language': 'C#',
            'example': 'Algorithm.CSharp/AccordVectorMachinesAlgorithm.cs',
            'version': '3.6.0',
            }
    }

    # Generate supported-libraries.php
    python_maxlen, python_libraries = __packages_to_dict(python_data['default'])
    csharp_maxlen, csharp_libraries = __packages_to_dict(csharp_data['default'])

    for key, value in python_libraries.items():
        if key in selected and not selected[key]['version']:
            selected[key]['version'] = value
    for key, value in csharp_libraries.items():
        if key in selected and not selected[key]['version']:
            selected[key]['version'] = value

    with open(RESOURCE / 'supported-libraries.php', mode='w', encoding='utf-8') as fp:
        html = '<div class="section-example-container">\n'
        html += '<pre class="python">\n'
        html += __library_to_code_block(python_maxlen, python_libraries)
        html += '</pre>'
        html += '<pre class="csharp">\n'
        html += __library_to_code_block(csharp_maxlen, csharp_libraries)
        html += '</pre>'
        html += '</div>'
        fp.write(html)

    # Generate foundation library HTML files
    for key in env_keys:
        maxlen, libraries = __packages_to_dict(python_data[key])
        with open(RESOURCE / f'supported-libraries-foundation-{key}.html', mode='w', encoding='utf-8') as fp:
            fp.write('<div class="section-example-container"><pre class="python">\n')
            fp.write(__library_to_code_block(maxlen, libraries))
            fp.write('</pre></div>\n')

    # Generate environment pages in each directory
    for dir_config in ENVIRONMENT_DIRS:
        if not dir_config['env_start_num']:
            continue
        __generate_default_environment_page(dir_config)
        __clean_environment_pages(dir_config)
        for i, key in enumerate(env_keys):
            num = dir_config['env_start_num'] + i
            __generate_foundation_environment_page(dir_config, num, key)

    # Generate Supported Libraries.php (ML page)
    html = '''<style>
.centered {text-align: center; }
</style>

<p>LEAN supports several machine learning libraries. You can import these packages and use them in your algorithms.</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th width="20%">Name</th>
            <th width="15%">Version</th>
            <th width="15%">Language</th>
            <th>Import Statement</th>
            <th width="5%">Example</th>
        </tr>
    </thead>
<tbody>

<?
class MachineLearningLibraryForWritingAlgorithm {
    public $name;
    public $version;
    public $importStatement;
    public $documentationLink;
    public $exampleLink;
    public $language;

    public function __construct($name, $version, $language, $importStatement, $documentationLink, $exampleLink)
    {
        $this->name = $name;
        $this->version = $version;
        $this->language = $language;
        $this->importStatement = $importStatement;
        $this->documentationLink = $documentationLink;
        $this->exampleLink = $exampleLink;
    }
}

$libraries = array('''

    for key, value in selected.items():
        if not value["version"]:
            continue
        example = value["example"]
        if example:
            example = f'https://github.com/QuantConnect/Lean/blob/master/{example}'
        html += f'''
    new MachineLearningLibraryForWritingAlgorithm("{value["name"]}", "{value["version"]}", "{value["language"]}", "{value["code"]}", "{value["url"]}", "{example}"),'''

    html = html[:-1] + '''
);

foreach ($libraries as $library) {
    echo "<tr>
              <td><a target='_BLANK' href='{$library->documentationLink}'>{$library->name}</a></td>
              <td>{$library->version}</td>
              <td>{$library->language}</td>
              <td><code>{$library->importStatement}</code></td>
              <td class='centered'><a href='{$library->exampleLink}'><i class='fa fa-external-link'></i></a></td>
          </tr>
    ";
}?>
    </tbody>
</table>'''

    with open(f'../03 Writing Algorithms/31 Machine Learning/01 Key Concepts/02 Supported Libraries.php', mode='w', encoding='utf-8') as fp:
        fp.write(html)
