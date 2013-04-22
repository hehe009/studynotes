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
$url = new moodle_url('/local/studynotes/notes.php', array('id'=>$id));
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');
$PAGE->navbar->add(get_string('notes:header', 'local_studynotes'));
$PAGE->navbar->add(get_string('menu:notes', 'local_studynotes'), new moodle_url('/local/studynotes/viewall.php'));



$PAGE->set_title(get_string('notes:header', 'local_studynotes'));
$PAGE->set_heading($PAGE->title);


echo $OUTPUT->header();

// retrieve notes
if ($notes = $DB->get_record('local_studynotes', array('id'=>$id))) {

    // verify user has the right to view the notes
    $isvalid = false;
    $isowner = false;
    if ($notes->owner == $USER->id) {
        $isvalid = true;
        $isowner = true;
    } else if ($DB->get_record('local_studynotes_share', array('userid'=>$USER->id, 'notesid'=>$id))) {
        $isvalid = true;
    }



    if ($isvalid) {

        // display 'edit' button if the notes is created by this user
        if ($isowner) {
            // prepare control table for add and del button
            $tablecontrols = new html_table();
            $tablecontrols->attributes['class'] = 'controls';
            $tablecontrols->cellspacing = 0;
            $tablecontrols->width = "100%";
            $row = new html_table_row();

            $cell = new html_table_cell();
            $cell->style = 'text-align:right';

            $cell->text = '<div style="text-align:right>'
                            .$OUTPUT->single_button(new moodle_url('edit.php', array('id'=>$notes->id)), get_string('button:edit', 'local_studynotes'), 'get')
                            . '</div>';
            $row->cells[] = $cell;

            // add row to table
            $tablecontrols->data[] = $row;

            // display control table
            echo html_writer::table($tablecontrols);
        }

        //echo $OUTPUT->heading($notes->subject, 2);
        echo $OUTPUT->heading($notes->subject);
        echo $notes->message;

        // get sharewith users
        $sql = 'SELECT u.id, u.username, u.lastname, u.firstname, sns.notesid
            FROM {local_studynotes_share} sns, {user} u
            WHERE sns.userid = u.id
            AND sns.notesid = :notesid
            ORDER by u.username';
        $params['notesid'] = $id;
        if ($sharewith = $DB->get_records_sql($sql, $params)) {
            $tablesharewith = new html_table();
            $tablesharewith->cellspacing = 0;
            $tablesharewith->width = "40%";

            $row = new html_table_row();

            $cell = new html_table_cell();
            $cell->style = 'text-align:center';
            $cell->header = true;
            $cell->text = get_string('notes:sharewith', 'local_studynotes');
            $row->cells[] = $cell;

            // add header row to the table
            $tablesharewith->data[] = $row;

            $row = new html_table_row();
            $cell = new html_table_cell();
            $cell->style = 'text-align:center';
            $cell->text = '';

            // list share with users
            foreach($sharewith as $user) {
                $cell->text .= $user->lastname . ' ' . $user->firstname . '<br>';
            }
            $row->cells[] = $cell;

            // add user list row to the table
            $tablesharewith->data[] = $row;

            // display the table
            echo html_writer::table($tablesharewith);

        } // end if this notes is shared with others

    } else {
        echo $OUTPUT->heading($PAGE->title);
        echo $OUTPUT->notification(get_string('error:nopermission', 'local_studynotes'));
    }
} else {
    echo $OUTPUT->heading($PAGE->title);
    echo $OUTPUT->notification(get_string('error:notexists', 'local_studynotes'));
}
echo $OUTPUT->footer();