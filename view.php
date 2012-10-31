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

$id = required_param('id', PARAM_INT);    // Course Module ID

if (!$cm = get_coursemodule_from_id('studynotes', $id)) {
    print_error(get_string('error:cmid', 'bjoustudynotes'));
}
if (!$course = $DB->get_record('course', array('id'=> $cm->course))) {
    print_error(get_string('error:cmconfig', 'bjoustudynotes'));
}
if (!$certificate = $DB->get_record('studynotes', array('id'=> $cm->instance))) {
    print_error(get_string('error:cmid', 'bjoustudynotes'));
}

$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_course_login($course);

$PAGE->set_url('/mod/bjoustudynotes/view.php', array('id' => $cm->id));
$PAGE->set_context($context);
$PAGE->set_cm($cm);

$PAGE->set_title(get_string('title', 'bjoustudynotes'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo "this is view.php";
echo $OUTPUT->footer($course);