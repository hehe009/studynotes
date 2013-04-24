<?php
/**
 * Study notes categroy form
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir.'/formslib.php');

class studynotes_edit_category_forms extends moodleform {

    public function definition() {
        global $CFG;

        $mform       =& $this->_form;

        if (is_array($this->_customdata)) {
            if (array_key_exists('userid', $this->_customdata)) {
                $userid = $this->_customdata['userid'];
            }
        }

        $mform->addElement('header', 'local_studynotes');
        $mform->addElement('text','txtname', get_string('category:name', 'local_studynotes'),'maxlength="15" size="20"');
        $mform->addRule('txtname', get_string('category:name:missing', 'local_studynotes'), 'required', null, 'client');
        $mform->setType('txtname', PARAM_TEXT);

        $mform->addElement('hidden', 'owner', $userid);
        $mform->addElement('hidden', 'id', '0');

        $this->add_action_buttons(false,get_string('savechanges'));
    }
}
