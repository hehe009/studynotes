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

    public function definition() {
        global $CFG;

        $mform       =& $this->_form;

        if (is_array($this->_customdata)) {
            if (array_key_exists('editoroptions', $this->_customdata)) {
                $editoroptions = $this->_customdata['editoroptions'];
            }
            if (array_key_exists('userid', $this->_customdata)) {
                $userid = $this->_customdata['userid'];
            }
        }

        $mform->addElement('header', 'local_studynotes');

        $mform->addElement('text','subject', get_string('notes:subject', 'local_studynotes'),'maxlength="100" size="50"');
        $mform->addRule('subject', get_string('notes:subject:missing', 'local_studynotes'), 'required', null, 'client');
        $mform->setType('subject', PARAM_TEXT);

        $mform->addElement('editor','message_editor', get_string('notes:content', 'local_studynotes'), null, $editoroptions);
        $mform->setType('message_editor', PARAM_RAW);

        $mform->addElement('text','sharewith', get_string('notes:share', 'local_studynotes'),'maxlength="100" size="50"');
        $mform->addHelpButton('sharewith', 'notes:share');
        $mform->setType('sharewith', PARAM_TEXT);

        $mform->addElement('hidden', 'owner', $userid);
        $mform->addElement('hidden', 'id', '0');

        $this->add_action_buttons(false,get_string('savechanges'));
    }

    function validation($data, $files) {
        global $DB;

        $errors = array();

        if (preg_match('/[\'^£$%&*()}{@#~?><>.;|=_+¬-]/', $data['sharewith'])) {
            $errors['sharewith'] = get_string('error:inputcharacter', 'local_studynotes');
        } else if (!empty($data['sharewith'])) {
            $arrayuserstobevalid = explode(',', $data['sharewith']);
            foreach ($arrayuserstobevalid as $usertobevalid) {
                global $DB;
                if (!$result = $DB->get_record('user',array('username'=>trim($usertobevalid)))) {
                    $errors['sharewith'] = get_string('error:invalidusername', 'local_studynotes');
                }
            }
        } // end else

        return $errors;
    }
}