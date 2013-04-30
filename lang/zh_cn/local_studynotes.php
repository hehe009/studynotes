<?php
/**
 * Chinese Simplified language file
 * @package   local_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = '学习笔记';
$string['menu:title'] = '学习笔记';
$string['menu:notes'] = '查看学习笔记';
$string['menu:category'] = '管理分类';
$string['notes:title:add'] = '添加学习笔记';
$string['notes:title:view'] = '查看学习笔记';
$string['notes:header'] = '学习笔记';
$string['notes:subject'] = '主题';
$string['notes:subject:missing'] = '请输入主题';
$string['notes:content'] = '内容';
$string['notes:share'] = '共享';
$string['notes:share_help'] = '与同学共享笔记
  输入他们​​的学生证号码
  用逗号（，）分隔每个号码';
$string['notes:sharewith'] = '共享';
$string['notes:owner'] = '拥有人';
$string['notes:lastmodified'] = '最后修改';
$string['notes:list'] = '学习笔记列表';
$string['notes:nolist'] = '你没有任何的学习笔记，点击“添加学习笔记”创建您的学习笔记';
$string['notes:delete'] = '删除学习笔记';
$string['notes:delete:confirm'] = '确定要删除笔记?';
$string['notes:delete:nonotes'] = '请选择要删除的学习笔记';
$string['notes:category:moveto'] = '移动选定笔记至分类: ';

$string['category:header'] = '学习笔记分类';
$string['category:list'] = '学习笔记分类列表';
$string['category:title:add'] = '添加分类';
$string['category:name'] = '名称';
$string['category:name:missing'] = '请输入分类名称';
$string['category:nocategory'] = '你没有任何分类';
$string['category:delete'] = '删除学习笔记的分类';
$string['category:delete:categoryname'] = '学习笔记分类:';
$string['category:delete:confirm'] = '确定要删除分类?';
$string['category:delete:nonotes'] = '请选择要删除的分类';
$string['category:name:uncategory'] = '没有分类';

$string['button:addnotes'] = '添加';
$string['button:delnotes'] = '删除';
$string['button:edit'] = '编辑';
$string['button:addcategory'] = '添加分类';
$string['button:delcategory'] = '删除分类';

$string['log:sharewith'] = '共享笔记号码: {$a->notesid} 及用户号码: {$a->userid}';
$string['log:viewnotes'] = '查看笔记号码: {$a}';
$string['log:addnotes'] = '添加笔记号码: {$a}';
$string['log:editnotes'] = '编辑笔记号码: {$a}';
$string['log:deletenotes'] = '删除笔记号码: {$a}';
$string['log:addcategory'] = '添加分类号码: {$a}';
$string['log:editcategory'] = '编辑分类号码: {$a}';
$string['log:delcategory'] = '删除分类号码: {$a}';
$string['log:newrelation'] = '指派笔记及分类关系. 笔记号码: {$a->notesid}, 分类号码: {$a->categoryid}';
$string['log:updaterelation'] = '更新笔记及分类关系. 笔记号码: {$a->notesid}, 由分类号码: {$a->fromid} 至分类号码: {$a->toid}';


$string['error:nopermission'] = '对不起，你无查看此笔记的权限';
$string['error:notexists'] = '对不起，您查看的笔记不存在或没有与您共享';
$string['error:inputcharacter'] = '无效的字符输入';
$string['error:invalidusername'] = '无效的用户';

// capabilities
$string['studynotes:enable'] = '启用学习笔记';
$string['studynotes:category'] = '启用学习笔记分类';