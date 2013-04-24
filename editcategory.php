<?php
/**
 * Study notes category add/edit page
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('editcategory_form.php');
global $CFG, $DB;

$id         = optional_param('id', 0, PARAM_INT);       // categotry id

require_login();

$pageparams = array('id'=>$id);
$personalcontext = context_user::instance($USER->id);
require_capability('local/studynotes:category', $personalcontext);

//prepare url
$url = new moodle_url('/local/studynotes/editcategory.php',$pageparams);
$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('category:header', 'local_studynotes'));
$PAGE->navbar->add($PAGE->title);
$PAGE->navbar->add(get_string('menu:category', 'local_studynotes'), new moodle_url('/local/studynotes/category.php'));

// create a form
$editform = new studynotes_edit_category_forms('editcategory.php', array('userid'=>$USER->id));

if ($id > 0) {
    // check whether category id exist
    if($category = $DB->get_record('local_studynotes_category', array('id'=>$id))) {
        $category->txtname = $category->categoryname;
        $editform->set_data($category);
    }
}

if ($formdata = $editform->get_data()) {

    $category = new stdClass();
    $category->categoryname = $formdata->txtname;
    $category->createby = $USER->id;

    if(confirm_sesskey()) {
        if ($formdata->id > 0) {
            $category->id = $formdata->id;

             $categoryid = $DB->update_record('local_studynotes_category', $category);

            // log user action
            add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/editcategory.php', get_string('log:editcategory', 'local_studynotes', $category->id), '', $USER->id);
        } else {
            $categoryid = $DB->insert_record('local_studynotes_category', $category);

            // log user action
            add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/editcategory.php', get_string('log:addcategory', 'local_studynotes', $categoryid), '', $USER->id);
        }
        redirect(new moodle_url('/local/studynotes/category.php'));
    }
}



$PAGE->set_title(get_string('category:title:add', 'local_studynotes'));
$PAGE->set_heading($PAGE->title);

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

$editform->display();

echo $OUTPUT->footer();