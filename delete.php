<?php
/**
 * Delete study notes
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $DB;

$id         = optional_param('id', 0, PARAM_INT);       // studynotes id
$arraydelete = optional_param('notes', '', PARAM_RAW); // array of notes to be delete
$confirm    = optional_param('confirm', '', PARAM_ALPHANUM);

$url = new moodle_url('/local/studynotes/delete.php');
$returnurl = new moodle_url('/local/studynotes/viewall.php');

require_login();

$pageparams = array('id'=>$id);
$personalcontext = context_user::instance($USER->id);
require_capability('local/studynotes:enable', $personalcontext);

$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');
$PAGE->set_title(get_string('notes:delete', 'local_studynotes'));
$PAGE->navbar->add(get_string('notes:header', 'local_studynotes'), new moodle_url('/local/studynotes/viewall.php'));
$PAGE->navbar->add($PAGE->title);
$PAGE->set_heading($PAGE->title);

if ($confirm == 'yes') {
    if (confirm_sesskey()) {
        $arraydelete = unserialize($arraydelete);
        foreach($arraydelete as $notesid) {
            // delete notes which is created by this user
            if ($result = $DB->delete_records('local_studynotes', array('id'=>$notesid, 'owner'=>$USER->id))) {
                // delete related share records
                $result = $DB->delete_records('local_studynotes_share', array('notesid'=>$notesid));
            } else {
                // delete note share to this user
                $result = $DB->delete_records('local_studynotes_share', array('userid'=>$USER->id));
            }

            // log user action
            add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/delete.php', get_string('log:deletenotes', 'local_studynotes', $notesid), '', $USER->id);
        }
        redirect($returnurl);
    } else {
        redirect($returnurl);
    }
}

$postdata = data_submitted();
$arraydata = get_object_vars($postdata);

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

// store selected notes, if any
$arraynotes = array();

foreach($arraydata as $key=>$selected) {
    if (preg_match('/notes/', $key)) {
        if ($notes = $DB->get_record('local_studynotes', array('id'=>substr($key, 5)))) {
            $arraynotes[] = $notes->id;
            echo get_string('notes:subject', 'local_studynotes').': '.$notes->subject.'<br>';
        }
    }
}

if (!empty($arraynotes)) {
    echo '<br>';
    $formcontinue = new single_button(new moodle_url('/local/studynotes/delete.php',
                        array('confirm'=>'yes','notes'=>serialize($arraynotes), 'sesskey'=>sesskey())), get_string('delete'), 'POST');
    $formcancel = new single_button(new moodle_url($CFG->wwwroot.'/local/studynotes/viewall.php'), get_string('cancel'));
    echo $OUTPUT->confirm(get_string('notes:delete:confirm', 'local_studynotes'),$formcontinue, $formcancel);
} else {
    echo $OUTPUT->heading(get_string('notes:delete:nonotes', 'local_studynotes'),2);
    echo $OUTPUT->single_button(new moodle_url($CFG->wwwroot.'/local/studynotes/viewall.php'), get_string('ok'));
}

echo $OUTPUT->footer();