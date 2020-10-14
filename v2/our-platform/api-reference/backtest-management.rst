===================
Backtest Management
===================

The QuantConnect.com API exposes methods for creating, reading, listing, updating, and deleting backtests in the user's account.

|

----------------------------------------------------------------

Backtest management API calls return either a Backtest object, a Backtest List object, or a Backtest Report object.

|

Backtest Object
---------------

A Backtest objects describes a Backtest response packet from the QuantConnect.com API.

Attributes
==========

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - name
     - string
     - Name of the backtest.
   * - note
     - string
     - Note on the backtest, attached by the user.
   * - backtestId
     - string
     - Assigned backtest Id.
   * - completed
     - boolean
     - True when the backtest is completed.
   * - progress
     - decimal
     - Progress of the backtest in percent 0-1.
   * - result
     - A BacktestResult object (see full API reference for its attributes)
     - Result-specific items from the backtest packet.
   * - error
     - string
     - Backtest error message.
   * - stacktrace
     - string
     - Backtest error stacktrace.
   * - created
     - string
     - Date and time the project was created.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Backtest List Object
--------------------

A Backtest List object contains a list of Backtest objects for a project.

Attributes
==========

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - backtests
     - Array of Backtest objects
     - List of backtest for a project.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Backtest Report Object
----------------------

A Backtest Report object contains the report of a backtest.

Attributes
==========

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - report
     - string
     - HTML data of the report, with embedded base64 images.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Create a Backtest
-----------------

Create a new backtest.

Path
====

``POST`` /backtests/create

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "compileId": 12345,
      "backtestName": "My first backtest"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id of the project which was compiled.
   * - compileId ``(Required)``
     - int
     - Compile Id returned from the compile creation request.
   * - backtestName ``(Required)``
     - string
     - Name for the new backtest

Response
========

Returns the newly created Backtest object.

.. code-block::

    {
      "name": "My first backtest",
      "backtestId": "abc123",
      "completed": false,
      "progress": 0.5
    }

|

----------------------------------------------------------------

Read a Backtest
---------------

Read out a single backtest from one of the user's projects.

Path
====

``POST`` /backtests/read

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "backtestId": "abc123"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id of the project from which to read a backtest.
   * - backtestId ``(Required)``
     - string
     - Compile-specific backtest Id of the backtest to read.

Response
========

Returns the requested Backtest object.

.. code-block::

    {
      "name": "My first backtest",
      "backtestId": "abc123",
      "completed": false,
      "progress": 0.5
    }

|

----------------------------------------------------------------

Update a Backtest
-----------------

Update a backtest's name and/or note.

Path
====

``POST`` /backtests/update

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "backtestId": "abc123",
      "name": "My backtest's new name",
      "note": "My personal note"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id for the backtest we want to update.
   * - backtestId ``(Required)``
     - string
     - Backtest Id of the backtest we want to update.
   * - name
     - string
     - Name we'd like to assign to the backtest.
   * - note
     - string
     - Note attached to the backtest.

Response
========

Returns a RestResponse object which indicates whether the request executed successfully.

.. code-block::

    {
      "success": true,
    }

|

----------------------------------------------------------------

Delete a Backtest
-----------------

Delete the specified backtest from the specified project.

Path
====

``POST`` /backtests/delete

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "backtestId": "abc123"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id for the backtest we want to delete.
   * - backtestId ``(Required)``
     - string
     - Backtest Id of the backtest we want to delete.

Response
========

Returns a RestResponse object which indicates whether the request executed successfully.

.. code-block::

    {
      "success": true,
    }

|

----------------------------------------------------------------

List Backtests
--------------

Get details from all of the backtests for the specified project.

Path
====

``POST`` /backtests/read

Request
=======

.. code-block::

    {
      "projectId": 000000001
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id of the project from which to read a backtest.

Response
========

Returns a Backtest List object containing all of the requested Backtest objects.

.. code-block::

    {
      "backtests": [
        {
          "name": "My first backtest",
          "backtestId": "abc123",
          "completed": false,
          "progress": 0.5
        },
        {
          "name": "My second backtest",
          "backtestId": "def456",
          "completed": false,
          "progress": 0.5
        }
      ],
      "success": true
    }

|

----------------------------------------------------------------

Read a Backtest Report
----------------------

Read out the report from the specified backtest.

Path
====

``POST`` /backtests/read/report

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "backtestId": "abc123"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id of the project from which to read a backtest report.
   * - backtestId ``(Required)``
     - string
     - Compile-specific backtest Id of the backtest report to read.

Response
========

Returns a Backtest Report object.

.. code-block::

    {
      "report": "Backtest report data",
      "success": true
    }

|