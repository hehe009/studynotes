<?php
/**
 * BJOU studynotes library file
 * @package   bjoustudynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Given an object containing all the necessary data, (defined by the
 * form in mod.html) this function will create a new instance and
 * return the id number of the new instance.
 */
function bjoustudynotes_add_instance($bjoustudynotes) {
    global $DB;

    $bjoustudynotes->timecreated = time();
    $bjoustudynotes->timemodified = time();

    if ($bjoustudynotes->id = $DB->insert_record('bjoustudynotes', $bjoustudynotes)) {
        return $bjoustudynotes->id;
    }
}


/**
 * Given an object containing all the necessary data, (defined by the
 * form in mod.html) this function will update an existing instance
 * with new data.
 */
function bjoustudynotes_update_instance($bjoustudynotes) {
    global $DB;
    $bjoustudynotes->id = $bjoustudynotes->instance;
    $bjoustudynotes->timemodified = time();

    if ($bjoustudynotes = $DB->update_record('bjoustudynotes', $bjoustudynotes)) {
        return $bjoustudynotes;
    }
}

/**
 * Given an ID of an instance of this module, this function will
 * permanently delete the instance and any data that depends on it.
 */
function bjoustudynotes_delete_instance($id) {
    global $CFG, $DB;

    if (!$bjoustudynotes = $DB->get_record('bjoustudynotes', array('id'=>$id))) {
        return false;
    }

    $result = true;

    try {
        $transaction = $DB->start_delegated_transaction();
        if (!$DB->delete_records('bjoustudynotes', array('id'=>$facetoface->id))) {
            throw new Exception(get_string('error:deleteinstance', 'bjoustudynotes'));
        }
        $transaction->allow_commit();
    } catch (Exception $e) {
        $transaction->rollback($e);
    }

    return $result;
}