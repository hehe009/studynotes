<?php
/**
 * View single notes
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $CFG, $DB;

$id         =  required_param('id', PARAM_INT);       // studynotes id

require_login();

$personalcontext = context_user::instance($USER->id, MUST_EXIST);
require_capability('local/studynotes:enable', $personalcontext);

//prepare url
$PAGE->set_url('/local/studynotes/notes.php', array('id'=>$id));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');
$PAGE->set_title(get_string('notes:header', 'local_studynotes'));
$PAGE->set_heading($PAGE->title);
$PAGE->navbar->add($PAGE->title);
$PAGE->navbar->add(get_string('menu:notes', 'local_studynotes'), new moodle_url('/local/studynotes/viewall.php'));


echo $OUTPUT->header();


// retrieve notes
if ($notes = $DB->get_record('local_studynotes', array('id'=>$id))) {

    // verify user has the right to view the notes
    $valid = false;
    if ($notes->owner == $USER->id) {
        $valid = true;
    } else if ($DB->get_record('local_studynotes_share', array('userid'=>$USER->id))) {
        $valid = true;
    }



    if ($valid) {
        echo $OUTPUT->heading($notes->subject, 2);
        echo $notes->message;
    } else {
        echo $OUTPUT->heading($PAGE->title);
        echo $OUTPUT->notification(get_string('error:nopermission', 'local_studynotes'));
    }
}
echo $OUTPUT->heading($PAGE->title);
echo $OUTPUT->notification(get_string('error:notexists', 'local_studynotes'));
echo $OUTPUT->footer();