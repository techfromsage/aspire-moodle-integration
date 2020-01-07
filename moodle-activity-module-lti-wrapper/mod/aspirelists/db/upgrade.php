<?php
function xmldb_aspirelists_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2014040701)
    {
        upgrade_mod_savepoint(true, 2014040701, 'aspirelists');
    }

    if ($oldversion < 2014041702) {

        // Define table aspirelists to be created.
        $table = new xmldb_table('aspirelists');

        // Adding fields to table aspirelists.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('lti', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('display', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('showexpanded', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');

        // Adding keys to table aspirelists.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table aspirelists.
        $table->add_index('course', XMLDB_INDEX_NOTUNIQUE, array('course'));

        // Conditionally launch create table for aspirelists.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Aspirelists savepoint reached.
        upgrade_mod_savepoint(true, 2014041702, 'aspirelists');
    }

    if ($oldversion < 2014041704) {

        // Define table aspirelists to be created.
        $table = new xmldb_table('aspirelists');

        // Adding fields to table aspirelists.

        $field = new xmldb_field('intro', XMLDB_TYPE_TEXT, null, null, null, null, null, 'course');

        // Conditionally launch add field display
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0', 'intro');

        // Conditionally launch add field display
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Aspirelists savepoint reached.
        upgrade_mod_savepoint(true, 2014041704, 'aspirelists');
    }

    if ($oldversion < 2014041705) {

        // Define table aspirelists to be created.
        $table = new xmldb_table('aspirelists');

        // Adding fields to table aspirelists.

        $field = new xmldb_field('name', XMLDB_TYPE_CHAR, 255, null, null, null, null, 'course');

        // Conditionally launch add field display
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Aspirelists savepoint reached.
        upgrade_mod_savepoint(true, 2014041705, 'aspirelists');
    }

    if ($oldversion < 2014042301) {

        // Define table aspirelists to be created.
        $table = new xmldb_table('aspirelists');

        // Remove/alter fields
        $field = new xmldb_field('lti', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Drop LTI field, since we don't use the LTI table anymore
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        $field = new xmldb_field('showexpanded', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        // This should default to collapsed
        if ($dbman->field_exists($table, $field)) {
            $field->setDefault('0');
            $dbman->change_field_default($table, $field);
        }

        // Aspirelists savepoint reached.
        upgrade_mod_savepoint(true, 2014042301, 'aspirelists');
    }

    if ($oldversion < 2015092900){
        upgrade_mod_savepoint(true, 2015092900, 'aspirelists');
    }

    if ($oldversion < 2015112400){
        upgrade_mod_savepoint(true, 2015112400, 'aspirelists');
    }

    if ($oldversion < 2016051100){
        upgrade_mod_savepoint(true, 2016051100, 'aspirelists');
    }

    if ($oldversion < 2016061400){
        upgrade_mod_savepoint(true, 2016061400, 'aspirelists');
    }

    if ($oldversion < 2017022000){
        upgrade_mod_savepoint(true, 2017022000, 'aspirelists');
    }

    if ($oldversion < 2017070400){
        upgrade_mod_savepoint(true, 2017070400, 'aspirelists');
    }

    if ($oldversion < 2018052910){
        upgrade_mod_savepoint(true, 2018052910, 'aspirelists');
    }

    if ($oldversion < 2019100310){
        upgrade_mod_savepoint(true, 2019100310, 'aspirelists');
    }

    if ($oldversion < 2019101500){
        upgrade_mod_savepoint(true, 2019101500, 'aspirelists');
    }

    if ($oldversion < 2020010700){
        upgrade_mod_savepoint(true, 2020010700, 'aspirelists');
    }
}