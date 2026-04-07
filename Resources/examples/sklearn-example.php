<h4>Example <?=$number?>: Scikit-Learn</h4>
<p>The below algorithm makes use of <code>Scikit-Learn</code> library to predict the future price movement using the previous 5 OHLCV data. The model is trained using rolling 2-year data. To ensure the model applicable to the current market environment, we recalibrate the model on every Sunday.</p>
<div class="section-example-container testable">
    <pre class="python">from sklearn.svm import SVR
from sklearn.model_selection import GridSearchCV
import joblib

class ScikitLearnExampleAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        
        self.set_cash(100000)
        # Request SPY data for model training, prediction and trading.
        self.symbol = self.add_equity("SPY", Resolution.DAILY).symbol

        # 2-year data to train the model.
        training_length = 252*2
        self.training_data = RollingWindow(training_length)
        # Warm up the training dataset to train the model immediately.
        history = self.history[TradeBar](self.symbol, training_length, Resolution.DAILY)
        for trade_bar in history:
            self.training_data.add(trade_bar)

        # Retrieve already trained model from object store to use immediately.
        if self.object_store.contains_key("sklearn_model"):
            file_name = self.object_store.get_file_path("sklearn_model")
            self.model = joblib.load(file_name)
        # Otherwise, grid serach with hyperparameter choices to create an optimal support vector regressor to predict price movement.
        else:
            param_grid = {'C': [.05, .1, .5, 1, 5, 10], 
                          'epsilon': [0.001, 0.005, 0.01, 0.05, 0.1], 
                          'gamma': ['auto', 'scale']}
            self.model = GridSearchCV(SVR(), param_grid, scoring='neg_mean_squared_error', cv=5)

        # Train the model to use the prediction right away.
        self.train(self.my_training_method)
        # Recalibrate the model weekly to ensure its accuracy on the updated domain.
        self.train(self.date_rules.every(DayOfWeek.SUNDAY), self.time_rules.at(8,0), self.my_training_method)
        
    def get_features_and_labels(self, n_steps=5) -&gt; None:
        # Train and predict the return data, which is more normalized and stationary.
        training_df = self.pandas_converter.get_data_frame[TradeBar](list(self.training_data)[::-1])
        daily_pct_change = training_df.pct_change().dropna()

        # Stack the data for 5-day OHLCV data per each sample to train with.
        features = []
        labels = []
        for i in range(len(daily_pct_change)-n_steps):
            features.append(daily_pct_change.iloc[i:i+n_steps].values.flatten())
            labels.append(daily_pct_change['close'].iloc[i+n_steps])
        features = np.array(features)
        labels = np.array(labels)

        return features, labels

    def my_training_method(self) -&gt; None:
        # Prepare the processed training data.
        features, labels = self.get_features_and_labels()
        # Recalibrate the model based on updated data.
        if isinstance(self.model, GridSearchCV):
            self.model = self.model.fit(features, labels).best_estimator_
        else:
            self.model = self.model.fit(features, labels)

    def on_data(self, slice: Slice) -&gt; None:
        if self.symbol in slice.bars:
            self.training_data.add(slice.bars[self.symbol])

        # Get prediction by the updated features.
        features, _ = self.get_features_and_labels()
        prediction = self.model.predict(features[-1].reshape(1, -1))
        prediction = float(prediction)

        # If the predicted direction is going upward, buy SPY.
        if prediction &gt; 0:
            self.set_holdings(self.symbol, 1)
        # If the predicted direction is going downward, sell SPY.
        elif prediction &lt; 0:            
            self.set_holdings(self.symbol, -1)

    def on_end_of_algorithm(self) -&gt; None:
        # Store the model to object store to retrieve it in other instances in case the algorithm stops.
        model_key = "sklearn_model"
        file_name = self.object_store.get_file_path(model_key)
        joblib.dump(self.model, file_name)
        self.object_store.save(model_key)</pre>
</div>

<h4>Example <?=$number + 1?>: XGBoost Framework Model</h4>
<p>The following algorithm uses the LEAN algorithm framework with XGBoost to trade equities near their earnings dates. It selects a universe based on liquidity and upcoming earnings, trains per-security XGBoost regressors using ROCP, RSI, and ATR indicators, and applies a bracket stop-loss and take-profit for risk management.</p>
<div class="section-example-container testable">
    <pre class="python">class ModulatedNadionReplicator(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.free_portfolio_value_percentage = 0.05
        self.settings.seed_initial_prices = True
        self._spy = self.add_equity("SPY", Resolution.HOUR)

        # Create universe parameters.
        self.universe_settings.resolution = Resolution.HOUR
        self.universe_settings.asynchronous = True
        date_rule = self.date_rules.month_start(self._spy)
        self.universe_settings.schedule.on(date_rule)
        self.add_universe_selection(EarningsVolumeUniverseSelectionModel(10))

        # Add the other framework models.
        self.add_alpha(XGBoostAlphaModel(self, date_rule, self._spy))
        self.set_portfolio_construction(InsightWeightingPortfolioConstructionModel())
        self.set_risk_management(BracketRiskModel(0.05, 0.15))

        # Add a warm up so the algorithm has insights on deployment day.
        self.set_warm_up(timedelta(45))


class EarningsVolumeUniverseSelectionModel(FundamentalUniverseSelectionModel):
    # Selects symbols by liquidity and nearest earnings report date.
    def __init__(self, universe_size=10):
        self._universe_size = universe_size
        super().__init__(self._select)

    def _select(self, fundamental):
        # Sort the top 30 by price and dollar volume.
        liquid = sorted(
            [f for f in fundamental if f.has_fundamental_data and 20 &lt;= f.price &lt;= 200 and f.dollar_volume &gt; 5_000_000],
            key=lambda f: f.dollar_volume
        )[-30:]

        # Select symbols with nearest earnings report dates.
        selected = sorted(
            [f for f in liquid if f.earning_reports.file_date],
            key=lambda f: str(f.earning_reports.file_date)
        )[:self._universe_size]

        return [f.symbol for f in selected]


class XGBoostAlphaModel(AlphaModel):

    def __init__(self, algorithm, date_rule, spy):
        self._algorithm = algorithm
        self._universe = []
        self._insights = []
        self._feature_window = 24
        self._history_bars = 48
        self._rsi_period = 12
        self._scaler = MinMaxScaler(feature_range=(-1, 1))
        self._spy = spy

        # Create rate-of-change indicator for SPY with specified window.
        self._spy.rocp = algorithm.rocp(spy, self._feature_window)
        self._spy.rocp.window.size = self._feature_window
        algorithm.indicator_history(self._spy.rocp, spy, self._spy.rocp.period + self._spy.rocp.window.size)

        algorithm.train(date_rule, algorithm.time_rules.at(8, 0), self._train_models)
        algorithm.schedule.on(date_rule, algorithm.time_rules.after_market_open(self._spy, 30), self._create_insights)

    def _create_insights(self):
        self._insights.clear()

        # Extract predictions from all trained models.
        for security in self._universe:
            # Skip prediction if model not yet trained.
            if not (security.model and security.rocp.window.is_ready and self._spy.rocp.window.is_ready):
                continue
            features, _ = self._build_features(security)
            # Get prediction from the trained model.
            magnitude = security.model.predict(features)[-1]
            # Determine signal direction based on prediction magnitude.
            direction = InsightDirection.FLAT
            if magnitude &gt; 0.05:
                direction = InsightDirection.UP
            elif magnitude &lt; -0.05:
                direction = InsightDirection.DOWN
            # Generate price insights with computed weights and directions.
            self._insights.append(Insight.price(security, timedelta(1), direction, weight=0.3 * abs(magnitude)))

    def update(self, algorithm, data):
        if algorithm.is_warming_up:
            return []

        insights = self._insights.copy()
        self._insights.clear()

        return insights

    def on_securities_changed(self, algorithm, changes):
        for security in changes.added_securities:
            if security == self._spy or security in self._universe:
                continue
            # Add security and register indicators to universe.
            self._universe.append(security)
            security.model = None
            security.rocp = algorithm.rocp(security, self._feature_window)
            security.rsi = algorithm.rsi(security, self._rsi_period, MovingAverageType.SIMPLE)
            security.atr = algorithm.atr(security, self._feature_window, MovingAverageType.SIMPLE)
            # Configure window sizes and populate history for indicator and window.
            bars = algorithm.history[TradeBar](security, self._feature_window * 2)
            for indicator in [security.rocp, security.rsi, security.atr]:
                indicator.window.size = self._feature_window
                for bar in bars:
                    indicator.update(bar)

        for security in changes.removed_securities:
            if security not in self._universe:
                continue
            # Remove security and deregister indicators from universe.
            self._universe.remove(security)
            for indicator in [security.rocp, security.rsi, security.atr]:
                algorithm.deregister_indicator(indicator)

    def _build_features(self, security):
        # Compute momentum and volatility indicators and normalize all features to consistent range.
        scaled = self._scaler.fit_transform(np.hstack(
            [self._get_indicator_history(self._spy.rocp)]
            + [self._get_indicator_history(indicator) for indicator in [security.rocp, security.rsi, security.atr]]
        ))

        return scaled[:, :3], scaled[:, [3]]

    def _get_indicator_history(self, indicator):
        return np.array([x.value for x in indicator.window])[::-1].reshape(-1, 1)

    def _train_models(self):
        # Iterate through all security in the universe for model training.
        for security in self._universe:
            if not (security.rocp.window.is_ready and self._spy.rocp.window.is_ready):
                continue

            features, scaled_rocp = self._build_features(security)
            target = scaled_rocp.ravel()

            # Prepare training and validation splits from the feature matrix.
            x_train, x_valid, y_train, y_valid = train_test_split(
                features, target, test_size=0.35, random_state=42,
            )

            # Define hyperparameter grid for randomized search optimization.
            parameters = {
                'n_estimators': [100, 200, 300, 400],
                'learning_rate': [0.001, 0.005, 0.01, 0.05],
                'max_depth': [8, 10, 12, 15],
                'gamma': [0.001, 0.005, 0.01, 0.02],
                'random_state': [42]
            }
            eval_set = [(x_train, y_train), (x_valid, y_valid)]
            base_model = xgb.XGBRegressor(objective="reg:squarederror", verbosity=0)
            model = RandomizedSearchCV(
                estimator=base_model, param_distributions=parameters, n_iter=5, scoring="neg_mean_squared_error", cv=4, verbose=0,
            )

            # Execute randomized search for optimal model hyperparameters.
            model.fit(x_train, y_train, eval_set=eval_set, verbose=False)
            security.model = model


class BracketRiskModel(RiskManagementModel):

    def __init__(self, drawdown_pct, profit_pct):
        # Store drawdown and profit thresholds as percentage values.
        self._drawdown_pct = -abs(drawdown_pct)
        self._profit_pct = abs(profit_pct)

    def manage_risk(self, algorithm, targets):
        # Build list of risk-adjusted targets.
        adjusted_targets = []

        # Iterate through all securities to evaluate positions.
        for symbol, security in algorithm.securities.items():
            # Reset trailing high for non-invested securities.
            if not security.invested:
                security.trailing_high = None
            # Take profit when unrealized gains exceed the threshold.
            elif security.holdings.unrealized_profit_percent &gt; self._profit_pct:
                adjusted_targets.append(PortfolioTarget(symbol, 0))
                algorithm.insights.cancel([symbol])
            # Initialize trailing high from the entry price.
            elif security.trailing_high is None:
                security.trailing_high = security.holdings.average_price
            # Update trailing high if a new high is reached.
            elif security.trailing_high &lt; security.high:
                security.trailing_high = security.high
            # Exit position when drawdown from trailing high exceeds limit.
            elif (security.low / security.trailing_high) - 1 &lt; self._drawdown_pct:
                adjusted_targets.append(PortfolioTarget(symbol, 0))
                algorithm.insights.cancel([symbol])

        return adjusted_targets
</pre>
</div>