============
Key Concepts
============

---------------------
Developing in the IDE
---------------------

|

Coding Your Algorithm
=====================

The QuantConnect Algorithm Lab allows you to write code in project files and then press the  Build button to compile your algorithm. Build errors will be displayed in the console and highlighted with red lines.

You can add, rename, and delete files in your project in the Projects tab. To rename your project, click into the project, hover over the title, and click on the pencil to the right of it. You can create a virtual folder for your project by placing a slash ("/") into the name of your file. The bare minimum to run an algorithm is your initialization and data event methods.

.. raw:: html
   :file: ./iframes/coding-your-algorithm.html

|

.. image:: https://cdn.quantconnect.com/web/i/docs/20180213-framework-light.png

``QuantConnect Backtesting and Coding Environment``

|

Backtesting Your Algorithm
==========================

Once you've got an algorithm compiling, you can click the Play button to launch a backtest. Results are shown in the Result drop-down menu. Backtesting in parallel is limited to fair usage for each user and may be reduced when it affects others in the community.

Running backtests are summarized on the terminal homepage and can be canceled from there to make room for other backtests.

|

API Help Documentation
======================

The API Tab details the classes and API of the underlying LEAN engine so you can see the methods and class infrastructure. It includes all the indicator helper methods to see all the indicators available. This is automatically generated from the source code of LEAN. If you are able, we recommend becoming familiar with `QCAlgorithm <https://github.com/QuantConnect/Lean/tree/master/Algorithm>`_ base source code.

|

Data Manager
============

The Data Manager Tab is a searchable database of the QuantConnect Data Library. Each asset's details are accessible, including the start date, delist date, source of the data, resolutions available, and the exchange on which the asset is traded.

If you find an issue with the QuantConnect data library, you can report this through our `Data <https://www.quantconnect.com/data>`_ page. The Data page provides a public reporting and tracking system to fix issues systematically.

|

Shortcut Keys
=============

The website coding environment comes with some keyboard shortcuts to make coding easier.

.. list-table::
   :header-rows: 1

   * - Short Cut
     - Description
   * - Ctrl + Space
     - Initiate auto-complete
   * - Ctrl + /	Toggle comments on selected code
     - Ctrl + Up/Down	Increase/decrease font size
   * - Ctrl + F	Find
     - Ctrl + H	Replace
   * - Ctrl + S	Save document and compile project
     - Ctrl + D	Remove line
   * - Ctrl + Z	Undo
     - Ctrl + Y (or Ctrl + Shift + Z)	Redo
   * - Alt + Up/Down	Move line up/down
     - Alt + Shift + Up/Down	Copy lines up/down
   * - Alt + 0 (zero)	Collapse all code blocks
     - Alt + Shift + 0 (zero)	Expand all code blocks

The full list of shortcuts can be found in the `ACE Editor <https://github.com/ajaxorg/ace/wiki/Default-Keyboard-Shortcuts>`_ open source project.

|

Learning Programming
====================

We aim to make it as easy as possible to use QuantConnect, but you still need to be able to program. We've provided some links below to get you started:

.. list-table::
   :header-rows: 1

   * - Language
     - Type
     - Name
     - Producer
   * - Python
     - Text/Video
     - `Introduction to Python <https://developers.google.com/edu/python/>`_
     - Google
   * - Python
     - Interactive
     - `Code Academy - Python <https://www.codecademy.com/learn/learn-python>`_
     - Code Acedemy

|

Local Development
=================

If you prefer coding in your own development environment, you can download the LEAN Open Source project and work locally. We recommend using `Visual Studio <https://www.visualstudio.com/downloads/>`_ as your programming environment because of the plugin that we offer for development. Visual Studio Community Edition has the full power of Visual Studio and enables programming in C#, F#, and Python.

If you're running on Mac or Linux, you can also use `MonoDevelop <https://www.monodevelop.com/download/>`_ or `Xamarin Studio <https://www.visualstudio.com/vs/visual-studio-mac/>`_ and copy-paste your algorithms into QuantConnect.com. Check out the `LEAN-Getting Started Tutorial <https://www.quantconnect.com/lean/docs#topic14.html>`_ for more information. The LEAN installation takes about 5 minutes.

Python users should follow the `Python Installation <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/readme.md>`_ process line by line. It specifically requires Python 3.6.6.