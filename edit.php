<?php
/**
 * Edit studynotes
 * @package   bjoustudynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('note_form.php');
require_once($CFG->dirroot.'/notes/lib.php');

$id = required_param('id', PARAM_INT);    // Course ID
$sitecontext  = get_context_instance(CONTEXT_COURSE, $id);

if (!$course = $DB->get_record('course', array('id'=> $id))) {
    print_error(get_string('error:courseid', 'block_bjoustudynotes'));
}

require_login($course);
if (!has_capability('block/bjoustudynotes:addnotes', $sitecontext)) {
    print_error('nopermissions', 'error', '', 'batch enroll student');
}

$context = get_context_instance(CONTEXT_COURSE, $id);

$url = new moodle_url('/blocks/bjoustudynotes/edit.php');
$url->param('id', $id);

$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_context($context);

$PAGE->set_title(get_string('title', 'block_bjoustudynotes'));
$PAGE->set_heading($course->fullname);

/// create note form
$noteform = new bjoustudynotes_note_edit_form();

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

// set defaults
$note->id = $course->id;
$noteform->set_data($note);

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

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);
echo "<link rel='stylesheet' type='text/css' href='http://localhost/moodle/blocks/bjoustudynotes/css/notes.css'>";

$noteform->display();

echo $OUTPUT->footer($course);