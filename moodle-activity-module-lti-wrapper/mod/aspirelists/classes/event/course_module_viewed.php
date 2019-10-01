<?php
namespace aspirelists\event;
defined('MOODLE_INTERNAL' || die);

/**
 * Class course_module_viewed
 * This provides an extension to the core course_module_viewed event to easily set legacy log data.
 * @package aspirelists\event
 */
class course_module_viewed extends \core\event\course_module_viewed {
    /** @var  array */
    var $legacy_log_data;

    protected function init() {
        $this->data['objecttable'] = 'aspirelists';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['crud'] = 'r';
        parent::init();
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