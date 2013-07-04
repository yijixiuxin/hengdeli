<?php /* Smarty version 2.6.26, created on 2013-06-27 05:31:39
         compiled from admin/project_list.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div style="width:100%; text-align:center; margin:10px 0;">
	<a style="margin:0 10px;" class="submit ui-state-default ui-corner-all" href="project.php?a=edit">添加+</a>
	<a style="margin:0 10px;" class="submit ui-state-default ui-corner-all" href="project.php">刷新</a>
</div>

<table width="95%" class="StormyWeatherFormTABLE" style="margin:auto;">
	<tr>
		<th>ID</th>
		<th>年份</th>
		<th>问卷</th>
		<th>标题</th>
		<th>备注</th>
		<th>问题</th>
		<th>编辑</th>
		<th colspan="2">状态</th>
	</tr>
<?php if ($this->_tpl_vars['project_list']): ?>
	<?php $_from = $this->_tpl_vars['project_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['project']):
?>
	<tr>
		<td><?php echo $this->_tpl_vars['project']['id']; ?>
</td>
		<td><?php echo $this->_tpl_vars['project']['year']; ?>
</td>
		<td><?php echo $this->_tpl_vars['project']['sid']; ?>
</td>
		<td><?php echo $this->_tpl_vars['project']['title']; ?>
</td>
		<td><?php echo $this->_tpl_vars['project']['info']; ?>
</td>
		<td><a href="project.php?a=gedit&id=<?php echo $this->_tpl_vars['project']['id']; ?>
">查看问题<a/></td>
		<td><a href="project.php?a=edit&id=<?php echo $this->_tpl_vars['project']['id']; ?>
">修改信息<a/></td>
	<?php if ($this->_tpl_vars['project']['status'] == '0'): ?>
		<td colspan="2"><a href="project.php?a=active&id=<?php echo $this->_tpl_vars['project']['id']; ?>
">激活<a/></td>
	<?php elseif ($this->_tpl_vars['project']['status'] == '1'): ?>
		<td><a href="project.php?a=die&id=<?php echo $this->_tpl_vars['project']['id']; ?>
">停止使用<a/></td>
		<td><a href="project.php?a=calc&id=<?php echo $this->_tpl_vars['project']['id']; ?>
">统计<a/></td>
	<?php else: ?><td colspan="2"><a href="project.php?a=calc&id=<?php echo $this->_tpl_vars['project']['id']; ?>
">统计<a/></td><?php endif; ?>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>