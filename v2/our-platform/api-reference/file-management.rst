==================
File Management
==================

The QuantConnect.com API exposes methods for creating, reading, listing, and deleting files from projects.

|

----------------------------------------------------------------

Project Files Response Object
-----------------------------

File management API calls return a Project Files Response object, which contains information about one or more files from a project.

Attributes
==========

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - files
     - Array of Project File objects
     - List of project files.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Project File Object
-------------------

A Project File object contains information about a single file.

Attributes
==========

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - name
     - string
     - Name of a project file.
   * - content
     - string
     - Contents of the project file.
   * - modified
     - string
     - Date and time the file was last modified.

|

----------------------------------------------------------------

Create a File
-------------

Add a file to the specified project with the specified file name and contents.

Path
====

``POST`` /files/create

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "name": "My First File",
      "content": "print('Hello world!')"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Id of the project in which to create the file.
   * - name ``(Required)``
     - string
     - The name of the new file.
   * - content ``(Required)``
     - string
     - The content of the new file.

Response
========

Returns a Project Files Response object, containing the newly created Project File object.

.. code-block::

    {
      "files": [{
        "name": "My First File",
        "content": "print('Hello world!')",
        "modified": "2020-10-01 10:30:00"
      }],
      "success": true,
    }

|

----------------------------------------------------------------

Read a File
-----------

Get details about a single file.

Path
====

``POST`` /files/read

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "fileName": "My First File"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Id of the project containing the file to read.
   * - fileName ``(Required)``
     - string
     - Name of the file to read.

Response
========

Returns a Project Files Response containing the requested Project File object.

.. code-block::

    {
      "files": [{
        "name": "My First File",
        "content": "print('Hello world!')",
        "modified": "2020-10-01 10:30:00"
      }],
      "success": true,
    }

|

----------------------------------------------------------------

Update a File's Name
--------------------

Update the name of a file.

Path
====

``POST`` /files/update

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "oldFileName": "My File's Old Name",
      "newFileName": "My File's New Name"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Id of the project containing the file to update.
   * - oldFileName ``(Required)``
     - string
     - The current name of the file.
   * - newFileName ``(Required)``
     - string
     - The new name for the file.

Response
========

Returns a RestResponse object which indicates whether the request executed successfully.

.. code-block::

    {
      "success": true,
    }

|

----------------------------------------------------------------

Update a File's Contents
------------------------

Update the contents of a file.

Path
====

``POST`` /files/update

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "fileName": "My First File",
      "newFileContents": "print('New file contents!')"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Id of the project containing the file to update.
   * - fileName ``(Required)``
     - string
     - The name of the file to update.
   * - newFileContents ``(Required)``
     - string
     - The new contents of the file.

Response
========

Returns a RestResponse object which indicates whether the request executed successfully.

.. code-block::

    {
      "success": true,
    }

|

----------------------------------------------------------------

Delete a File
-------------

Delete the file with the specified project Id.

Path
====

``POST`` /files/delete

Request
=======

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "name": "My First File"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Id of the project containing the file to delete.
   * - name ``(Required)``
     - string
     - Name of the file to delete.

Response
========

Returns a RestResponse object which indicates whether the request executed successfully.

.. code-block::

    {
      "success": true,
    }

|

----------------------------------------------------------------

List Projects
-------------

Get details about all of the files within a specified project.

Path
====

``POST`` /files/read

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
     - Id of the project from which to list all files.

Response
========

Returns a Project Files Response containing Project File objects representing each file within the specified project.

.. code-block::

    {
      "files": [
        {
          "name": "My First File",
          "content": "print('Hello world #1!')",
          "modified": "2020-09-30 10:30:00"
        },
        {
          "name": "My Second File",
          "content": "print('Hello world #2!')",
          "modified": "2020-10-01 10:30:00"
      }
      ],
      "success": true,
    }

|
