<?php
/**
 * Delete study notes category
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $DB;

$id         = optional_param('id', 0, PARAM_INT);       // study notes category id
$arraydelete = optional_param('notes', '', PARAM_RAW); // array of notes category to be delete
$confirm    = optional_param('confirm', '', PARAM_ALPHANUM);

$url = new moodle_url('/local/studynotes/deletecategory.php');
$returnurl = new moodle_url('/local/studynotes/category.php');

require_login();

$pageparams = array('id'=>$id);
$personalcontext = context_user::instance($USER->id);
require_capability('local/studynotes:category', $personalcontext);

$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');
$PAGE->set_title(get_string('category:delete', 'local_studynotes'));
$PAGE->navbar->add(get_string('category:header', 'local_studynotes'), new moodle_url('/local/studynotes/category.php'));
$PAGE->navbar->add($PAGE->title);
$PAGE->set_heading($PAGE->title);

if ($confirm == 'yes') {
    if (confirm_sesskey()) {
        $arraydelete = unserialize($arraydelete);
        foreach($arraydelete as $categoriesid) {
            // delete notes which is created by this user
            if ($result = $DB->delete_records('local_studynotes_category', array('id'=>$categoriesid, 'createby'=>$USER->id))) {
                // delete related share records
                $result = $DB->delete_records('local_studynotes_relation', array('categoryid'=>$categoriesid));
            }

            // log user action
            add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/deletecategory.php', get_string('log:delcategory', 'local_studynotes', $categoriesid), '', $USER->id);
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

// store selected categories, if any
$arraycategories = array();


foreach($arraydata as $key=>$selected) {
    if (preg_match('/notescategory/', $key)) {
        if ($category = $DB->get_record('local_studynotes_category', array('id'=>substr($key, 13)))) {
            $arraycategories[] = $category->id;
            echo get_string('category:delete:categoryname', 'local_studynotes').' '.$category->categoryname.'<br>';
        }
    }
}

if (!empty($arraycategories)) {
    echo '<br>';
    $formcontinue = new single_button(new moodle_url('/local/studynotes/deletecategory.php',
                        array('confirm'=>'yes','notes'=>serialize($arraycategories), 'sesskey'=>sesskey())), get_string('delete'), 'POST');
    $formcancel = new single_button(new moodle_url($CFG->wwwroot.'/local/studynotes/category.php'), get_string('cancel'));
    echo $OUTPUT->confirm(get_string('category:delete:confirm', 'local_studynotes'),$formcontinue, $formcancel);
} else {
    echo $OUTPUT->heading(get_string('category:delete:nonotes', 'local_studynotes'),2);
    echo $OUTPUT->single_button(new moodle_url($CFG->wwwroot.'/local/studynotes/category.php'), get_string('ok'));
}

echo $OUTPUT->footer();