.. _organizations-resource-management:

===================
Resource Management
===================

|


Introduction
============

Running an algorithm on Quantconnect either in backtesting or in live trading requires computational resources. QuantConnect has a reservoir of compute nodes of varying degrees of power available for you to use. Every organization comes with a free B-MICRO backtesting node and R-MICRO research node. However, you can also add additional nodes, allowing your members to run faster backtests and more concurrent live algorithms.

|

Adding and Removing Nodes
=========================

You can view the nodes that are available in the resources page. Nodes are categorized by their responsibility. There are three types of nodes: Backtest nodes, Research nodes, and Live Trading nodes. Each node has its own specifications and can be given an identifying name. You can add a node by clicking Add Node in an empty node module. You will have to provide a name for your new node and also select what type of node you wish to add. You can remove a node by selecting Terminate Node. And you can also rename a node by selecting Set Name.

.. figure:: https://cdn.quantconnect.com/i/tu/organization-resource-1.png

    Node Subscriptions

|

Backtesting Nodes
=================

Adding more backtesting nodes allows you to run more concurrent backtests. Depending on the design of your algorithm you can add more powerful nodes to run faster backtests. Nodes with more RAM allocation can handle more intensive operations such as machine learning and options data processing.

.. list-table::
   :header-rows: 1

   * - Node Type
     - Number of Cores
     - Processing Speed
     - RAM
   * - B-MICRO
     - 1 Cores
     - 3.7 Ghz
     - 8 GB
   * - B2-8
     - 2 Cores
     - 4.9 Ghz
     - 8 GB
   * - B4-12
     - 4 Cores
     - 4.9 Ghz
     - 12 GB
   * - B8-16
     - 8 Cores
     - 4.9 Ghz
     - 16 GB

|

Research Nodes
==============

More powerful research nodes allow you to handle more data and run faster computations in your notebooks.

.. list-table::
   :header-rows: 1

   * - Node Type
     - Number of Cores
     - Processing Speed
     - RAM
   * - R1-4
     - 1 Cores
     - 2.4 Ghz
     - 8 GB
   * - R2-8
     - 2 Cores
     - 2.4 Ghz
     - 8 GB
   * - R4-12
     - 4 Cores
     - 2.4 Ghz
     - 12 GB
   * - R8-16
     - 8 Cores
     - 2.4 Ghz
     - 16 GB

|

Live Nodes
==========

A live trading node is needed for each deployed live algorithm. More powerful live nodes allow you to run algorithms with larger universes, increases your notifications limit and gives you more time for machine-learning training

.. list-table::
   :header-rows: 1

   * - Node Type
     - Number of Cores
     - Processing Speed
     - RAM
   * - L-MICRO
     - 1 Core
     - 2.4 Ghz
     - 0.5 GB
   * - L1-1
     - 1 Core
     - 2.4 Ghz
     - 1 GB
   * - L1-2
     - 1 Core
     - 2.4 Ghz
     - 2 GB
   * - L1-4
     - 1 Core
     - 2.4 Ghz
     - 4 GB

|

Data Storage Limits
===================

The :ref:`Object Store <algorithm-reference-machine-learning-storing-trained-models>` allows you to store project specific data in QuantConnect's cache. This can be useful in saving machine learning models or other data which is expensive to compute or retrieve from external sources. Free accounts are given 500 MB in the object store which you upgrade up to 10 GB of space. You can also view how much storage space you've consumed so far.

.. figure:: https://cdn.quantconnect.com/i/tu/organization-resource-2.png

    Object Store Data Limits

**Deleting Used Space**

You can free used storage space by deleting the project which has stored data in the Object Store. By cloning the project before deleting it, you can maintain your code while also clearing your storage space.


