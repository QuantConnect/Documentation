const defined_ml_libraries = {
    'tensorflow': { name: 'TensorFlow', url: 'https://www.tensorflow.org/', code: 'import tensorflow', language: 'Python', example: 'Algorithm.Python/TensorFlowNeuralNetworkAlgorithm.py' },
    'scikit-learn': { name: 'SciKit Learn', url: 'https://scikit-learn.org/stable/', code: 'import sklearn', language: 'Python', example: 'Algorithm.Python/ScikitLearnLinearRegressionAlgorithm.py' },
    'torch': { name: 'Py Torch', url: 'https://pytorch.org/', code: 'import torch', language: 'Python', example: 'Algorithm.Python/PytorchNeuralNetworkAlgorithm.py' },
    'keras': { name: 'Keras', url: 'https://keras.io/', code: 'import keras', language: 'Python', example: 'Algorithm.Python/KerasNeuralNetworkAlgorithm.py' },
    'hmmlearn': { name: 'hmmlearn', url: 'https://hmmlearn.readthedocs.io/en/latest/', code: 'import hmmlearn', language: 'Python', example: '' },
    'tsfresh': { name: 'tsfresh', url: 'https://tsfresh.readthedocs.io/en/latest/', code: 'import tsfresh', language: 'Python', example: '' },
    'stable_baselines3': { name: 'Stable-Baselines3', url: 'https://stable-baselines3.readthedocs.io/en/master', code: 'from stable_baselines3 import *', language: 'Python', example: '' },
    'fastai': { name: 'fastai', url: 'https://docs.fast.ai/', code: 'import fastai', language: 'Python', example: '' },
    'deap': { name: 'Deap', url: 'https://deap.readthedocs.io/en/master/overview.html', code: 'import deap', language: 'Python', example: '' },
    'xgboost': { name: 'XGBoost', url: 'https://xgboost.readthedocs.io/en/latest/', code: 'import xgboost', language: 'Python', example: '' },
    'mlfinlab': { name: 'mlfinlab', url: 'https://github.com/hudson-and-thames/mlfinlab', code: 'import mlfinlab', language: 'Python', example: '', version: '1.6.0' },
    'Accord': { name: 'Accord', url: 'http://accord-framework.net/', code: 'using Accord.MachineLearning;', language: 'C#', example: 'Algorithm.CSharp/AccordVectorMachinesAlgorithm.cs', version: '3.6.0' }
};

function renderMlLibraries(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const pyLibs = {};
    for (const p of defined_python_data['default']) pyLibs[p.name] = p.version;
    const csLibs = {};
    for (const p of defined_csharp_data['default']) csLibs[p.name] = p.version;

    let html = '';
    for (const [key, lib] of Object.entries(defined_ml_libraries)) {
        let version = lib.version || pyLibs[key] || csLibs[key] || '';
        if (!version) continue;
        const exampleUrl = lib.example ? 'https://github.com/QuantConnect/Lean/blob/master/' + lib.example : '';
        const exampleLink = exampleUrl ? `<a href='${exampleUrl}'><i class='fa fa-external-link'></i></a>` : '';
        html += `<tr>
            <td><a target='_BLANK' href='${lib.url}'>${lib.name}</a></td>
            <td>${version}</td>
            <td>${lib.language}</td>
            <td><code>${lib.code}</code></td>
            <td class='centered'>${exampleLink}</td>
        </tr>\n`;
    }
    container.innerHTML = html;
}
