<?php

namespace block_aspirelists\task;

class expire_list_cache extends \core\task\scheduled_task {

  public function get_name() {
    return get_string('expirelisttask', 'block_aspirelists');
  }

  public function execute() {
    // Somewhat crudely let's purge all keys from this cache
    $cache = \cache::make('block_aspirelists', 'aspirelists');
    $cache->purge();
  }
}