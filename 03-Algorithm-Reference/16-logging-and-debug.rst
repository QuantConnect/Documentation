.. _algorithm-reference-logging-and-debug:

=======================================
Algorithm Reference - Logging and Debug
=======================================

|

Debug Messages
==============

Algorithms can send debug messages to the console using the ``Debug(string message)`` method. Debug messages are capped at 200 characters long. If multiple identical messages are sent within 1 second, we rate limit the messages to avoid crashing your browser.

.. tabs::

   .. code-tab:: c#

        Debug(Time.ToString("o") + " Purchasing AAPL: " + data["AAPL"].Price);

   .. code-tab:: py

        self.Debug(str(self.Time) + " Purchasing AAPL: " + str(slice["SPY"].Price))

.. figure:: https://cdn.quantconnect.com/web/i/docs/docs-debug-appl-price.png

            Debug Message in IDE Console

|

Error Messages
==============

Algorithms can send error messages to the console using the ``Error(string message)`` method. Error messages appear red in the console, so you can see them easily. Like debug messages, error messages are rate limited to avoid flooding your browser.

.. tabs::

   .. code-tab:: c#

        Error("Volatility too high, terminating algorithm.");
        Quit(); //Option: Instruct algorithm to stop.

   .. code-tab:: py

        self.Error("Volatility too high, terminating algorithm.")
        self.Quit() # Optional: Instruct algorithm to stop.

.. figure:: https://cdn.quantconnect.com/web/i/docs/docs-console-error-message.png

            Error Message in IDE Console

|

Logging
=======

Algorithms can save more detailed messaging to log files for later analysis using ``Log(string message)``. At the end of the backtest, a link will be presented to view your results. In live trading, a log viewer lets you view log results while the algorithm is running. Because of data vendor limitations, price information cannot be recorded in logs.

If multiple identical messages are sent, they are filtered out. You can intentionally bypass this by adding a timestamp to your log messages.

Because of vendor limitations, free users are capped to 10kb of logs per backtest with a maximum of 3Mb per day. Users with a subscription can generate up to 100kb of logs per backtest.

.. tabs::

   .. code-tab:: c#

        Log("Additional detailed logging messages");

   .. code-tab:: py

        self.Log("Additional detailed logging messages")

.. figure:: https://cdn.quantconnect.com/web/i/docs/docs-log-message.png

            Log Message in IDE Console


