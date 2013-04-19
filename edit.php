<?php
/**
 * Add/edit studynotes
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('edit_form.php');
global $CFG, $DB;

$id         = optional_param('id', 0, PARAM_INT);       // studynotes id

require_login();

$pageparams = array('id'=>$id);
$personalcontext = context_user::instance($USER->id);


//prepare url
$url = new moodle_url('/local/studynotes/edit.php',$pageparams);
$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_context(context_system::instance());

// prepare editor options
$editoroptions = array(
    'maxfiles'   => EDITOR_UNLIMITED_FILES,
    'maxbytes'   => $CFG->maxbytes,
    'trusttext'  => false,
    'forcehttps' => false,
    'context'    => $personalcontext
);



//// create the form
$editform = new studynotes_edit_forms(NULL, array('userid'=>$USER->id, 'editoroptions' => $editoroptions));
if ($formdata = $editform->get_data()) {
    //print_object($formdata);die();
    $notes = new stdClass();

    $notes->modified = time();
    $notes->subject = $formdata->subject;
    $notes->message = $formdata->message_editor['text'];
    $notes->messageformat = $formdata->message_editor['format'];
    $notes->owner = $formdata->owner;

    if ($id == 0) {
        $notesid = $DB->insert_record('local_studynotes', $notes);
        redirect(new moodle_url('/local/studynotes/viewall.php'));
    }

}


$PAGE->set_title(get_string('notes:title:add', 'local_studynotes'));
$PAGE->set_heading(get_string('notes:header', 'local_studynotes'));

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

$editform->display();

echo $OUTPUT->footer();