.. _organizations-team-management:

===============
Team Management
===============

|

Introduction
============

The members of your organization are able to collaborate on projects and share resources with each other. You can manage the members of your team from the Members page in the organization tab. The Members tab also contains information like the total number of members and the number of active members, which are members who have been online in the last hour.

The management system is centered around user-based permissions, which allow certain members to manage resources. The manager of the organization has access to all permissions and can designate specific permissions to other members.

|

Adding and Removing Members
===========================

You can add a new member by selecting the *Add Member* button. You can add an additional member using either their name or the email address associated with their account. Adding a new member will use a Seat of your organization, so your organization needs to have vacant seats. Note that the owner occupies one seat.

.. figure:: https://cdn.quantconnect.com/i/tu/organization-team-1.png

    Team Management Page

|

Permission Management
=====================

Each member of your organization will have access to the resources available. However, you can also grant various permissions to your members. The organization manager has full permission for managing resources, managing members and designating permissions to other members. There a few different categories of permissions you can allow members.

.. figure:: https://cdn.quantconnect.com/i/tu/organization-team-2.png

    Permission Management

**Node Permissions**

.. list-table::
   :header-rows: 1

   * - Permission
     - Description
   * - Create
     - Permission to create new nodes, specific to each node type
   * - Stop
     - Permission to stop running nodes, specific to each node type
   * - Delete
     - Permission to delete existing nodes, specific to each node type

**Team Permissions**

.. list-table::
   :header-rows: 1

   * - Permission
     - Description
   * - Add
     - Permission to add new members
   * - Remove
     - Permission to remove existing members
   * - Edit
     - Permission to change the permissions of other members

**Storage Permissions**

.. list-table::
   :header-rows: 1

   * - Permission
     - Description
   * - Create
     - Permission to write to the Object Store
   * - Delete
     - Permission to delete data in the Object Store
   * - Billing
     - Permission to subscibe to more space in the Object Store

**Alpha Permissions**

Token Permissions

.. list-table::
   :header-rows: 1

   * - Permission
     - Description
   * - Create
     - Permission to create a new token
   * - Delete
     - Permission to delete an existing token
   * - Read
     - Permission to read tokens

Exchange Permissions

.. list-table::
   :header-rows: 1

   * - Permission
     - Description
   * - Read
     - Permission to read from the Alpha Streams exchange


