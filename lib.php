<?php
/**
 * library file
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



/**
 * Generate link in navigation block.
 * function called by navigation block automatically.
 *
 * @param global_navigation
 *
 * @return nil
 */
function local_studynotes_extends_navigation(global_navigation $navigation) {
    global $CFG;

    if (has_capability('local/studynotes_enable', context_system::instance())) {
        $nodeexamresultmenu = $navigation->add(get_string('menu:title', 'local_studynotes'));

        $nodeexamresult = $nodeexamresultmenu->add(get_string('menu:notes', 'local_studynotes'),
            new moodle_url($CFG->wwwroot.'/local/studynotes/viewall.php'));

        if (has_capability('local/studynotes_category', context_system::instance())) {
            $nodeexamresult = $nodeexamresultmenu->add(get_string('menu:category', 'local_studynotes'),
                new moodle_url($CFG->wwwroot.'/local/studynotes/category.php'));
        }
    }
}