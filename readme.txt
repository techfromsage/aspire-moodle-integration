Aspire Moodle Integration
=========================

This software provides a "block" (for Moodle 1.9.x) that can be added to the course page in Moodle. The block will display available reading lists in Talis Aspire for that course.

License:
========

Copyright (c) Talis Education Limited, 2010
Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to:
  * change or modify the code code
  * redistribute the code without restriction
There is no warranty, either expressed or implied, covering the use or installation of this software.

Pre-requisites
==============

A running Moodle instance. This block has been tested against Moodle v1.9.8-0. It has not been tested against any other version.

Our test environment was the Bitnami Moodle Stack (http://bitnami.org/stack/moodle) running on Amazon EC2.

How to install:
===============

1. ssh or telnet to your Moodle server
2. change directory to your Moodle PHP application's root htdocs directory (on our server, this was /opt/bitnami/apps/moodle/htdocs/)
3. Download the file http://aspire-moodle-integration.googlecode.com/files/aspire-moodle-integration.tar to this directory. On our installation (Linux), we did: 
     sudo wget http://aspire-moodle-integration.googlecode.com/files/aspire-moodle-integration.tar
4. Extract the archive. On our installation (Linux), we did:
     sudo tar -xvf aspire-moodle-integration.tar 
5. Log into Moodle as an administrator, and navigate to the Notifications screen (http://yourserver/moodle/admin/index.php). You should see a message telling you that the Aspire Lists block was successfully installed

How to configure:
=================

1. From the admin area, choose "Modules", then "Blocks" and finally "Resource Lists"
2. Type in the base url of your Aspire installation, e.g. "http://lists.broadminsteruniversity.org". Note this value must have no trailing slashes or page names - "http://lists.broadminsteruniversity.org/index.html" would be incorrect
3. Choose your target knowledge grouping from Courses, Modules, Units or Programmes. The target knowledge grouping should be the lowest level knowledge grouping in your hierarchy, to which lists are normally attached.
4. Click "Save Changes"

