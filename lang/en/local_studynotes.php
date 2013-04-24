<?php
/**
 * English language file
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Study notes';
$string['menu:title'] = 'Study notes';
$string['menu:notes'] = 'View notes';
$string['menu:category'] = 'Manage categories';
$string['notes:title:add'] = 'Add a new study note';
$string['notes:title:view'] = 'View study note';
$string['notes:header'] = 'Study note';
$string['notes:subject'] = 'Subject';
$string['notes:subject:missing'] = 'notes subject is missing';
$string['notes:content'] = 'Content';
$string['notes:share'] = 'Share';
$string['notes:share_help'] = 'Share your notes with classmates
  input their student IDs
  use comma(,) to separate each ID';
$string['notes:sharewith'] = 'Share with';
$string['notes:owner'] = 'Owner';
$string['notes:lastmodified'] = 'Last modified';
$string['notes:list'] = 'List of study notes';
$string['notes:delete'] = 'Delete study notes';
$string['notes:delete:confirm'] = 'Are you sure to delete these notes?';
$string['notes:delete:nonotes'] = 'Please select a notes to delete';
$string['notes:category:moveto'] = 'Move selected notes to category: ';

$string['category:header'] = 'Study notes category';
$string['category:list'] = 'List of study notes categories';
$string['category:title:add'] = 'Add a new category';
$string['category:name'] = 'Name';
$string['category:name:missing'] = 'Category name is missing';
$string['category:nocategory'] = 'You have no category yet';


$string['button:addnotes'] = 'Add a new notes';
$string['button:delnotes'] = 'Delete selected notes';
$string['button:edit'] = 'Edit';
$string['button:addcategory'] = 'Add a category';
$string['button:delcategory'] = 'Delete selected categories';
$string['button:managecategory'] = 'Manage notes';

$string['log:sharewith'] = 'share notes id: {$a->notesid} with user id: {$a->userid}';
$string['log:viewnotes'] = 'view notes id: {$a}';
$string['log:addnotes'] = 'add notes id: {$a}';
$string['log:editnotes'] = 'edit notes id: {$a}';
$string['log:deletenotes'] = 'delete notes id: {$a}';
$string['log:addcategory'] = 'add category id: {$a}';
$string['log:editcategory'] = 'edit category id: {$a}';
$string['log:delcategory'] = 'delete category id: {$a}';


$string['error:nopermission'] = 'Sorry, you do not have the right to view this notes';
$string['error:notexists'] = 'Sorry, the notes you are trying to view does not exist or does not shared with you';
$string['error:inputcharacter'] = 'Invalid character input';
$string['error:invalidusername'] = 'Invalid username';

// capabilities
$string['studynotes:enable'] = 'Enable study notes';
$string['studynotes:category'] = 'Enable study notes category';