.. _algorithm-reference-reality-modeling:

=================
Reality Modelling
=================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `CustomModelsAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/CustomModelsAlgorithm.cs>`_
     - `CustomModelsAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/CustomModelsAlgorithm.py>`_
   * - `CustomPartialFillModelAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/CustomPartialFillModelAlgorithm.cs>`_
     - `CustomPartialFillModelAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/CustomPartialFillModelAlgorithm.py>`_
   * - `MarginCallEventsAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/MarginCallEventsAlgorithm.cs>`_
     - `MarginCallEventsAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/MarginCallEventsAlgorithm.py>`_
   * - `BrokerageModelAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BrokerageModelAlgorithm.cs>`_
     - `BrokerageModelAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BrokerageModelAlgorithm.py>`_

|

Introduction
============

Models can be used to improve the accuracy of your backtesting. We provide basic default models that assume you are trading on highly liquid assets, but if you are trading high volumes or on low volume assets, you should update these models to be more realistic.

All models are set on a per security basis. To set a model, first fetch the security object and apply your model.

.. code-block::

    //Set IBM to have a constant $1 transaction fee.
    Securities["IBM"].FeeModel = new ConstantFeeTransactionModel(1);

All models should be set up in your Initialize() method.

|

.. _algorithm-reference-reality-modeling-brokerage-models:

Brokerage Models
================

We provide a shortcut to set common models and properties relating to each of the brokerages we support. These brokerage models set fees, fill models, slippage models, and trading markets for a brokerage. In addition they validate that it is possible to submit trades to the brokerage (e.g. submitting equity trades to a forex only brokerage).

Brokerage models set

    * Transaction fees.
    * Supported asset classes for the brokerage.
    * Validate order types and settings.
    * Default account type (margin or cash account).
    * Support for extended market hours.
    * Default leverage for assets
    * Default settlement models

This gives you enormous control over your algorithm behavior, and allows you to model virtually any brokerage in the world.

In addition to our default brokerage model (`DefaultBrokerageModel <https://www.quantconnect.com/lean/docs#topic8588.html>`_), we provide brokerage models for Interactive Brokers (`InteractiveBrokersBrokerageModel <https://www.quantconnect.com/lean/docs#topic8640.html>`_), FXCM (`FxcmBrokerageModel <https://www.quantconnect.com/lean/docs#topic8607.html>`_), OANDA (`OandaBrokerageModel <https://www.quantconnect.com/lean/docs#topic8607.html>`_), Coinbase Pro (`GDAXBrokerageModel <https://www.quantconnect.com/lean/docs#topic8628.html>`_ ), and Bitfinex (`Bitfinex <https://www.quantconnect.com/lean/docs#topic8628.html>`_)

Brokerage models override any other models you may set for a security.

.. tabs::

   .. code-tab:: c#

        // Set brokerage model using helper methods:
        SetBrokerageModel(BrokerageName.FxcmBrokerage); // Defaults to margin account
        SetBrokerageModel(BrokerageName.Bitfinex, AccountType.Margin); //Or override account type

        // Supported brokerage names:
        BrokerageName.InteractiveBrokersBrokerage
                     .FxcmBrokerage
                     .OandaBrokerage
                     .GDAX
                     .Bitfinex


   .. code-tab:: py

        # Set brokerage model using helper methods:
        self.SetBrokerageModel(BrokerageName.FxcmBrokerage) # Defaults to margin account
        self.SetBrokerageModel(BrokerageName.Bitfinex, AccountType.Margin) # Or override account type

        # Supported brokerage names:
        BrokerageName.InteractiveBrokersBrokerage
                     .FxcmBrokerage
                     .OandaBrokerage
                     .GDAX
                     .Bitfinex

.. tabs::

   .. code-tab:: c#

        // You can also create your own brokerage model: IBrokerageModel
        class MyBrokerage: DefaultBrokerage {
           // Custom implementation of brokerage here.
        }

        SetBrokerageModel(new MyBrokerage());

   .. code-tab:: py

        # You can also create your own brokerage model: IBrokerageModel
        class MyBrokerage(DefaultBrokerage):
           # Custom implementation of brokerage here.

        self.SetBrokerageModel(MyBrokerage())

|

Brokerage Supported Order Types
===============================

Each brokerage model validates the security and order type before it submits an order.

.. list-table::
   :widths: 25 50
   :header-rows: 1

   * - Brokerage
     - Supported Order Types

   * - `FXCM <https://www.fxcm.com/>`_ - FOREX, CFD
     - Market Order, Limit Order, and Stop Market

   * - `Interactive Brokers <https://gdcdyn.interactivebrokers.com/en/?f=%2Fen%2Fpagemap%2Fpagemap_newaccounts.php%3Ftr_ref_id%3D11402>`_ -  Equity, FOREX, Futures, Options
     - Market Order, Limit Order, Stop Market, Stop Limit Order, Market On Open, and Market On Close

   * - `OANDA <https://www.oanda.com/>`_ - FOREX, CFD
     - Market Order, Limit Order, and Stop Market

   * - `Coinbase Pro <https://pro.coinbase.com/>`_ - Crypto
     - Market Order, Limit Order, and Stop Market. Does not allow order update.

   * - Default Brokerage Model
     - 	All

|

.. _algorithm-reference-reality-modeling-fee-models:

Fee Models
==========

Fee models set the fees for each order. We provide customized fee models for all brokerages, but you can also set your own. Like all models, they must be set on a security by security basis.

Fee models implement the ``IFeeModel`` interface. If you wish to implement your own fee model, you can start with the ``FeeModel`` and override methods you wish to change. ``IFeeModel.GetOrderFee`` accepts a single parameter of type ``OrderFeeParameters`` and returns an ``OrderFee`` that represents a cash amount with a given currency.


.. tabs::

   .. code-tab:: c#

        // Set IBM to use a fixed $1.5 per trade fee model.
        Securities["IBM"].FeeModel = new ConstantFeeModel(1.5);

        // Set EURUSD to use FXCM's transaction fees:
        Securities["EURUSD"].FeeModel = new FxcmFeeModel();

   .. code-tab:: py

        # Set IBM to use a fixed $1.5 per trade fee model.
        self.Securities["IBM"].FeeModel = ConstantFeeModel(1.5)

        # Set EURUSD to use FXCM's transaction fees:
        self.Securities["EURUSD"].FeeModel = FxcmFeeModel()

.. tabs::

   .. code-tab:: c#

        // Assigning securities custom fee models:
        Securities["SPY"].SetFeeModel(new CustomFeeModel());

        // Custom fee implementation
        public class CustomFeeModel : FeeModel {
            public override OrderFee GetOrderFee(OrderFeeParameters parameters) {
                // custom fee math
                var fee = Math.Max(1m, parameters.Security.Price
                                   * parameters.Order.AbsoluteQuantity
                                   * 0.00001m);
                return new OrderFee(new CashAmount(fee, "USD"));
            }
        }

        // Non accountCurrency custom fee model to pay order fees in a desired currency
        public class NonAccountCurrencyCustomFeeModel : FeeModel {
            public override OrderFee GetOrderFee(OrderFeeParameters parameters) {
                return new OrderFee(new CashAmount(1m, "ETH"));
            }
        }

   .. code-tab:: py

        # Assigning securities custom fee models:
        self.Securities["SPY"].SetFeeModel(CustomFeeModel())

        # Custom fee implementation
        class CustomFeeModel:
            def GetOrderFee(self, parameters):
                fee = max(1, parameters.Security.Price
                          * parameters.Order.AbsoluteQuantity
                          * 0.00001)
                return OrderFee(CashAmount(fee, 'USD'))

        # Non accountCurrency custom fee model to pay order fees in a desired currency
        class NonAccountCurrencyCustomFeeModel:
            def GetOrderFee(self, parameters):
                return OrderFee(CashAmount(1, 'ETH'))

|

.. _algorithm-reference-reality-modeling-slippage-models:

Slippage Models
===============

Slippage is the difference in price between your last reported quote and the real price the trade filled at. This difference can be positive or negative, as sometimes the price can slip in your favor. In volatile markets, you are likely to experience more slippage.

Slippage models implement the ``ISlippageModel`` interface. We provide the ``VolumeShareSlippageModel`` for forex based securities, and the ``ConstantSlippageModel`` for Equities.

Advanced users may wish to implement their own volatility based slippage model - increasing the accuracy of your backtests in volatile markets.

.. tabs::

   .. code-tab:: c#

        // Assigning securities custom slippage models:
        Securities["SPY"].SetSlippageModel(new CustomSlippageModel(this));

        // Custom slippage implementation
        public class CustomSlippageModel : ISlippageModel {
            private readonly QCAlgorithm _algorithm;

            public CustomSlippageModel(QCAlgorithm algorithm) {
                _algorithm = algorithm;
            }

            public decimal GetSlippageApproximation(Security asset, Order order) {
                // custom slippage math
                var slippage = asset.Price*0.0001m*(decimal) Math.Log10(2*(double) order.AbsoluteQuantity);
                _algorithm.Log("CustomSlippageModel: " + slippage);
                return slippage;
            }
        }

   .. code-tab:: py

        # Assigning securities custom slippage models:
        self.Securities["SPY"].SetSlippageModel(CustomSlippageModel(self))

        # Custom slippage implementation
        class CustomSlippageModel:
            def __init__(self, algorithm):
                self.algorithm = algorithm

            def GetSlippageApproximation(self, asset, order):
                # custom slippage math
                slippage = asset.Price * d.Decimal(0.0001 * np.log10(2*float(order.AbsoluteQuantity)))
                self.algorithm.Log("CustomSlippageModel: " + str(slippage))
                return slippage

|

Fill Models
===========

Fill models give you control over order fills. Each supported order type is passed through a dedicated method and returns an ``OrderEvent`` object. OrderEvents are used to carry information about order partial fills or errors.

The Fill Models implement the ``IFillModel`` interface. If you wish to implement your own fill model, you can start with the ``FillModel`` and override methods you wish to change. We provide the ``ImmediateFillModel``, which assumes orders are immediately and completely filled.

.. tabs::

   .. code-tab:: c#

        // Set the fill models in initialize:
        Securities["IBM"].SetFillModel(new CustomFillModel(this));

        // Custom fill model implementation stub
        public class CustomFillModel : FillModel {
            private readonly QCAlgorithm _algorithm;
            private readonly Random _random = new Random(387510346); // seed it for reproducibility
            private readonly Dictionary _absoluteRemainingByOrderId = new Dictionary();

            public CustomFillModel(QCAlgorithm algorithm) {
                _algorithm = algorithm;
            }

            public override OrderEvent MarketFill(Security asset, MarketOrder order) {
                // this model randomly fills market orders
                decimal absoluteRemaining;
                if (!_absoluteRemainingByOrderId.TryGetValue(order.Id, out absoluteRemaining)) {
                    absoluteRemaining = order.AbsoluteQuantity;
                    _absoluteRemainingByOrderId.Add(order.Id, order.AbsoluteQuantity);
                }
                var fill = base.MarketFill(asset, order);
                var absoluteFillQuantity = (int) (Math.Min(absoluteRemaining, _random.Next(0, 2*(int)order.AbsoluteQuantity)));
                fill.FillQuantity = Math.Sign(order.Quantity) * absoluteFillQuantity;

                if (absoluteRemaining == absoluteFillQuantity) {
                    fill.Status = OrderStatus.Filled;
                    _absoluteRemainingByOrderId.Remove(order.Id);
                }
                else {
                    absoluteRemaining = absoluteRemaining - absoluteFillQuantity;
                    _absoluteRemainingByOrderId[order.Id] = absoluteRemaining;
                    fill.Status = OrderStatus.PartiallyFilled;
                }
                _algorithm.Log("CustomFillModel: " + fill);
                return fill;
            }
        }

   .. code-tab:: py

        # Set the fill models in initialize:
        self.Securities["IBM"].SetFillModel(CustomFillModel(self))

        # Custom fill model implementation stub
        class CustomFillModel(FillModel):
            def __init__(self, algorithm):
                self.algorithm = algorithm
                self.absoluteRemainingByOrderId = {}
                random.seed(100)

            def MarketFill(self, asset, order):
                #if not _absoluteRemainingByOrderId.TryGetValue(order.Id, absoluteRemaining):
                absoluteRemaining = order.AbsoluteQuantity
                self.absoluteRemainingByOrderId[order.Id] = order.AbsoluteQuantity
                fill = super().MarketFill(asset, order)
                absoluteFillQuantity = int(min(absoluteRemaining, random.randint(0, 2*int(order.AbsoluteQuantity))))
                fill.FillQuantity = np.sign(order.Quantity) * absoluteFillQuantity
                if absoluteRemaining == absoluteFillQuantity:
                    fill.Status = OrderStatus.Filled
                    if self.absoluteRemainingByOrderId.get(order.Id):
                        self.absoluteRemainingByOrderId.pop(order.Id)
                else:
                    absoluteRemaining = absoluteRemaining - absoluteFillQuantity
                    self.absoluteRemainingByOrderId[order.Id] = absoluteRemaining
                    fill.Status = OrderStatus.PartiallyFilled
                self.algorithm.Log("CustomFillModel: " + str(fill))
                return fill

|

Buying Power Models
===================

Buying power models (also known as margin models) control how much buying power (leverage) your algorithm has to make trades. Buying power calculations can be very complex and depend on many factors, including the brokerage or even time of day.

Buying power models implement the ``IBuyingPowerModel`` interface and default to the ``BuyingPowerModel`` class. If you wish to implement your own buying power model, you can start with the default and override methods you wish to change.

We also provide the ``PatternDayTradingMarginModel`` to model intraday pattern day trading for US equities, which provides 4x intraday leverage and 2x overnight leverage.

.. tabs::

   .. code-tab:: c#

        // Example of setting a security to use PDT margin models:
        // Generally you do not need to adjust margin models
        Securities["AAPL"].MarginModel = new PatternDayTradingMarginModel();

   .. code-tab:: py

        # Example of setting a security to use PDT margin models:
        # Generally you do not need to adjust margin models
        self.Securities["AAPL"].MarginModel =  PatternDayTradingMarginModel()

The margin call model can be disabled by easily setting the model to ``Null`` at portfolio level.

.. tabs::

   .. code-tab:: c#

        // In Initialize()
        Portfolio.MarginCallModel = MarginCallModel.Null;

   .. code-tab:: py

        #In Initialize()
        self.Portfolio.MarginCallModel = MarginCallModel.Null

|

Settlement Models
=================

After a trade is made brokerages settle the cash depending on the markets and account type. This is managed by our Settlement Models. The most common settlement type is immediate - where the funds are available for trading immediately. This is handled by the ``ImmediateSettlementModel``. US Equities trading with cash accounts is typically settled 3 days after the transaction occurred. This is managed by the ``DelayedSettlementModel``.

Settlement models implement the ``ISettlementModel`` interface. You can create your own settlement model by implementing this method. Most users will not need to create their own settlement model and can use one of the ones provided above.

.. tabs::

   .. code-tab:: c#

        // Set a security to a delayed settlement model: settle 7 days later, at 8am.
        Securities["IBM"].SettlementModel = new DelayedSettlementModel(7, new TimeSpan(8, 0, 0));

   .. code-tab:: py

        # Set a security to a delayed settlement model: settle 7 days later, at 8am.
        self.Securities["IBM"].SettlementModel =  DelayedSettlementModel(7, timedelta(hours = 8))

|

Portfolio Models
================

Portfolio models control how order fills are applied to your portfolio. They take an ``OrderEvent``, ``Security``, and ``SecurityPortfolioManager`` object and update the holdings to reflect the new final position. You should only need to update your portfolio model when you are creating a new asset type.

Portfolio models implement the ``ISecurityPortfolioModel`` interface.

|

Volatility Model
================

The volatility model is a property of a security. Exactly how volatility is calculated varies a lot between strategies, so we've provided an override point here. Volatility models get updated with data each time step and are expected to be updated immediately. This is primarily required for options backtesting.

Volatility models implement the ``VolatilityModel`` interface. We default to the ``NullVolatilityModel`` which returns 0 volatility at all times. As a helper, we also provide the ``RelativeStandardDeviationVolatilityModel``, which calculates the volatility based on standard deviation.

