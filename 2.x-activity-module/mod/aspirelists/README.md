Talis Aspire Reading Lists Moodle Integration
=============================================

Compatibility
-------------

This activity plugin has been tested with stable versions of Moodle 2.4, 2.6, 2.7, 2.8
If you do become aware of any issues affecting the plugin, then feel free to raise them with Talis directly, or by adding issues to the github repository.  Feel free to submit Pull Requests too!


Installation
------------

Copy the 2.x-activity-module/mod/aspirelists directory to your {MoodleRoot}/mod directory.

Log in to Moodle as an administrator and you should be prompted to upgrade mod_aspirelists.

If you are already logged in as an administrator - go to the 'notifications' page.

"mod_lti" must also be enabled for this module to work.

That's it!

Configuration
-------------

## create access keys in Talis Aspire Reading Lists

First off, you will need to contact Talis Support to ask them to enable the **access-keys** permission in *Talis Aspire Reading Lists*.

Once that is set, from the Talis Aspire Reading List navigation menu, select *Admin* -> *Integrations*.  On the "Integrations" page, click on *Add new access key*.

* A form will appear, choose a distinct, descriptive name and click *Add*.
* Make note of the **API key** and **Shared secret** that are generated.

## Configure moodle to use the Talis Aspire Reading Lists LTI tool

In Moodle, go to the *Site administration* menu and choose *Plugins* -> *Activity modules* -> *LTI*

You should be taken to the **LTI** / **External Tool Types** page.  From here, click *Add external tool configuration*.

At the next form, fill out the following:

<dl>
    <dt>Tool Name</dt>
    <dd>Whatever you call Talis Aspire Reading Lists at your institution.</dd>
    <dt>Tool Base URL</dt>
    <dd>http://{{baseUrlOfTalisAspireReadingLists}}/lti/launch</dd>
    <dt>Consumer Key</dt>
    <dd>This should be <em>API key</em> from above</dd>
    <dt>Shared Secret</dt>
    <dd>This should be <em>Shared secret</em> from above</dd>
    <dt>Custom Parameters</dt>
    <dd>You will almost certainly need to add some custom parameters here - read <a href="http://knowledge.talis.com/articles/tarl-lti/#parameters" title="link to TARL LTI article">this article for more information</a></dd>
    <dt>Show tool type when creating tool instances</dt>
    <dd>Check this if you want TARL to appear as an option when an instructor adds an LTI resource as an External Tool</dd>
    <dt>Default Launch Container</dt>
    <dd>Choose either <em>Embed</em> or <em>Embed, without blocks</em>, depending on your preference</dd>
</dl>

Everything else can be ignored for now.  Click *Save changes*.  Your external tool should now appear in the *Active* list.

Now go to the *Site administration* menu and choose *Plugins* -> *Activity modules* -> *Course Resource List*.

You should be taken to the **Course Resource List** settings page.  Fill out the form as follows:

<dl>
    <dt>Target Aspire URL</dt>
    <dd>This should be <em>http://{baseUrlOfTalisAspireReadingLists}</em> with no trailing slash.  It should be the same as you entered in <strong>Tool Base URL</strong> above, but leave off <em>/lti/launch</em></dd>
    <dt>Module code field</dt>
    <dd>The course database field in Moodle that contains the module/course code that lists are associated with in TARL</dd>
    <dt>Module Code Regex</dt>
    <dd>Often module/course codes need to be rewritten slightly to match what is in TARL, enter a regex that matches the module code here</dd>
    <dt>Time Period Regex</dt>
    <dd>If the module code in Moodle contains the time period for the course, enter a regex that identifies it in the string</dd>
    <dt>Time Period Mapping</dt>
    <dd>A JSON string that maps the strings extracted from the above regex to the time period slugs in TARL</dd>
    <dt>Default height of an embedded list</dt>
    <dd>If a list is displayed inline within a course, this value will control the default height of the iframe</dd>
</dl>

Click *Save changes*.  **Course Resource List** should now be available as **Resource** in the *Add an activity or resource*
menu when editing a course.



