# Talis Reading List integrations with Moodle

[![Build Status](https://travis-ci.org/talis/aspire-moodle-integration.svg?branch=master)](https://travis-ci.org/talis/aspire-moodle-integration)

This repository contains two integrations between Moodle and Talis Aspire. A description of each follows to help you choose the right one for you.

There are individual README files for each plugin which contain installation and other useful instructions.

## Moodle 3.x mod_aspirelists

The activity module has been tested with all current versions of Moodle. [See the full matrix of versions tested](https://travis-ci.org/talis/aspire-moodle-integration).

This is an activity module which is essentially a wrapper for our [Talis Reading List LTI Tool](http://knowledge.talis.com/articles/tarl-lti/).

Users will be able to select and embed lists (or sections of lists) in their moodle courses. Lists and sections can be either displayed in-line or can be shown in embedded pages within Moodle.

This module is being actively maintained by Talis and the wider Talis Developer Community, and you are welcome to submit your own issues and pull requests!

### What version are you using?
To determine what version of this activity module you are using, 

* in Moodle
* go to your 'Plugin Overview' page `{moodlesite}/admin/plugins.php`
* search for **mod_aspirelists** and note the version in the version column.

## moodle-block-plugin block_aspirelists

This is a block plugin which allows a moodle block to be added to courses. It is very simplistic in how it does this.

### What version are you using?
To determine what version of this block you are using, 

* in Moodle
* go to your 'Plugin Overview' page `{moodlesite}/admin/plugins.php`
* search for **block_aspirelists** and note the version in the version column.

# Contributing changes and reporting issues

Without your feedback and input, these plugins would not be where they are today. If you have an idea for an improvement, or have made a change in a local copy of the plugin, then please do contribute.

## Guidelines for contributing

1. Raise an issue first — this means that a disucssion can be had about the change
1. Fork this repo
1. Make changes in a new branch.
1. Raise a Pull Request against this repo with those changes, and reference the issue from step 1
1. Once a review of the changes has been completed, we'll merge the changes into the master branch.
