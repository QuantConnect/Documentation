.. _live-trading-notifications:

=============
Notifications
=============

|

Demonstration Algorithm
=======================

.. list-table::
   :header-rows: 1

   * - C#
     - Py

   * - `LiveFeaturesAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/LiveFeaturesAlgorithm.cs>`_
     - `LiveFeaturesAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/LiveFeaturesAlgorithm.py>`_

|

Introduction
============

Through QuantConnect Live Trading, you can send messages to alert you about market events or your algorithm's actions. Currently, QuantConnect supports three types of notification methods: Email, SMS, or WebHook.

To prevent sending thousands of messages each time you run a backtest, all notifications are ignored in backtesting.

|

Email Notifications
===================

To request email notifications on market events, you need to use the ``Notify.Email(email, subject, contents)`` method. This will trigger QuantConnect to immediately send an email in live trading.

.. tabs::

   .. code-tab:: c#

        //Send yourself an email on live trade executions
        Notify.Email("myEmailAddress@gmail.com", "Live Trade Executed", "Bought 100 Shares of AAPL");

   .. code-tab:: py

        #Send yourself an email on live trade executions
        self.Notify.Email("myEmailAddress@gmail.com", "Live Trade Executed", "Bought 100 Shares of AAPL")

Notification emails are rate limited to 20 messages per hour.

|

SMS Notifications
=================

In live trading, QuantConnect supports sending SMS notifications by calling the ``Notify.Sms(phone, message)`` method anywhere from your algorithm class.

.. tabs::

   .. code-tab:: c#

        Notify.Sms("+1 1234 5678", "SPY Trade at " + data.Bars["SPY"].Close);

   .. code-tab:: py

        self.Notify.Sms("+1234567890", "BTCUSD Price at " + str(slice["BTCUSD"].Close))

SMS Notifications are rate limited to 20 messages per hour, however fully utilizing 20 messages per hour will incur heavy costs at QuantConnect's expense. We request you use discretion when sending SMS messages to ensure we can continue offering this free service for everyone.

|

Webhook Notifications
=====================

With QuantConnect live trading, you can perform a webhook to your server to trigger an event. The hook is an HTTP-POST request to the URL you provide, with any data you provide encoded as a JSON object in the POST body. The request is sent with a timeout of 300s.

To perform a webhook use the ``Notify.Web(url, data=null)`` method. The data parameter is optional.

.. tabs::

   .. code-tab:: c#

        Notify.Web("myServer.com", payload);

   .. code-tab:: py

        self.Notify.Web("myServer.com", payload)

Webhook requests are limited to 20 requests per hour.

|

Limits of Use
=============

QuantConnect sources data from external vendors and must agree and uphold their license terms. These agreements do not allow redistribution of the live financial data. Because of this, you cannot use the notification system to distribute financial data to external clients.