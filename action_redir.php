<?php
/**
 * Wrapper script redirecting user operations to correct destination.
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$postdata = data_submitted();


if (isset($postdata->buttonedit)) {
    $PAGE->set_url('/local/studynotes/action_redir.php', array('formaction'=>'edit.php'));
    require_once('edit.php');
    die();
} else if (isset($postdata->buttondelete)) {
    $PAGE->set_url('/local/studynotes/action_redir.php', array('formaction'=>'delete.php'));
    require_once('delete.php');
    die();
} else if (isset($postdata->buttoneditcategory)) {
    $PAGE->set_url('/local/studynotes/action_redir.php', array('formaction'=>'editcategory.php'));
    require_once('editcategory.php');
    die();
} else if (isset($postdata->buttondeletecategory)) {
    $PAGE->set_url('/local/studynotes/action_redir.php', array('formaction'=>'deletecategory.php'));
    require_once('deletecategory.php');
    die();
} else {
    $PAGE->set_url('/local/studynotes/action_redir.php', array('formaction'=>'linkup.php'));
    require_once('linkup.php');
    die();
}