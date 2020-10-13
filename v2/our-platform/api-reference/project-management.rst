==================
Project Management
==================

The QuantConnect.com API exposes methods for creating, reading, listing, and deleting projects in the user's account.

|

----------------------------------------------------------------

The ProjectResponse Object
--------------------------

Project management API calls return a ProjectResponse object, which contains information about one or more projects.

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

Create a Project
----------------

Create a project with the specified name and language.

Path
====

``POST`` /projects/create

Parameters
==========

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

Returns a ProjectResponse object, containing the newly created Project object.

.. code-block::

    {
      "Projects": {
        "projectId": 987654321,
        "name": , "My-First-Project"
        "created": "09/30/2020",
        "modified": "10/01/2020",
        "language": "Py"
      },
      "Success": true,
    }

|

----------------------------------------------------------------

Read a Project
--------------

Get details about a single project.

Path
====

``POST`` /projects/read

Parameters
==========

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

Returns a ProjectResponse containing the requested Project object.

.. code-block::

    {
      "Projects": [
        {
          "projectId": 000000001,
          "name": , "My-First-Project"
          "created": "09/29/2020",
          "modified": "09/30/2020",
          "language": "C#"
        }
      ],
      "Success": true,
    }

|

----------------------------------------------------------------

Delete a Project
----------------

Delete the project with the specified project ID.

Path
====

``POST`` /projects/delete

Parameters
==========

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
      "Success": true,
    }

|

----------------------------------------------------------------

List Projects
-------------

Get details about all of a user's projects.

Path
====

``POST`` /projects/read

Parameters
==========

None.

Response
========

Returns a ProjectResponse containing Project objects representing each of the user's projects.

.. code-block::

    {
      "Projects": [
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
      "Success": true,
    }

|