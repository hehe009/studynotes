<?php
/**
 * Linkup study notes and category
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $DB;

$url = new moodle_url('/local/studynotes/linkup.php');
$returnurl = new moodle_url('/local/studynotes/viewall.php');

require_login();

$personalcontext = context_user::instance($USER->id);
require_capability('local/studynotes:enable', $personalcontext);

$postdata = data_submitted();
$categoryid = $postdata->categoryid;
$arraydata = get_object_vars($postdata);


// delete existing notes - category relation
$arraynotes = array();
foreach($arraydata as $key=>$selected) {
    if (preg_match('/notes/', $key)) {

        // check notes-category relation already exist
        $sql = "SELECT snr.notesid, snc.categoryname, snc.createby, snr.id as relationid, snc.id as fromid
                FROM {local_studynotes_category} snc, {local_studynotes_relation} snr
                WHERE snc.id = snr.categoryid
                AND snc.createby = :userid
                AND snr.notesid = :notesid";
        $params['userid'] = $USER->id;
        $params['notesid'] = substr($key, 5);
        if ($result = $DB->get_record_sql($sql, $params)) {

            // record found, check whether original relation match with requested categoryid
            if ($result->fromid != $categoryid) {

                // not the same, update record
                $updaterelation = new stdClass();
                $updaterelation->id = $result->relationid;
                $updaterelation->notesid = $params['notesid'];
                $updaterelation->categoryid = $categoryid;
                // add addition param for logging
                $updaterelation->fromid = $result->fromid;
                $updaterelation->toid = $categoryid;

                // if user select category 'uncategory'
                if ($categoryid == 0) {
                    // delete record
                    $DB->delete_records('local_studynotes_relation', array('id'=>$updaterelation->id));
                } else {

                    // update database
                    $DB->update_record('local_studynotes_relation', $updaterelation);
                }

                // log user action
                add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/linkup.php', get_string('log:updaterelation', 'local_studynotes', $updaterelation), '', $USER->id);

            } // end if original relation match with requested categoryid
        } else if ($categoryid != 0) {

            // don't create any record for category 'uncategory'

            // no existing record, insert a new record
            $newrelation = new stdClass();
            $newrelation->notesid = $params['notesid'];
            $newrelation->categoryid = $categoryid;

            // insert record
            $DB->insert_record('local_studynotes_relation', $newrelation);

            // log user action
            add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/linkup.php', get_string('log:newrelation', 'local_studynotes', $newrelation), '', $USER->id);

        }
    }
} // end foreach notes array

redirect($returnurl);