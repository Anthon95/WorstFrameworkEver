<?php /* Smarty version Smarty-3.1.16, created on 2014-02-08 19:06:48
         compiled from "C:\wamp\www\SupInternet\annee2\framework\WorstFrameworkEver\app\templates\Main\doSomething.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2845552f6706a410291-86125168%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5c1bc1dd9ebdb2f4d4d3b7661fe61326b7f4509c' => 
    array (
      0 => 'C:\\wamp\\www\\SupInternet\\annee2\\framework\\WorstFrameworkEver\\app\\templates\\Main\\doSomething.tpl',
      1 => 1391882807,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2845552f6706a410291-86125168',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_52f6706a449aa4_95478006',
  'variables' => 
  array (
    'message' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52f6706a449aa4_95478006')) {function content_52f6706a449aa4_95478006($_smarty_tpl) {?><section>
    <p>I do something !</p>
    <p>Here is dynamic content : <?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</p>
</section>
<?php }} ?>
