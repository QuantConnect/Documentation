<style> 
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

$libraries = array(
    new MachineLearningLibraryForWritingAlgorithm("TensorFlow", "2.11.0", "Python", "import tensorflow", "https://www.tensorflow.org/", "https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/TensorFlowNeuralNetworkAlgorithm.py"),
    new MachineLearningLibraryForWritingAlgorithm("SciKit Learn", "1.2.1", "Python", "import sklearn", "https://scikit-learn.org/stable/", "https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ScikitLearnLinearRegressionAlgorithm.py"),
    new MachineLearningLibraryForWritingAlgorithm("Py Torch", "1.13.1", "Python", "import torch", "https://pytorch.org/", "https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/PytorchNeuralNetworkAlgorithm.py"),
    new MachineLearningLibraryForWritingAlgorithm("Keras", "2.11.0", "Python", "import keras", "https://keras.io/", "https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/KerasNeuralNetworkAlgorithm.py"),
    new MachineLearningLibraryForWritingAlgorithm("gplearn", "0.4.2", "Python", "import gplearn", "https://gplearn.readthedocs.io/en/stable/intro.html", ""),
    new MachineLearningLibraryForWritingAlgorithm("hmmlearn", "0.2.8", "Python", "import hmmlearn", "https://hmmlearn.readthedocs.io/en/latest/", ""),
    new MachineLearningLibraryForWritingAlgorithm("tsfresh", "0.20.0", "Python", "import tsfresh", "https://tsfresh.readthedocs.io/en/latest/", ""),
    new MachineLearningLibraryForWritingAlgorithm("Stable-Baselines3", "1.7.0", "Python", "from stable_baselines3 import *", "https://stable-baselines3.readthedocs.io/en/master", ""),
    new MachineLearningLibraryForWritingAlgorithm("fastai", "2.7.11", "Python", "import fastai", "https://docs.fast.ai/", ""),
    new MachineLearningLibraryForWritingAlgorithm("Deap", "1.3.3", "Python", "import deap", "https://deap.readthedocs.io/en/master/overview.html", ""),
    new MachineLearningLibraryForWritingAlgorithm("XGBoost", "1.7.4", "Python", "import xgboost", "https://xgboost.readthedocs.io/en/latest/", ""),
    new MachineLearningLibraryForWritingAlgorithm("mlfinlab", "1.6.0", "Python", "import mlfinlab", "https://github.com/hudson-and-thames/mlfinlab", ""),
    new MachineLearningLibraryForWritingAlgorithm("Accord", "3.6.0", "C#", "using Accord.MachineLearning;", "http://accord-framework.net/", "https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AccordVectorMachinesAlgorithm.cs")
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
</table>