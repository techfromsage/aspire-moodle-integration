<?php

namespace mod_aspirelists\privacy;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\context;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;

defined('MOODLE_INTERNAL') || die();

/**
 * This plugin does not store any personal user data in the database, but does cause LTI connections to be made.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * Get the language string to show as a reason.
     * @return string
     */
    public static function get_reason() : string {
        return 'privacy:metadata:reason';
    }

    /**
     * Get a list of the metadata used by this plugin
     *
     * @param collection $collection
     *
     * @return collection
     */
    public static function get_metadata(collection $collection) : collection {

        $collection->add_external_location_link('lti_client', [
            'userid' => 'privacy:metadata:lti_client:userid',
            'role' => 'privacy:metadata:lti_client:role',
            'courseid' => 'privacy:metadata:lti_client:courseid',
            'courseidnumber' => 'privacy:metadata:lti_client:courseidnumber',
            'courseshortname' => 'privacy:metadata:lti_client:courseshortname',
            'coursefullname' => 'privacy:metadata:lti_client:coursefullname',
        ], 'privacy:metadata:lti_client');

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     *
     * @return  contextlist   $contextlist  The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid): contextlist
    {
        // This plugin does not directly handle user data in moodle
        return new contextlist();
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist)
    {
        // This plugin does not directly handle user data in moodle
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context)
    {
        // This plugin does not directly handle user data in moodle
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist)
    {
        // This plugin does not directly handle user data in moodle
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist)
    {
        // This plugin does not directly handle user data in moodle
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist)
    {
        // This plugin does not directly handle user data in moodle
    }
}