<h4>Example <?=$number?>: Scikit-Learn</h4>
<p>The below algorithm makes use of <code>Scikit-Learn</code> library to predict the future price movement using the previous 5 OHLCV data. The model is trained using rolling 2-year data. To ensure the model applicable to the current market environment, we recalibrate the model on every Sunday.</p>
<div class="section-example-container">
    <pre class="python">from sklearn.svm import SVR
from sklearn.model_selection import GridSearchCV
import joblib

class ScikitLearnExampleAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2022, 7, 4)
        self.set_cash(100000)
        self.symbol = self.add_equity("SPY", Resolution.DAILY).symbol

        training_length = 252*2
        self.training_data = RollingWindow[TradeBar](training_length)
        history = self.history[TradeBar](self.symbol, training_length, Resolution.DAILY)
        for trade_bar in history:
            self.training_data.add(trade_bar)

        if self.object_store.contains_key("sklearn_model"):
            file_name = self.object_store.get_file_path("sklearn_model")
            self.model = joblib.load(file_name)

        else:
            param_grid = {'C': [.05, .1, .5, 1, 5, 10], 
                          'epsilon': [0.001, 0.005, 0.01, 0.05, 0.1], 
                          'gamma': ['auto', 'scale']}
            self.model = GridSearchCV(SVR(), param_grid, scoring='neg_mean_squared_error', cv=5)

        self.train(self.my_training_method)
        self.train(self.date_rules.every(DayOfWeek.SUNDAY), self.time_rules.at(8,0), self.my_training_method)
        
    def get_features_and_labels(self, n_steps=5) -&gt; None:
        training_df = self.pandas_converter.get_data_frame[TradeBar](list(self.training_data)[::-1])
        daily_pct_change = training_df.pct_change().dropna()

        features = []
        labels = []
        for i in range(len(daily_pct_change)-n_steps):
            features.append(daily_pct_change.iloc[i:i+n_steps].values.flatten())
            labels.append(daily_pct_change['close'].iloc[i+n_steps])
        features = np.array(features)
        labels = np.array(labels)

        return features, labels

    def my_training_method(self) -&gt; None:
        features, labels = self.get_features_and_labels()
        if isinstance(self.model, GridSearchCV):
            self.model = self.model.fit(features, labels).best_estimator_
        else:
            self.model = self.model.fit(features, labels)

    def on_data(self, slice: Slice) -&gt; None:
        if self.symbol in slice.bars:
            self.training_data.add(slice.bars[self.symbol])

        features, _ = self.get_features_and_labels()
        prediction = self.model.predict(features[-1].reshape(1, -1))
        prediction = float(prediction)

        if prediction &gt; 0:
            self.set_holdings(self.symbol, 1)
        elif prediction &lt; 0:            
            self.set_holdings(self.symbol, -1)

    def on_end_of_algorithm(self) -&gt; None:
        model_key = "sklearn_model"
        file_name = self.object_store.get_file_path(model_key)
        joblib.dump(self.model, file_name)
        self.object_store.save(model_key)</pre>
</div>