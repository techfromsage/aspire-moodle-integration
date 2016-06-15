<?php
namespace aspirelists\event;

defined('MOODLE_INTERNAL' || die);
/**
 * Class aspire_lists_launch
 * Provides a hook into the Moodle Event system for logging events.
 * This class implements the key functions needed to provide compatibility with https://docs.moodle.org/dev/Event_2
 * This class represents the event of a user launching an aspire list LTI link.
 * @package aspirelists\event
 */
class aspire_lists_launch extends \core\event\base {
    /** @var  array */
    var $legacy_log_data;

    protected function init() {
        $this->data['objecttable'] = 'aspirelists';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['crud'] = 'r';
    }

    public static function get_name() {
        return get_string('eventAspireLaunch', 'aspirelists');
    }

    public function get_description() {
        return "The user with id {$this->userid} Launched an LTI link with id {$this->objectid}.";
    }

    public function get_legacy_logdata() {
        return $this->legacy_log_data;
    }

    /**
     * easily set our old add_to_log data
     * @param array $data
     */
    public function set_legacy_logdata(array $data) {
        $this->legacy_log_data = $data;
    }
}