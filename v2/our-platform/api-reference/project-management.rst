==================
Project Management
==================

The QuantConnect.com API exposes methods for creating, reading, listing, and deleting projects in the user's account.

|

----------------------------------------------------------------

The Project Response Object
---------------------------

Project management API calls return a Project Response object, which contains information about one or more projects.

Attributes
==========

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - Projects
     - Array of Project objects
     - List of projects for the authenticated user.
   * - Success
     - Boolean
     - Indicate if the API request was successful.
   * - Errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

The Project Object
------------------

A Project object contains information about a single project.

Attributes
==========

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - projectId
     - int
     - ID of the project.
   * - name
     - string
     - Name of the project.
   * - created
     - string
     - Date and time the project was created.
   * - modified
     - string
     - Date and time the project was last modified.
   * - language
     - string
     - Programming language of the project. Either "C#" or "Py".

|

----------------------------------------------------------------

Create a Project
----------------

Create a project with the specified name and language.

Path
====

``POST`` /projects/create

Request
=======

.. code-block::

    {
      "name": "My-First-Project",
      "language": "Py"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - Name ``(Required)``
     - string
     - A name for the project
   * - Language ``(Required)``
     - string
     - Programming language to use. Either "Py" or "C#".

Response
========

Returns a Project Response object, containing the newly created Project object.

.. code-block::

    {
      "projects": {
        "projectId": 987654321,
        "name": , "My-First-Project"
        "created": "09/30/2020",
        "modified": "10/01/2020",
        "language": "Py"
      },
      "success": true,
    }

|

----------------------------------------------------------------

Read a Project
--------------

Get details about a single project.

Path
====

``POST`` /projects/read

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
     - ID of the project to read.

Response
========

Returns a Project Response containing the requested Project object.

.. code-block::

    {
      "projects": [
        {
          "projectId": 000000001,
          "name": , "My-First-Project"
          "created": "09/29/2020",
          "modified": "09/30/2020",
          "language": "C#"
        }
      ],
      "success": true,
    }

|

----------------------------------------------------------------

Delete a Project
----------------

Delete the project with the specified project ID.

Path
====

``POST`` /projects/delete

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
     - ID of the project to delete.

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

Get details about all of a user's projects.

Path
====

``POST`` /projects/read

Request
=======

None.

Response
========

Returns a Project Response containing Project objects representing each of the user's projects.

.. code-block::

    {
      "projects": [
        {
          "projectId": 000000001,
          "name": "My-First-Project"
          "modified": "2020-09-30 10:30:00",
          "created": "2020-09-30 10:00:00",
          "language": "C#"
        },
        {
          "projectId": 000000002,
          "name": , "My-Second-Project"
           "modified": "2020-10-01 10:30:00",
          "created": "2020-10-01 10:00:00",
          "language": "Py"
        }
      ],
      "success": true,
    }

|