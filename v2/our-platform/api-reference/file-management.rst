==================
File Management
==================

The QuantConnect.com API exposes methods for creating, reading, listing, and deleting files from projects.

|

----------------------------------------------------------------

Project Files Response Object
-----------------------------

Project Files Response object contains information about one or more files from a project return in an array format.

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

Add a file to the specified project with the specified file name and contents. The API returns a copy of the file and confirmation it was successful.
The file directory location can be denoted by the slashes in the filename.

Path
====

``POST`` /files/create

Request
=======

.. code-block::

    {
      "projectId": 12345678,
      "name": "myNewFile.py",
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
        "name": "myNewFile.py",
        "content": "print('Hello world!')",
        "modified": "2020-10-01 10:30:00"
      }],
      "success": true,
    }

|

----------------------------------------------------------------

Read a Single File
-----------

Get details about a single file.

Path
====

``POST`` /files/read

Request
=======

.. code-block::

    {
      "projectId": 12345678,
      "fileName": "main.py"
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
        "name": "main.py",
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
      "projectId": 12345678,
      "oldFileName": "myOldFileName.py",
      "newFileName": "myNewFileName.py"
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

Update a File Contents
------------------------

Update the contents of a file.

Path
====

``POST`` /files/update

Request
=======

.. code-block::

    {
      "projectId": 12345678,
      "fileName": "myPrintFileName.py",
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
      "projectId": 12345678,
      "name": "fileToDelete.py"
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

List Files
-------------

Get details about all of the files within a specified project by not providing a specific file. 

Path
====

``POST`` /files/read

Request
=======

.. code-block::

    {
      "projectId": 12345678
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
          "name": "main.py",
          "content": "print('Hello world #1!')",
          "modified": "2020-09-30 10:30:00"
        },
        {
          "name": "myClassLibrary.py",
          "content": "print('Hello world #2!')",
          "modified": "2020-10-01 10:30:00"
      }
      ],
      "success": true,
    }

|
