

.. image:: https://cdn.quantconnect.com/web/i/docs/docs_home_icon.rev0.png
   :align: left

=============
Documentation
=============

.. raw:: html

    <h2 style="margin-top: 8px;">
        <span style="font-size: 22px;">Learn to use QuantConnect and Explore Our Features</span>
    </h2>

|

.. image:: https://cdn.quantconnect.com/web/i/docs/home/docs_using_qc_rev0.svg
   :width: 60
   :height: 60
   :align: left

**Using QuantConnect**

Learn the basics of working in the terminal

|

.. image:: https://cdn.quantconnect.com/web/i/docs/home/docs_algo_ref_rev0.svg
   :width: 60
   :height: 60
   :align: left

**Algorithm Reference**

Reference to building an Algorithm

|

.. image:: https://cdn.quantconnect.com/web/i/docs/home/docs_algo_framework_rev0.svg
   :width: 60
   :height: 60
   :align: left

**Algorithm Framework**

Reusable modular code to fast track design

|

.. image:: https://cdn.quantconnect.com/web/i/docs/home/docs_research_rev0.svg
   :width: 60
   :height: 60
   :align: left

**Research**

Interactive Jupyter development API

|

.. image:: https://cdn.quantconnect.com/web/i/docs/home/docs_tutorials_rev0..svg
   :width: 60px
   :height: 60px
   :align: left

**Tutorials**

Series of written introductions to python and finance

|

.. image:: https://cdn.quantconnect.com/web/i/docs/home/docs_live_trading_rev0.svg
   :width: 60px
   :height: 60px
   :align: left

**Live Trading**

Harness live-specific features and trade on your brokerage

|

.. image:: https://cdn.quantconnect.com/web/i/docs/home/docs_using_lean_rev0.svg
   :width: 60px
   :height: 60px
   :align: left

**Using LEAN**

Go deep into the engine powering QuantConnect

|

.. image:: https://cdn.quantconnect.com/web/i/docs/home/docs_organization_rev0.svg
   :width: 60px
   :height: 60px
   :align: left

**Organizations on QuantConnect**

Build your organization from QuantConnect's foundation

|

Try QuantConnect Now
====================

QuantConnect's LEAN engine manages your portfolio and data feeds letting you focus on your algorithm strategy and execution. Data is piped into your strategy via event handlers, upon which you can place trades. We provide basic portfolio management and fill modelling underneath the hood automatically. This is provided by the QCAlgorithm base class.

|

|design| Design and Test Your Strategy

.. |design| image:: https://cdn.quantconnect.com/web/i/docs/home/docs_design_test_icon_rev0.svg
   :width: 25px

|deploy| Deploy it to Your Live Brokerage

.. |deploy| image:: https://cdn.quantconnect.com/web/i/docs/home/docs_deploy_icon_rev0.svg
   :width: 25px


|code| Code in Multiple Languages

.. |code| image:: https://cdn.quantconnect.com/web/i/docs/home/docs_code_icon_rev0.svg
   :width: 25px


|servers| Harness Our Cluster of Servers

.. |servers| image:: https://cdn.quantconnect.com/web/i/docs/home/docs_harness_icon_rev0.svg
   :width: 25px

|

.. tabs::

   .. code-tab:: c#

        public class BasicTemplateAlgorithm : QCAlgorithm
        {
          public override void Initialize()
          {
             // Setup algorithm requirements: cash, dates and securities.
             // Initialize is called once at the start of the algorithm.
          }

          public override void OnData(Slice data) {
             // Data requested is then piped into event handlers like this one.
          }
        }

   .. code-tab:: py

        class BasicTemplateAlgorithm(QCAlgorithm):
          def Initialize(self):
                '''Initialise the data and resolution required, as well as the
                cash and start-end dates for your algorithm. All algorithms must initialized.'''
                pass

            def OnData(self, data):
                '''OnData event is the primary entry point for your algorithm. Each new data
                point will be pumped in here. data is a Slice object keyed by symbol containing
                the stock data'''
                pass

|

Supported Brokerages
====================

|interactive-brokers|

|

|oanda|

|

|fxcm|

|

|coinbase|

|

|alpaca|

|

|bitfinex|

.. |interactive-brokers| image:: https://cdn.quantconnect.com/web/i/splash/Interactive%20Brokers%20Brokerage.png
   :height: 59px

.. |oanda| image:: https://cdn.quantconnect.com/web/i/splash/OANDA%20Brokerage.png
   :height: 59px

.. |fxcm| image:: https://cdn.quantconnect.com/web/i/splash/FXCM%20Brokerage.png
   :height: 59px

.. |coinbase| image:: https://cdn.quantconnect.com/web/i/coinbase_pro_rev0.png
   :height: 59px

.. |alpaca| image:: https://cdn.quantconnect.com/web/i/splash/Alpaca%20Brokerage.png
   :height: 59px

.. |bitfinex| image:: https://cdn.quantconnect.com/web/i/splash/Bitfinex%20Exchange.png
   :height: 59px