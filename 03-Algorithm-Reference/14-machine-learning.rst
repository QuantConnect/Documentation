.. _algorithm-reference-machine-learning:

================
Machine Learning
================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `TrainingExampleAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/TrainingExampleAlgorithm.cs>`_
     - `TrainingExampleAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/TrainingExampleAlgorithm.py>`_
   * - `TrainingInitializeRegressionAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/TrainingInitializeRegressionAlgorithm.cs>`_
     - `TrainingInitializeRegressionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/TrainingInitializeRegressionAlgorithm.py>`_
   * - `AccordVectorMachinesAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AccordVectorMachinesAlgorithm.cs>`_
     - `TensorFlowNeuralNetworkAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/TensorFlowNeuralNetworkAlgorithm.py>`_
   * -
     - `ScikitLearnLinearRegressionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ScikitLearnLinearRegressionAlgorithm.py>`_
   * -
     - `PytorchNeuralNetworkAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/PytorchNeuralNetworkAlgorithm.py>`_
   * -
     - `KerasNeuralNetworkAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/KerasNeuralNetworkAlgorithm.py>`_

|

Introduction
============

QuantConnect supports using machine learning techniques for your trading strategies. In designing a machine learning strategy, you should consider the time required to train your models, so they are ready for use when the market is open. In the following sections, we'll explore how to schedule a model training which receives a longer time allowance, and then how to store the result of your work.

|

.. _algorithm-reference-machine-learning-training-models:

Training Models
===============

The ``Train`` feature allows you to get an increase in computation time to perform your model training for your machine learning strategies. Normally algorithms must perform all necessary work within 10 minutes before returning from the OnData method. With the training features, these limits have been increased to more than 30 minutes to give you time to run your models.

Important: In backtesting, the ``Train`` method is *synchronous* and will block your program execution while the model is executed. However, in live trading, it runs *asynchronously*. Because of this, you should be careful to ensure your model is ready to use before continuing program execution. This training occurs on a separate thread, so we recommend a boolean flag to notify your algorithm of the model state. We post some examples of this in the Examples section below.

**Initializing a Model**

Models can be trained immediately with the ``Train()`` method. This is most useful for training the model immediately on the deployment of your strategy to production or when its performance begins to degrade.

.. tabs::

   .. code-tab:: c#

        // Trigger a training immediately in your training method.
        Train(MyTrainingMethod);


   .. code-tab:: py

        # Trigger a training immediately in your training method.
        self.Train(self.MyMethod)

**Scheduling Training**

Model training can be scheduled in a similar way to a scheduled event. To do this, you need to pass in a ``DateRule`` and ``TimeRule`` argument to the ``Train`` method. You can see a full list of available date and time rules in the `scheduled event <https://www.quantconnect.com/docs/algorithm-reference/scheduled-events>`_ documentation.

.. tabs::

   .. code-tab:: c#

        // Set TrainingMethod to be executed at 8:00 am every Sunday
        Train(DateRules.Every(DayOfWeek.Sunday), TimeRules.At(8, 0), MyTrainingMethod);


   .. code-tab:: py

        # Set TrainingMethod to be executed at 8:00 am every Sunday
        self.Train(self.DateRules.Every(DayOfWeek.Sunday), self.TimeRules.At(8,0), self.MyTrainingMethod)

We recommend scheduling your training for the evening or early mornings when the market is closed to get the best compute allocation. While the market is open, your CPU is occupied with processing incoming tick data and handling other LEAN events.

**Training Limits**

Training resources are allocated with a `leaky bucket algorithm <https://en.wikipedia.org/wiki/Leaky_bucket>`_ where a maximum of n-minutes can be used in a single training, and the allocated compute refills over time. This gives you burst allocations when needed and recharges the allowance to prepare for the next training. Limits are configured by server type according to the table below.

.. list-table::
   :widths: 25 50
   :header-rows: 1

   * - Live Server Type
     - Resource Allocation

   * - 512 MB
     - Capacity: 30min. Refill Rate: 5min/24hrs.

   * - 1024 MB
     - Capacity: 60min. Refill Rate: 10min/24hrs.

   * - 2048
     - Capacity: 90min. Refill Rate: 15min/24hrs.

|

.. _algorithm-reference-machine-learning-storing-trained-models:

Storing Trained Models
======================

Once models are trained you can store the results into an *object store*. This is a permanent project-specific storage location for data located in QuantConnect. Objects are accessible from backtesting and live, and are stored with a key. You can think of this almost like a private project DropBox for your model data.

The object store can be used as a back up of your algorithm variables or to load a complex AI model that you don't wish to re-train. Once stored, your data is backed up on QuantConnect servers until requested.

When deploying a live algorithm, your state is loaded from the object store on deployment. Currently, it is not "refreshed", so you will need to redeploy the live algorithm when you wish to reload your data.

**Storing Data**

The Object Store is accessible in the root of your algorithm. It has the following methods available for storing data. You can see an example of using these in the `demonstration algorithm <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ObjectStoreExampleAlgorithm.py>`_.


.. tabs::

   .. code-tab:: py

        self.ObjectStore.Save("key", "value")                        # Save data as a string
        self.ObjectStore.SaveBytes("key", bytearray(objectValue))    # Save data as a bytes
        self.ObjectStore.SaveJson("key", objectValue)                # Save object as JSON encoded string
        self.ObjectStore.SaveXml("key", objectValue)                 # Save object as XML encoded string

**Reading Data**


.. tabs::

   .. code-tab:: py

        val = self.ObjectStore.Read("key")                        # Read data as string
        bytes = self.ObjectStore.ReadBytes("key")                 # Read data as bytes
        jsonObj = self.ObjectStore.ReadJson("key")    # Deserialize a JSON object from storage
        xmlObj = self.ObjectStore.ReadXml("key")      # Deserialize a XML object from storage

**Deleting Data**

You can delete data from the object store using the ``Delete()`` method.

.. tabs::

   .. code-tab:: py

        self.ObjectStore.Delete("key")            # Delete the data from the store

**Storage Limits**

.. list-table::
   :widths: 25 50
   :header-rows: 1

   * - Subscription Level
     - Resource Allocation

   * - Free
     - 5MB, 100 Files.

   * - Prime
     - 50MB, 1000 Files

   * - Professional
     - 	500MB, 10,000 Files

|

.. _algorithm-reference-machine-learning-supported-libraries:

Supported Libraries
===================

QuantConnect has 11 supported machine learning libraries installed and available. You can import these packages and use them as demonstrated below.

.. list-table::
   :header-rows: 1

   * - Name
     - Version
     - Language
     - Import Statement
     - Example

   * - `TensorFlow <https://www.tensorflow.org/>`_
     - 1.13.1
     - Python
     - import tensorflow
     - `TensorFlow Example <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/TensorFlowNeuralNetworkAlgorithm.py>`_

   * - `SciKit Learn <https://scikit-learn.org/stable/>`_
     - 0.21.3
     - Python
     - import sklearn
     - `SciKit Example <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ScikitLearnLinearRegressionAlgorithm.py>`_

   * - `Py Torch <https://pytorch.org/>`_
     - 1.1.0
     - Python
     - import torch
     - `Py Torch Example <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/PytorchNeuralNetworkAlgorithm.py>`_

   * - `Keras <https://keras.io/>`_
     - 2.2.4
     - Python
     - import keras
     - `Keras Example <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/KerasNeuralNetworkAlgorithm.py>`_

   * - `Theano <http://deeplearning.net/software/theano/>`_
     - 1.0.4
     - Python
     - import theano
     -

   * - `hmmlearn <https://hmmlearn.readthedocs.io/en/latest/>`_
     - 0.2.2
     - Python
     - import hmmlearn
     -

   * - `tsfresh <https://tsfresh.readthedocs.io/en/latest/>`_
     - 0.12.0
     - Python
     - import tsfresh
     -

   * - `fastai <https://docs.fast.ai/>`_
     - 1.0.54
     - Python
     - import fastai
     -

   * - `Deap <https://deap.readthedocs.io/en/master/overview.html>`_
     - 1.0.54
     - Python
     - import deap
     -

   * - `mlfinlab <https://github.com/hudson-and-thames/mlfinlab>`_
     - 0.9.3
     - Python
     - import mlfinlab
     -

   * - `Accord <http://accord-framework.net/>`_
     - 3.60
     - CSharp
     - Using Accord.MachineLearning;
     - `Accord Example <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AccordVectorMachinesAlgorithm.cs>`_

   * - `AForge.Neuro <http://www.aforgenet.com/framework/samples/>`_
     - 2.2.5
     - CSharp
     - using AForge.Neuro;
     -

|

Examples
========

**Using Semaphore to Synchronize Model Usage and Training**

A "semaphore" is a thread-safe flag you can use to synchronize program operation across different threads. Because your model trainings can take a long time, they are processed in a separate thread from your algorithm data. You need to confirm the model is ready to use before using it to generate predictions.

.. code-block::

    class SemaphoreTrainingAlgorithm(QCAlgorithm):

        # Model Object
        model = None
        # Model State Flag
        modelIsTraining = False

        def Initialize(self):
            self.Train(self.MyTraining)

        def MyTraining(self):
            self.modelIsTraining = True
            # Perform Work.....
            self.modelIsTraining = False

        def OnData(self, data):
            # Do not use model while its being trained.
            if self.modelIsTraining:
                return

            # Once training is complete; use the model safely.
            result = self.model.Predict()
