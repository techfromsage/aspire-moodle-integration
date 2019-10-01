<?php

namespace block_aspirelists\privacy;

class provider implements
  // This plugin does not store any personal user data.
  \core_privacy\local\metadata\null_provider {

  /**
   * Return string that explains that no data is held by this plugin.
   *
   * @return  string
   */
  public static function get_reason() : string {
    return 'privacy:metadata';
  }
}