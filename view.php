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
require_once('lib.php');
require_once($CFG->dirroot.'/notes/lib.php');

$id = required_param('id', PARAM_INT);    // Course Module ID

$url = new moodle_url('/mod/bjoustudynotes/view.php');
$url->param('id', $id);

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

$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_context($context);
$PAGE->set_cm($cm);

$PAGE->set_title(get_string('title', 'bjoustudynotes'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo "<link rel='stylesheet' type='text/css' href='http://localhost/moodle/mod/bjoustudynotes/css/notes.css'>";

/// create note form
$noteform = new mod_bjoustudynotes_note_edit_form();

// get user's notes (if any)
if ($note = $DB->get_record('post', array('userid'=> $USER->id, 'courseid' => $course->id,
                        'publishstate' => 'draft'))) {
    /// set note id
    $note->noteid = $note->id;

} else {
    // add new note
    $note = new stdClass();
    $note->courseid     = $course->id;
    $note->userid       = $USER->id;;

    $url->param('courseid', $course->id);
    $url->param('userid', $USER->id);
}

// hack pass cm id to form
$note->id = $cm->id;

/// set defaults
$noteform->set_data($note);

$PAGE->set_url($url);

/// if form was cancelled then return to the notes list of the note
if ($noteform->is_cancelled()) {
    redirect(new moodle_url($CFG->wwwroot.'/course/view.php', array('id'=>$course->id)));

}

/// if data was submitted and validated, then save it to database
if ($note = $noteform->get_data()){
    $note->id = $note->noteid;
    if (note_save($note)) {
        add_to_log($note->courseid, 'notes', 'update', 'index.php?course='.$note->courseid.'&amp;user='.$note->userid . '#note-' . $note->id, 'update note');
    }
    // redirect to notes list that contains this note
    redirect(new moodle_url($CFG->wwwroot.'/course/view.php', array('id'=>$course->id)));
}

$noteform->display();

echo $OUTPUT->footer($course);