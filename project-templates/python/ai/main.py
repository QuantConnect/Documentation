# region imports
import tensorflow as tf
from AlgorithmImports import *
# endregion


class TensorFlowAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        # Request SPY data for model training, prediction and trading.
        self._spy = self.add_equity("SPY")
        # Hyperparameters to create the MLP model.
        num_factors = 5
        num_neurons_1 = 10
        num_neurons_2 = 10
        num_neurons_3 = 5
        self._epochs = 100
        self._learning_rate = 0.0001
        # Create the MLP model with ReLU activation.
        self._model = tf.keras.Sequential([
            tf.keras.layers.Dense(num_neurons_1, activation=tf.nn.relu, input_shape=(num_factors,)),  # input shape required
            tf.keras.layers.Dense(num_neurons_2, activation=tf.nn.relu),
            tf.keras.layers.Dense(num_neurons_3, activation=tf.nn.relu),
            tf.keras.layers.Dense(1)
        ])
        # 2-year data to train the model.
        training_length = 500
        self._spy.session.size = training_length
        # Warm up the training dataset to train the model immediately.
        history = self.history[TradeBar](self._spy, training_length, Resolution.DAILY)
        for trade_bar in history:
            self._spy.session.update(trade_bar)
        # Train the model to use the prediction right away.
        self.train(self._my_training_method)
        # Recalibrate the model weekly to ensure its accuracy on the updated domain.
        self.train(self.date_rules.week_start(), self.time_rules.at(8, 0), self._my_training_method)

    def _get_features_and_labels(self, lookback: int = 5) -> tuple[np.ndarray, np.ndarray]:
        lookback_series = []
        # Train and predict on N-period differencing data which is more normalized and stationary.
        data = pd.Series([bar.close for bar in self._spy.session][::-1])
        for i in range(1, lookback + 1):
            df = data.diff(i)[lookback:-1]
            df.name = f"close-{i}"
            lookback_series.append(df)
        X = pd.concat(lookback_series, axis=1).reset_index(drop=True).dropna()
        Y = data.diff(-1)[lookback:-1].reset_index(drop=True)
        return X.values, Y.values

    def _my_training_method(self) -> None:
        # Prepare the processed training data.
        features, labels = self._get_features_and_labels()
        # Define the loss function using MSE for this example.
        def loss_mse(target_y: np.ndarray, predicted_y: tf.Tensor) -> tf.Tensor:
            return tf.reduce_mean(tf.square(target_y - predicted_y))
        # Train the model with Adam optimizer.
        optimizer = tf.keras.optimizers.Adam(learning_rate=self._learning_rate)
        for i in range(self._epochs):
            with tf.GradientTape() as t:
                loss = loss_mse(labels, self._model(features))
            jac = t.gradient(loss, self._model.trainable_weights)
            optimizer.apply_gradients(zip(jac, self._model.trainable_weights))

    def on_data(self, data: Slice) -> None:
        if data.bars:
            # Get prediction using the updated features.
            new_features = self._get_features_and_labels()[0]
            prediction = self._model(new_features)
            prediction = float(prediction.numpy()[-1])
            # If the predicted direction is upward buy SPY, otherwise sell.
            self.set_holdings(self._spy, 1 if prediction > 0 else -1)
