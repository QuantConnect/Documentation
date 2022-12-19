<p>The following table shows the supported machine learning libraries:</p>

<table class='qc-table table'>
    <thead>
      <tr>
          <th>Library</th>
          <th>Research Tutorial</th>
          <th>Documentation</th>
      </tr>
    </thead>
    <tbody>

<?php

class MachineLearningLibrary {
    public $name;
    public $tutorialLink;
    public $documentationLink;

    public function __construct($name, $tutorialLink, $documentationLink)
    {
        $this->name = $name;
        $this->tutorialLink = $tutorialLink;
        $this->documentationLink = $documentationLink;
    }
}

$libraries = array(
    new MachineLearningLibrary("Keras", "keras", "https://keras.io/"),
    new MachineLearningLibrary("TensorFlow", "tensorflow", "https://www.tensorflow.org/"),
    new MachineLearningLibrary("Scikit-Learn", "scikit-learn", "https://scikit-learn.org/stable/index.html"),
    new MachineLearningLibrary("hmmlearn", "hmmlearn", "https://hmmlearn.readthedocs.io/en/latest/"),
    new MachineLearningLibrary("gplearn", "gplearn", "https://gplearn.readthedocs.io/en/stable/intro.html"),
    new MachineLearningLibrary("PyTorch", "pytorch", "https://pytorch.org/"),
    new MachineLearningLibrary("Stable Baselines", "stable-baselines", "https://stable-baselines.readthedocs.io/en/master/"),
    new MachineLearningLibrary("tslearn", "tslearn", "https://tslearn.readthedocs.io/en/stable/"),
    new MachineLearningLibrary("XGBoost", "xgboost", "https://xgboost.readthedocs.io/en/latest/")
);

foreach ($libraries as $library) {
    echo "<tr>
              <td>{$library->name}</td>
              <td><a href='/docs/v2/research-environment/machine-learning/{$library->tutorialLink}'>Tutorial</a></td>
              <td><a rel='nofollow' target='_blank' href='{$library->documentationLink}'>Documentation</a></td>
          </tr>
    ";
}

?>
        
    </tbody>
</table>
