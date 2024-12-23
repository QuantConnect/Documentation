from os import chdir, path, popen
from pathlib import Path

chdir(path.dirname(path.abspath(__file__)))
RESOURCE = Path('../Resources/libraries/')

def __process_pip_line(line):
    if '@' not in line:
        return line
    line = next(filter(lambda l: 'whl' in l, line.split('/')), '')
    if not line:
        return line
    return '=='.join(line.split('-')[:2]).replace('_','-') + '\n'

def __create_python_libraries_file():
    cmd = 'docker run --entrypoint bash quantconnect/lean:latest -c "pip3 freeze"'
    lines = [__process_pip_line(line) for line in popen(cmd).readlines()]
    with open(RESOURCE / 'default-python.txt', mode='w', encoding='utf-8') as fp:
        fp.write(''.join([l for l in lines if l]))

def __create_csharp_libraries_file():
    lean_sln = Path("../Lean/QuantConnect.Lean.sln")
    # Install Lean
    if not lean_sln.is_file():
        popen("git clone https://github.com/QuantConnect/Lean.git ../Lean").close()
        popen(f"dotnet restore {lean_sln.resolve()}").close()

    cmd = f'dotnet list {lean_sln.resolve()} package'
    def process_line(line):
        return '=='.join([l for l in line.split(' ') if l][1:3])
    lines = sorted(set([process_line(line) for line in popen(cmd).readlines() if '>' in line]))
    with open(RESOURCE / 'default-csharp.txt', mode='w', encoding='utf-8') as fp:
        fp.write('\n'.join(lines))

def __library_to_code_block(maxlen, libraries):
    def format_output(key: str, value: str, maxlen: int) -> str:
        count = maxlen - len(key)
        value = value.replace("\n", "")
        return f'{key + " " * count} {value}'
    html = ''
    for key, value in sorted(libraries.items(), key=lambda x: x[0].lower()):
        html += format_output(key, value, maxlen) + '\n'
    return html

def __read_libraries_from_file(filename, sep='=='):
    with open(filename, mode='r', encoding='utf-8') as f:
        def line_to_kvp(line):
            csv = [x for x in line.split(sep) if x]
            return csv[0],csv[1][:-1]
        lines = [__process_pip_line(line) for line in f.readlines()]
        libraries = dict([line_to_kvp(line) for line in lines if sep in line])
        return len(max(libraries.keys(), key=len)), libraries

if __name__ == '__main__':
    __create_csharp_libraries_file()
    __create_python_libraries_file()
    
    package_reference = '''    <PackageReference Include="Accord" Version="3.6.0" />
    <PackageReference Include="Accord.Audio" Version="3.6.0" />
    <PackageReference Include="Accord.Fuzzy" Version="3.6.0" />
    <PackageReference Include="Accord.Genetic" Version="3.6.0" />
    <PackageReference Include="Accord.MachineLearning" Version="3.6.0" />
    <PackageReference Include="Accord.MachineLearning.GPL" Version="3.6.0" />
    <PackageReference Include="Accord.Math" Version="3.6.0" />
    <PackageReference Include="Accord.Statistics" Version="3.6.0" />
    <PackageReference Include="csnumerics" Version="1.0.2" />
    <PackageReference Include="Deedle" Version="2.1.0" />
    <PackageReference Include="DynamicInterop" Version="0.9.1" />
    <PackageReference Include="Google.OrTools" Version="9.11.4210" />
    <PackageReference Include="MathNet.Filtering" Version="0.7.0" />
    <PackageReference Include="MathNet.Filtering.Kalman" Version="0.7.0" />
    <PackageReference Include="MathNet.Numerics" Version="5.0.0" />
    <PackageReference Include="MathNet.Spatial" Version="0.6.0" />
    <PackageReference Include="Microsoft.Data.Analysis" Version="0.22.0" />
    <PackageReference Include="Microsoft.ML.CpuMath" Version="4.0.0" />
    <PackageReference Include="Microsoft.ML.DataView" Version="4.0.0" />
    <PackageReference Include="Microsoft.ML.Ensemble" Version="0.22.0" />
    <PackageReference Include="Microsoft.ML.FastTree" Version="4.0.0" />
    <PackageReference Include="Microsoft.ML.LightGbm" Version="4.0.0" />
    <PackageReference Include="Microsoft.ML.Mkl.Components" Version="4.0.0" />
    <PackageReference Include="Microsoft.ML.OnnxRuntime" Version="1.20.1" />
    <PackageReference Include="Microsoft.ML.TensorFlow" Version="4.0.0" />
    <PackageReference Include="Microsoft.ML.TimeSeries" Version="4.0.0" />
    <PackageReference Include="Newtonsoft.Json" Version="13.0.2" />
    <PackageReference Include="NodaTime" Version="3.0.5" />
    <PackageReference Include="OpenAI" Version="2.1.0" />
    <PackageReference Include="QLNet" Version="1.13.1" />
    <PackageReference Include="R.NET" Version="1.9.0" />
    <PackageReference Include="QuantConnect.pythonnet" Version="2.0.42" />
    <PackageReference Include="QuantConnect.PredictNowNET" Version="1.0.2" />
    <PackageReference Include="RestSharp" Version="106.12.0" />
    <PackageReference Include="Catalyst" Version="1.0.54164" />
    <PackageReference Include="Catalyst.Models.English" Version="1.0.30952" />
    <PackageReference Include="CNTK.CPUOnly" Version="2.8.0-rc0.dev20200201" />
    <PackageReference Include="LibTopoART" Version="0.98.0" />
    <PackageReference Include="Microsoft.ML" Version="4.0.0" />
    <PackageReference Include="NumSharp" Version="0.30.0" />
    <PackageReference Include="SciSharp.TensorFlow.Redist" Version="2.16.0" />
    <PackageReference Include="SciSharp.TensorFlow.Redist-Linux-GPU" Version="2.11.1" />
    <PackageReference Include="SharpLearning.DecisionTrees" Version="0.31.8" />
    <PackageReference Include="SharpLearning.AdaBoost" Version="0.31.8" />
    <PackageReference Include="SharpLearning.RandomForest" Version="0.31.8" />
    <PackageReference Include="SharpLearning.GradientBoost" Version="0.31.8" />
    <PackageReference Include="SharpLearning.Neural" Version="0.31.8" />
    <PackageReference Include="SharpLearning.Ensemble" Version="0.31.8" />
    <PackageReference Include="SharpLearning.Common.Interfaces" Version="0.31.8" />
    <PackageReference Include="SharpLearning.CrossValidation" Version="0.31.8" />
    <PackageReference Include="SharpLearning.Metrics" Version="0.31.8" />
    <PackageReference Include="SharpLearning.Optimization" Version="0.31.8" />
    <PackageReference Include="SharpLearning.Containers" Version="0.31.8" />
    <PackageReference Include="SharpLearning.InputOutput" Version="0.31.8" />
    <PackageReference Include="SharpLearning.FeatureTransformations" Version="0.31.8" />
    <PackageReference Include="SharpLearning.XGBoost" Version="0.31.8" />
    <PackageReference Include="SharpNeatLib" Version="2.4.4" />
    <PackageReference Include="TensorFlow.Keras" Version="0.15.0" />
    <PackageReference Include="TensorFlow.NET" Version="0.150.0" />
    <PackageReference Include="Plotly.NET" Version="5.1.0" />
    <PackageReference Include="Plotly.NET.CSharp" Version="0.13.0" />
    <PackageReference Include="Plotly.NET.Interactive" Version="5.0.0" />
    <PackageReference Include="QuantConnect.pythonnet" Version="2.0.41" />
    <PackageReference Include="NodaTime" Version="3.0.5" />'''

    cloud_added = {}
    for line in package_reference.split('\n'):
        parts = line.split('"')
        cloud_added[parts[1]] = parts[3]

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

    with open(RESOURCE / 'supported-libraries.php', mode='w', encoding='utf-8') as fp:
        html = '<div class="section-example-container">\n'
        for language in ['python', 'csharp']:
            maxlen, libraries = __read_libraries_from_file(RESOURCE / f'default-{language}.txt')

            for key, value in libraries.items():
                if key in selected:
                    if not selected[key]['version']:
                        selected[key]['version'] = value

            html += f'<pre class="{language}">\n' 
            html += __library_to_code_block(maxlen, libraries)

            # Add C# Cloud
            if language == "csharp":
                html += '<? if ($cloudPlatform) { ?>\n'
                cloud_added = {k:v for k,v in cloud_added.items() if k not in libraries}
                html += __library_to_code_block(maxlen, cloud_added)
                html += '<? } ?>\n'

            html += '</pre>'

        html += '</div>'
        fp.write(html)
    
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

    with open(f'03 Writing Algorithms/31 Machine Learning/01 Key Concepts/02 Supported Libraries.php', mode='w', encoding='utf-8') as fp:
        fp.write(html)