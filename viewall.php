<?php
/**
 * Display a list of study notes
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir.'/formslib.php');
require_login();

$url = new moodle_url('/local/studynotes/viewall.php');

$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('notes:list', 'local_studynotes'));
$PAGE->set_heading(get_string('notes:list', 'local_studynotes'));

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

echo $OUTPUT->single_button(new moodle_url('edit.php'), get_string('button:addnotes', 'local_studynotes'), 'get');

echo $OUTPUT->footer();