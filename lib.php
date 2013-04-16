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
function studynotes_extends_navigation(global_navigation $navigation) {

    $nodeexamresultmenu = $navigation->add(get_string('menu:title', 'local_studynotes'));

    $nodeexamresult = $nodeexamresultmenu->add(get_string('menu:notes', 'local_studynotes'),
                                        new moodle_url($CFG->wwwroot.'local/studynotes/viewall.php'));

    $nodeexamresult = $nodeexamresultmenu->add(get_string('menu:category', 'local_studynotes'),
                                        new moodle_url($CFG->wwwroot.'local/studynotes/category.php'));
}