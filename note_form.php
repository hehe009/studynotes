<?php
/**
 * BJOU studynotes note form
 * @package   bjoustudynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');

class bjoustudynotes_note_edit_form extends moodleform {

    function definition() {
        $mform =& $this->_form;
        $mform->addElement('header', 'general', get_string('title', 'bjoustudynotes'));

        $mform->addElement('html', '<div class="bgnotes">');

        $mform->addElement('textarea', 'content', get_string('content', 'notes'), array('rows'=>18, 'cols'=>52));
        $mform->setType('content', PARAM_RAW);
        $mform->addRule('content', get_string('nocontent', 'notes'), 'required', null, 'client');

        $mform->addElement('html', '</div>');

        $this->add_action_buttons();

        $mform->addElement('hidden', 'publishstate', 'draft');
        $mform->setType('course', PARAM_INT);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('course', PARAM_INT);

        $mform->addElement('hidden', 'userid');
        $mform->setType('user', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'noteid');
        $mform->setType('noteid', PARAM_INT);
    }
}
