Talis Aspire Reading Lists Moodle Integration
=============================================

Compatibility
-------------

## Moodle 3.11 to 4.x

You are recommended to use Talis' LTI 1.3 intergration with Moodle 3.11 upwards. [Documentation is available on our support knowledge base](https://support.talis.com/hc/en-us/articles/5519648821149-Talis-Aspire-LTI-1-3-Moodle-Set-Up-Instructions)

This plugin ONLY works with LTI 1.1 and is not going to be updated to use LTI 1.3

## Moodle 3.x to 3.11

The activity module has been tested with all current versions of Moodle. [See the full matrix of versions tested](https://travis-ci.org/talis/aspire-moodle-integration).
If you do become aware of any issues affecting the plugin, then feel free to raise them with Talis directly, or by adding issues to the github repository here.  

Feel free to submit Pull Requests too!


Installation
------------

Copy the moodle-activity-module-lti-wrapper/mod/aspirelists directory to your {MoodleRoot}/mod directory.

Log in to Moodle as an administrator and you should be prompted to upgrade mod_aspirelists.

If you are already logged in as an administrator - go to the 'notifications' page.

"mod_lti" must also be enabled for this module to work.

Configuration
-------------

## create access keys in Talis Aspire Reading Lists

First off, you will need to contact Talis Support to ask them to enable the **access-keys** permission in *Talis Aspire Reading Lists*.

Once that is set, from the Talis Aspire Reading List navigation menu, select *Admin* -> *Integrations*.  On the "Integrations" page, click on *Add new access key*.

* A form will appear, choose a distinct, descriptive name and click *Add*.
* Make note of the **API key** and **Shared secret** that are generated.
* Do not share key's and secrets between different instances of moodle.

## Configure moodle to use the Talis Aspire Reading Lists LTI tool

In Moodle, go to the *Site administration* menu and choose *Plugins* -> *Activity modules* -> *LTI*

You should be taken to the **LTI** / **External Tool Types** page.  From here, click *Add external tool configuration*.

At the next form, fill out the following:

<dl>
    <dt>Tool Name</dt>
    <dd>Whatever you call Talis Aspire Reading Lists at your institution.</dd>
    <dt>Tool Base URL</dt>
    <dd>https://{{tenancyShortName}}.rl.talis.com/lti/launch</dd>
    <dt>Consumer Key</dt>
    <dd>This should be <em>API key</em> from above</dd>
    <dt>Shared Secret</dt>
    <dd>This should be <em>Shared secret</em> from above</dd>
    <dt>Custom Parameters</dt>
    <dd>You will almost certainly need to add some custom parameters here - read <a href="http://knowledge.talis.com/articles/tarl-lti/#parameters" title="link to TARL LTI article">this article for more information</a></dd>
    <dt>Show tool type when creating tool instances</dt>
    <dd>Check this if you want TARL to appear as an option when an instructor adds an LTI resource as an External Tool (note that you may not need to do this as the ativity module will add a 'resource' which can be chosen instead of using the built-in 'External Tool' activity type)</dd>
    <dt>Default Launch Container</dt>
    <dd>Choose either <em>Embed</em> or <em>Embed, without blocks</em>, depending on your preference</dd>
</dl>

Everything else can be ignored for now.  Click *Save changes*.  Your external tool should now appear in the *Active* list.

Now go to the *Site administration* menu and choose *Plugins* -> *Activity modules* -> *Course Resource List*.

You should be taken to the **Course Resource List** settings page.  Fill out the form as follows:

<dl>
    <dt>Target Aspire URL</dt>
    <dd>This should be <em>https://{tenancyShortName}.rl.talis.com</em> with no trailing slash.  It should be the same as you entered in <strong>Tool Base URL</strong> above, but leave off <code>/lti/launch</code></dd>
    <dt>Module code field</dt>
    <dd>The course database field in Moodle that contains the module/course code that lists are associated with in TARL</dd>
    <dt>Module Code Regex</dt>
    <dd>Often module/course codes need to be rewritten slightly to match what is in TARL, enter a regex that will pick out the module code in the first matching group</dd>
    <dt>Time Period Regex</dt>
    <dd>If the module code in Moodle contains the time period for the course, enter a regex that identifies it in the first matching group. The time period code here will need to match the 'slug' of a time period in Talis Aspire Reading Lists</dd>
    <dt>Time Period Mapping</dt>
    <dd>A JSON string that maps the strings extracted from the above regex to the time period slugs in TARL - you only need to do use this if you have multiple codes in moodle that need to map to the same time periods in Talis Aspire</dd>
    <dt>Default height of an embedded list</dt>
    <dd>If a list is displayed inline within a course, this value will control the default height of the iframe</dd>
</dl>

Click *Save changes*.  **Course Resource List** should now be available as **Resource** in the *Add an activity or resource*
menu when editing a course.

If you need help with any of the settings here, please contact [support@talis.com](mailto:support@talis.com) with screen shots of the above settings pages.

## Performance tips

When using large numbers (greater than 5) inline lti resources on a single course page it is recommended that they do not default to expanded.
This can lead to high volumes of simultaneous LTI requests, slowing down responses and, on rare occasions, the possibility that some requests
may not return if too many courses are being loaded by multiple users.
