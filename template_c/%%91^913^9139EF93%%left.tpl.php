<?php /* Smarty version 2.6.26, created on 2013-06-29 04:03:20
         compiled from left.tpl */ ?>
<table width="202" height="461" border="0" cellpadding="0" cellspacing="0" style="background:url('images/image_09.gif') no-repeat;">
	<tr align="center">
		<td height="207" width="202" valign="top" >
			<table width="194" height="158" align="right">
				<tr>
					<td height="52" ></td>
				</tr>
				<tr>
					<td width="583" height="98" >
					<form action="index.php" method="get">
						<input type="hidden" name="a" value="<?php echo $this->_tpl_vars['a']; ?>
" />
						<input type="hidden" name="c" value="<?php echo $this->_tpl_vars['c']; ?>
" />
						
						<select name="stage" id="stage" style="width:150px;margin:5px 0;">
							<option value="0">测评期数</option>
						<?php if ($this->_tpl_vars['stages']): ?>
							<?php $_from = $this->_tpl_vars['stages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
							<option value="<?php echo $this->_tpl_vars['item']['stage']; ?>
" <?php if ($this->_tpl_vars['item']['c']): ?>selected="selected"<?php endif; ?>>第<?php echo $this->_tpl_vars['item']['stage']; ?>
期</option> 
							<?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
						</select><br/>
						
						<!---->
						
						<input  type="submit" style="width:150px;margin:5px 0;" value="提交" />
					</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr align="center">
		<td height="254" valign="top" >
		<table width="141" height="60">
			<tr align="center">
				<td><span style="padding-left:20px"><!--img height="49" align="absmiddle" width="165" src="images/zhongguo2.gif"--></span></td>
				<!--td align="center"><img src="images/zhongguo.gif" width="165" height="49" align="absmiddle"  /></td-->
			</tr>
		</table>
		</td>
	</tr>
</table>
<?php echo '
<script>
$j(document).ready(function(){
	$j(\'#area\').change(function(){
		$j.ajax({
			url : \'ajax.php?a=get_mendian_by_area\',
			data : "acode="+$j(\'#area\').val(),
			type:\'post\',
			dataType: \'json\',
			success:function(data){
				if(!data.status){
					alert(\'area error\');
					return false;
				}
				var str = \'\', data = data.data;
				for(var o in data){
					str += \'<option value="\'+ data[o].code +\'">\'+data[o].name+\'</option>\';
				}
				$j(\'#mendian\').html(str);
			}
		});
	});
});
</script>
'; ?>