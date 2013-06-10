# Aspire Moodle Integration

This software provides a "block" for Moodle that can be added to the course page in Moodle. The block will display available reading lists in Talis Aspire for that course.

## License

Copyright (c) Talis Education Limited, 2013
Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to:
  * change or modify the code code
  * redistribute the code without restriction
There is no warranty, either expressed or implied, covering the use or installation of this software.

## Pre-requisites

A running Moodle instance. This block has been tested against Moodle v2.4. It has not been tested against any other version.

Our test environment was the Bitnami Moodle Stack (http://bitnami.org/stack/moodle) running in a local virtual machine.

## How to install
The commands shown here are those used on a unix based system, they also contain specifics for our bitnami based moodle instance on which we were testing, but the principle of each step can be applied to other systems.

1. ssh or telnet to your Moodle server
    ssh bitnami@bitnamiserver.local
2. change directory to your Moodle PHP application's root htdocs directory:
    cd /opt/bitnami/apps/moodle/htdocs/
3. Download the file aspire-moodle-integration.tar to this directory:
    wget https://github.com/talis/aspire-moodle-integration/raw/master/2.4/aspire-moodle2.4-integration.tar
4. Extract the archive:
    tar -xvf aspire-moodle-integration.tar 
5. Log into Moodle as an administrator, and navigate to the Notifications screen
    http://yourserver/moodle/admin/index.php
6. You should see a message telling you that the Talis Aspire Resource Lists block needs your attention. Clicking 'Upgrade Moodle database now' will finish off the installation of this block.

## How to configure

The block needs to be configured to point to your Talis Aspire tenancy

7. Now setup the block:
  7. choose _Site Administration >> Plugins >> Blocks >> Talis Aspire Resource Lists_ 
  7. Configure the plugin for your local installation

## Making it live

8. Now add the block to all courses
  8. follow the instructions in this [knowledgebase article](http://support.talisaspire.com/entries/22420746-Making-a-Moodle-2-Block-appear-on-all-Courses)
9. Or you might want to only add to a selected course:
  9. From the Blocks drop down on the right hand column, choose "Resource Lists". 
  9. The resource lists block will be placed on the page. Optionally, you can now position the block using the icons under the block title to move it about.


