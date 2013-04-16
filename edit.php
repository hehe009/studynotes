<?php
/**
 * Add/edit studynotes
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../config.php');
require_once('edit_form.php');

$id         = optional_param('id', 0, PARAM_INT);       // studynotes id

$PAGE->set_pagelayout('admin');
$PAGE->set_url('/course/edit.php');