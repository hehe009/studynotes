<?php
/**
 * BJOU studynotes
 * @package   bjoustudynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once('note_form.php');

$id = required_param('id', PARAM_INT);    // Course Module ID

if (!$cm = get_coursemodule_from_id('bjoustudynotes', $id)) {
    print_error(get_string('error:cmid', 'bjoustudynotes'));
}
if (!$course = $DB->get_record('course', array('id'=> $cm->course))) {
    print_error(get_string('error:cmconfig', 'bjoustudynotes'));
}
if (!$certificate = $DB->get_record('bjoustudynotes', array('id'=> $cm->instance))) {
    print_error(get_string('error:cmid', 'bjoustudynotes'));
}

$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_course_login($course);

$PAGE->set_url('/mod/bjoustudynotes/view.php', array('id' => $cm->id));
$PAGE->set_pagelayout('course');
$PAGE->set_context($context);
$PAGE->set_cm($cm);

$PAGE->set_title(get_string('title', 'bjoustudynotes'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo "<link rel='stylesheet' type='text/css' href='http://localhost/moodle/mod/bjoustudynotes/css/notes.css'>";

/// create form
$noteform = new mod_bjoustudynotes_note_edit_form();
$noteform->display();

echo $OUTPUT->footer($course);