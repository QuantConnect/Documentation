==================
Compiling Projects
==================

The QuantConnect.com API exposes methods for creating compile jobs for the user's projects, and reading the results of those compile jobs.

|

----------------------------------------------------------------

Compile Object
--------------

Project compile API calls return a Compile object, which contains information about a compilation request.

Attributes
==========

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - compileId
     - string
     - Compile ID for a successful build.
   * - state
     - string
     - State of the compilation request. Either "InQueue", "BuildSuccess", or "BuildError".
   * - logs
     - Array of strings
     - Logs of the compilation request.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Create a Compile Request
------------------------

Create a new compile job request for the specified project.

Path
====

``POST`` /compile/create

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
     - Id of the project to be compiled.

Response
========

Returns a Compile object, containing information about the compile job.

.. code-block::

    {
      "compileId": 12345,
      "state": "BuildSuccess",
      "logs": ["Initiating compilation", "Compilation complete."],
      "success": true
    }

|

----------------------------------------------------------------

Read a Compile Request
----------------------

Read a compile job result.

Path
====

``POST`` /compile/read

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "compileId": 12345
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

Response
========

Returns a Compile object, containing information about the compile job.

.. code-block::

    {
      "compileId": 12345,
      "state": "BuildSuccess",
      "logs": ["Initiating compilation", "Compilation complete."],
      "success": true
    }

|