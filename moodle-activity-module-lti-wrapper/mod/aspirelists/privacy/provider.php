<?php

namespace mod_aspirelists\privacy;
use core_privacy\local\metadata\collection;

// This plugin does not store any personal user data in the database, but does cause LTI connections to be made.
class provider implements \core_privacy\local\metadata\provider {

    /**
     * Get the language string to show as a reason.
     * @return string
     */
    public static function get_reason() : string {
        return 'privacy:metadata:reason';
    }

    public static function get_metadata(collection $collection) : collection {

        $collection->add_external_location_link('lti_client', [
            'userid' => 'privacy:metadata:lti_client:userid',
            'role' => 'privacy:metadata:lti_client:role',
            'courseid' => 'privacy:metadata:courseid',
            'courseidnumber' => 'privacy:metadata:courseidnumber',
            'courseshortname' => 'privacy:metadata:courseshortname',
            'coursefullname' => 'privacy:metadata:coursefullname',
        ], 'privacy:metadata:lti_client');

        return $collection;
    }
}