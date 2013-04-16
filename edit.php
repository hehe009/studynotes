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

$id         = optional_param('id', 0, PARAM_INT);       // studynotes id

require_login();

$pageparams = array('id'=>$id);

//prepare url
$url = new moodle_url('/local/studynotes/edit.php',$pageparams);
$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_context(context_system::instance());

//// create the form
$editform = new studynotes_edit_forms(NULL, array('userid'=>$USER->id));
if ($data = $editform->get_data()) {

    $today = time();
    $today = make_timestamp(date('Y', $today), date('m', $today), date('d', $today), 0, 0, 0);
    $data->modified = $today;
    print_object($data);
    die();
}


$PAGE->set_title(get_string('notes:title:add', 'local_studynotes'));
$PAGE->set_heading(get_string('notes:header', 'local_studynotes'));

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

$editform->display();

echo $OUTPUT->footer();