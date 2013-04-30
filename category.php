<?php
/**
 * List all notes categories
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

global $DB;

require_login();
$personalcontext = context_user::instance($USER->id, MUST_EXIST);
require_capability('local/studynotes:category', $personalcontext);

$url = new moodle_url('/local/studynotes/category.php');

$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');
$PAGE->set_title(get_string('category:list', 'local_studynotes'));
$PAGE->set_heading($PAGE->title);

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

// form for handle button in this page
echo '<form action="action_redir.php" method="post" id="studynotesform">';

// prepare control table for add and manage button
$controlstable = new html_table();
$controlstable->attributes['class'] = 'controls';
$controlstable->cellspacing = 0;
$controlstable->data[] = new html_table_row();
$controlstable->data[0]->cells[] = '<input type="submit" name="buttoneditcategory" value="'.get_string('button:addcategory', 'local_studynotes').'">';
$controlstable->data[0]->cells[] = '<input type="submit" name="buttondeletecategory" value="'.get_string('button:delcategory', 'local_studynotes').'">';
$controlstable->data[0]->cells[] = '<input type="submit" name="buttonmanage" value="'.get_string('button:managecategory', 'local_studynotes').'">';

// display control table
echo html_writer::table($controlstable);

if ($categories = $DB->get_records('local_studynotes_category', array('createby'=>$USER->id))) {
    // prepare category table
    // define header
    $table = new html_table();
    $table->attributes['class'] = 'generaltable boxaligncenter';
    $table->cellspacing = 0;
    $table->head = array ();
    $table->align = array();
    $table->head[] = get_string('select');
    $table->align[] = 'center';
    $table->head[] = get_string('category:name', 'local_studynotes');
    $table->align[] = 'center';

    // table content
    foreach ($categories as $cat) {
    $row = new html_table_row();

    $cell = new html_table_cell();
    $cell->style = 'text-align:center;';
    $cell->text = '<input type="checkbox" class="usercheckbox" name="notescategory'.$cat->id.'" /> ';
    $row->cells[] = $cell;

    $cell = new html_table_cell();
    $cell->style = 'text-align:center;';
    $cell->text = '<a href="editcategory.php?id='.$cat->id.'">'.$cat->categoryname.'</a><br>';
    $row->cells[] = $cell;

    // add row to table
    $table->data[] = $row;
    } // end foreach

    echo html_writer::table($table);
} else {
    echo get_string('category:nocategory', 'local_studynotes');
}


echo '<noscript style="display:inline">';
echo '<div><input type="submit" value="'.get_string('ok').'" /></div>';
echo '</noscript>';
echo '</form>';

echo $OUTPUT->footer();