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
global $CFG, $DB;

$id         = optional_param('id', 0, PARAM_INT);       // studynotes id

require_login();

$pageparams = array('id'=>$id);
$personalcontext = context_user::instance($USER->id);
require_capability('local/studynotes:enable', $personalcontext);


//prepare url
$url = new moodle_url('/local/studynotes/edit.php',$pageparams);
$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('notes:header', 'local_studynotes'));
$PAGE->navbar->add($PAGE->title);
$PAGE->navbar->add(get_string('menu:notes', 'local_studynotes'), new moodle_url('/local/studynotes/viewall.php'));

// prepare editor options
$editoroptions = array(
    'maxfiles'   => EDITOR_UNLIMITED_FILES,
    'maxbytes'   => $CFG->maxbytes,
    'trusttext'  => false,
    'forcehttps' => false,
    'context'    => $personalcontext
);

//// create the form
$editform = new studynotes_edit_forms(NULL, array('userid'=>$USER->id, 'editoroptions' => $editoroptions));

if ($id > 0) {
    if ($notes = $DB->get_record('local_studynotes', array('id'=>$id))) {

        // check if user is notes' owner
        if ($notes->owner != $USER->id) {
             echo $OUTPUT->header();
            echo $OUTPUT->heading($PAGE->title);
            echo $OUTPUT->notification(get_string('error:notexists', 'local_studynotes'));
            echo $OUTPUT->footer();
            die();
        }

        // put notes message back to editor
        $notes->message_editor = array('text'=>$notes->message,'format'=>$notes->messageformat);

        // get sharewith users
        $sql = 'SELECT u.id, u.username, sns.notesid
                FROM {local_studynotes_share} sns, {user} u
                WHERE sns.userid = u.id
                AND sns.notesid = :notesid
                ORDER by u.username';
        $params['notesid'] = $id;
        if ($sharewith = $DB->get_records_sql($sql, $params)) {
            $arrayusers = array();
            foreach ($sharewith as $users) {
                $arrayusers[] = $users->username;
            } // end foreach

            $notes->sharewith = implode(",", $arrayusers);
            unset($arrayusers);
        }

        // set form data
        $editform->set_data($notes);
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading($PAGE->title);
        echo $OUTPUT->notification(get_string('error:notexists', 'local_studynotes'));
        echo $OUTPUT->footer();
        die();
    }
}


if ($formdata = $editform->get_data()) {
    //print_object($formdata);die();
    $notes = new stdClass();

    $notes->modified = time();
    $notes->subject = $formdata->subject;
    $notes->message = $formdata->message_editor['text'];
    $notes->messageformat = $formdata->message_editor['format'];
    $notes->owner = $formdata->owner;

    if (($formdata->id == 0) AND (confirm_sesskey())) { // new notes
        $notesid = $DB->insert_record('local_studynotes', $notes);

        // handle share notes with users
        if($formdata->sharewith != '') {

            // add share with users, delimiter is ','
            $formdata->sharewith = preg_replace( '/\s+/', '', $formdata->sharewith);
            $arraysharewith = explode(',', $formdata->sharewith);
            foreach($arraysharewith as $user) {
                $sql = 'SELECT id as userid FROM {user} WHERE username = :username';
                $params['username'] = $user;
                $sharewithuser = $DB->get_record_sql($sql, $params);
                $sharewithuser->notesid = $notesid;
                $result = $DB->insert_record('local_studynotes_share', $sharewithuser);

                // log user action
                add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/edit.php', get_string('log:sharewith', 'local_studynotes', $sharewithuser), '', $USER->id);
            } // end foreach
        }

        // log user action
        add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/edit.php', get_string('log:addnotes', 'local_studynotes', $notesid), '', $USER->id);

        redirect(new moodle_url('/local/studynotes/viewall.php'));
    } else { // edit notes
        $notes->id = $formdata->id;

        if(confirm_sesskey()) {
            $notesid = $DB->update_record('local_studynotes', $notes);

            // handle share notes with users
            if($formdata->sharewith != '') {

                // delete previous share records
                if ($sharewithusers = $DB->get_records('local_studynotes_share', array('notesid'=>$notes->id))) {
                    $DB->delete_records('local_studynotes_share', array('notesid'=>$notes->id));
                }

                // add share with users, delimiter is ','
                $formdata->sharewith = preg_replace( '/\s+/', '', $formdata->sharewith);
                $arraysharewith = explode(',', $formdata->sharewith);
                foreach($arraysharewith as $user) {
                    $sql = 'SELECT id as userid FROM {user} WHERE username = :username';
                    $params['username'] = $user;
                    $sharewithuser = $DB->get_record_sql($sql, $params);
                    $sharewithuser->notesid = $notes->id;
                    $result = $DB->insert_record('local_studynotes_share', $sharewithuser);

                    // log user action for leader info
                    add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/edit.php', get_string('log:sharewith', 'local_studynotes', $sharewithuser), '', $USER->id);
                } // end foreach
            }

            // log user action
            add_to_log($SITE->id, 'studynotes', get_string('notes:header','local_studynotes'), '../local/studynotes/edit.php', get_string('log:editnotes', 'local_studynotes', $id), '', $USER->id);

            redirect(new moodle_url('/local/studynotes/viewall.php'));
        }
    }

}


$PAGE->set_title(get_string('notes:title:add', 'local_studynotes'));
$PAGE->set_heading(get_string('notes:header', 'local_studynotes'));

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

$editform->display();

echo $OUTPUT->footer();