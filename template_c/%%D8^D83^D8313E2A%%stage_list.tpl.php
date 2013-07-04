<?php /* Smarty version 2.6.26, created on 2013-06-27 05:31:36
         compiled from admin/stage_list.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table width="95%" class="StormyWeatherFormTABLE" style="margin:auto;">
	<tr>
		<th>期数</th>
		<th>项目id</th>
		<th colspan="2">操作</th>
	</tr>
<?php if ($this->_tpl_vars['stage_list']): ?>
	<?php $_from = $this->_tpl_vars['stage_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['stage']):
?>
	<tr>
		<td> <?php echo $this->_tpl_vars['stage']['stage']; ?>
 </td>
		<td> <?php echo $this->_tpl_vars['stage']['pid']; ?>
 </td>
		<td><a href="stage.php?a=statis&stage=<?php echo $this->_tpl_vars['stage']['stage']; ?>
">统计<a/></td>
		<td><a href="javascript:;" onClick="upfile(<?php echo $this->_tpl_vars['stage']['stage']; ?>
);">上传数据<a/></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
</table>
<div id="waiting" title='loading'>
	<form action="stage.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="a" value="upfile"/>
		<input type="file" name="file"/>
		<input type="hidden" name="stage" id="tostage" value="" />
		<input type="submit" />
	</form>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script type="text/javascript">
$(function(){
	$(\'#waiting\').dialog({
			autoOpen: false,
			width: 400,
			bgiframe: true,
			resizable: false,
			modal: true,
	});
});
function upfile(stage){
	$(\'#tostage\').val(stage);
	$(\'#waiting\').dialog("open");
}
</script>
'; ?>