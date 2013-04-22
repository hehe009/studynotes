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
global $DB;

require_login();
$personalcontext = context_user::instance($USER->id, MUST_EXIST);
require_capability('local/studynotes:enable', $personalcontext);

$url = new moodle_url('/local/studynotes/viewall.php');

$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');
$PAGE->set_title(get_string('notes:list', 'local_studynotes'));
$PAGE->set_heading($PAGE->title);

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

// get all notes created by and share with this user
$sql = "SELECT sn.id, sn.subject, sn.owner, sn.modified, u.lastname, u.firstname
        FROM {local_studynotes} sn, {local_studynotes_share} sns, {user} u
        WHERE u.id = sn.owner
        AND sn.id = sns.notesid
        AND (sns.userid = :sharewith OR sn.owner = :userid)";
$params['userid'] = $USER->id;
$params['sharewith'] = $USER->id;
$allmynotes = $DB->get_records_sql($sql, $params);

// prepare control table for add and del button
$controlstable = new html_table();
$controlstable->attributes['class'] = 'controls';
$controlstable->cellspacing = 0;
$controlstable->data[] = new html_table_row();
$controlstable->data[0]->cells[] = $OUTPUT->single_button(new moodle_url('edit.php'), get_string('button:addnotes', 'local_studynotes'), 'get');
$controlstable->data[0]->cells[] = $OUTPUT->single_button(new moodle_url('del.php'), get_string('button:delnotes', 'local_studynotes'), 'get');

// display control table
echo html_writer::table($controlstable);

// prepare notes table
// define header
$table = new html_table();
$table->width = "95%";
$table->cellspacing = 0;
$table->head = array ();
$table->align = array();
$table->head[] = get_string('select');
$table->align[] = 'center';
$table->head[] = get_string('notes:subject', 'local_studynotes');
$table->align[] = 'left';
$table->head[] = get_string('notes:owner', 'local_studynotes');
$table->align[] = 'center';
$table->head[] = get_string('notes:lastmodified', 'local_studynotes');
$table->align[] = 'center';

// table content
foreach ($allmynotes as $notes) {
    $row = new html_table_row();

    $cell = new html_table_cell();
    $cell->style = 'text-align:center';
    $cell->text = '<input type="checkbox" class="usercheckbox" name="notes'.$notes->id.'" /> ';
    $row->cells[] = $cell;

    $cell = new html_table_cell();
    $cell->style = 'text-align:left';
    $cell->text = '<a href="notes.php?id='.$notes->id.'">'.$notes->subject.'</a><br>';
    $row->cells[] = $cell;

    $cell = new html_table_cell();
    $cell->style = 'text-align:center';
    $cell->text = $notes->lastname.' '.$notes->firstname;
    $row->cells[] = $cell;

    $cell = new html_table_cell();
    $cell->style = 'text-align:center';
    $cell->text = userdate($notes->modified);
    $row->cells[] = $cell;

    // add row to table
    $table->data[] = $row;
}
echo html_writer::table($table);
echo $OUTPUT->footer();