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
$sql = "SELECT notes.id, notes.subject, notes.owner, notes.userid, notes.firstname, notes.lastname, notes.modified,
        relation.categoryname, relation.categoryid
        FROM
        (SELECT sn.id as id, sn.subject, sn.owner, sns.userid, u.firstname, u.lastname, sn.modified
            FROM mdl_local_studynotes sn
            left join mdl_local_studynotes_share sns on sns.notesid = sn.id
            left join mdl_user u on sn.owner = u.id
            WHERE sn.owner = :userid
            OR sns.userid = :sharewith) notes
       LEFT OUTER join
       (SELECT snc.categoryname, snc.id as categoryid, snc.createby, snr.notesid
           FROM mdl_local_studynotes_category snc, mdl_local_studynotes_relation snr
           where snr.categoryid = snc.id
            AND snc.createby = :cateogrycreator) relation
       ON notes.id = relation.notesid
       GROUP by notes.id
       ORDER by relation.categoryname DESC, notes.subject ASC";

$params['userid'] = $USER->id;
$params['sharewith'] = $USER->id;
$params['cateogrycreator'] = $USER->id;
$allmynotes = $DB->get_records_sql($sql, $params);

// For delete
echo '<form action="action_redir.php" method="post" id="studynotesform">';

// prepare control table for add and del button
$controlstable = new html_table();
$controlstable->attributes['class'] = 'controls';
$controlstable->cellspacing = 0;
$controlstable->data[] = new html_table_row();
$controlstable->data[0]->cells[] = '<input type="submit" name="buttonedit" value="'.get_string('button:addnotes', 'local_studynotes').'">';
if ($allmynotes) {
    $controlstable->data[0]->cells[] = '<input type="submit" name="buttondelete" value="'.get_string('button:delnotes', 'local_studynotes').'">';
}

// display control table
echo html_writer::table($controlstable);

if ($allmynotes) {
    // prepare notes table
    // define header
    $table = new html_table();
    $table->attributes['class'] = 'generaltable boxaligncenter';
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

    $arraycategoryandnotes = array();


    // prepare a data array for display note and category in table
    foreach ($allmynotes as $notes) {

        if ($notes->categoryid == null) {
            $notes->categoryid = 0;
        }

        if (!empty($arraycategoryandnotes[$notes->categoryid])) {
            $arraytemp = $arraycategoryandnotes[$notes->categoryid];
            array_push($arraytemp, $notes);
            $arraycategoryandnotes[$notes->categoryid] = $arraytemp;
        } else {
            $arraycategoryandnotes[$notes->categoryid] = array($notes);
        }
    }

    // table content
    foreach ($arraycategoryandnotes as $category=>$allnotes) {
        $row = new html_table_row();
        $cell = new html_table_cell();

         // add row to table
        $table->data[] = $row;
        $cell->style = 'text-align:center';
        $cell->colspan = 4;
        if ($category != 0 ) {
            $cell->text = $allnotes[0]->categoryname;
        } else {
            $cell->text = '<b>'.get_string('category:name:uncategory', 'local_studynotes').'</b>';
        }
        $row->cells[] = $cell;


        foreach($allnotes as $notes) {

        $row = new html_table_row();

        $cell = new html_table_cell();
        $cell->style = 'text-align:center;width:10%';
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
        $cell->text = userdate($notes->modified, get_string('strftimedatetimeshort', 'langconfig'));
        $row->cells[] = $cell;

        // add row to table
        $table->data[] = $row;
        }
    }

    echo html_writer::table($table);

    echo '<input type="button" id="checkall" value="'.get_string('selectall').'" /> ';
    echo '<input type="button" id="checknone" value="'.get_string('deselectall').'" /> ';
} else {
    echo get_string('notes:nolist', 'local_studynotes');
}
if ($categories = $DB->get_records('local_studynotes_category', array('createby'=>$USER->id))) {
    $displaylist = array();
    $displaylist['0'] = get_string('category:name:uncategory', 'local_studynotes');

    foreach ($categories as $category) {
        $displaylist[$category->id] = $category->categoryname;
    }

    // display category list
    echo html_writer::tag('label', get_string('notes:category:moveto', 'local_studynotes'), array('for'=>'formactionid'));
    echo html_writer::select($displaylist, 'categoryid', '', array(''=>'choosedots'), array('id'=>'formactionid'));
}

// for delete
echo '<noscript style="display:inline">';
echo '<div><input type="submit" value="'.get_string('ok').'" /></div>';
echo '</noscript>';
echo '</form>';

$module = array('name'=>'core_user', 'fullpath'=>'/user/module.js');
$PAGE->requires->js_init_call('M.core_user.init_participation', null, false, $module);

// JS for category selection box
echo '<script>';
echo 'document.getElementById("formactionid").onchange = (function(){ document.getElementById("studynotesform").submit();});';
echo '</script>';


echo $OUTPUT->footer();

