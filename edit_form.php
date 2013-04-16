<?php
/**
 * Study notes Moodle form
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir.'/formslib.php');

class studynotes_edit_forms extends moodleform {

    function definition() {
        global $OUTPUT, $CFG;

        $mform       =& $this->_form;

        $mform->addElement('notes:header', 'local_studynotes');

        $mform->addElement('text','notestitle', get_string('notes:title', 'local_studynotes'),'maxlength="100" size="50"');
        $mform->addRule('notestitle', get_string('notes:title:missing', 'local_studynotes'), 'required', null, 'client');
        $mform->setType('notestitle', PARAM_TEXT);

        $mform->addElement('editor','notescontent_editor', get_string('notes:content', 'local_studynotes'), null, editor_options());
        $mform->setType('notescontent_editor', PARAM_RAW);

        $mform->addElement('text','notesshare', get_string('notes:share', 'local_studynotes'),'maxlength="100" size="50"');
        $mform->addHelpButton('notesshare', 'notes:share');
        $mform->setType('notesshare', PARAM_TEXT);

        $this->add_action_buttons(false,get_string('savechanges'));
    }

    /**
     * Returns the options array to use in forum text editor
     *
     * @return array
     */
    private static function editor_options() {
        global $COURSE, $PAGE, $CFG;
        // TODO: add max files and max size support
        $maxbytes = get_user_max_upload_file_size($PAGE->context, $CFG->maxbytes, $COURSE->maxbytes);
        return array(
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'maxbytes' => $maxbytes,
            'trusttext'=> true,
            'return_types'=> FILE_INTERNAL | FILE_EXTERNAL
        );
    }
}