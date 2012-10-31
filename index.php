<?php
/**
 * BJOU studynotes index
 * @package   bjoustudynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$id = required_param('id', PARAM_INT);           // Course ID

// Ensure that the course specified is valid
if (!$course = $DB->get_record('course', array('id'=> $id))) {
    print_error(get_string('error:courseid', 'bjoustudynotes'));
}

require_course_login($course);
$context = get_context_instance(CONTEXT_COURSE, $course->id);

$PAGE->set_url('/mod/bjoustudynotes/index.php', array('id' => $id));
$PAGE->set_title(get_string('title', 'bjoustudynotes'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo "this is index.php";
echo $OUTPUT->footer($course);