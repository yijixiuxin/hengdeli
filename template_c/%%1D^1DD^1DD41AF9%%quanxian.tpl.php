<?php /* Smarty version 2.6.26, created on 2013-06-29 03:48:01
         compiled from quanxian.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<td width="202" height="167"  valign="top" ><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "left_quanxian.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<script type="text/javascript">
<!--
	<?php echo '
	
	'; ?>

	//-->
</script>

		<td valign="top">
		
					<?php if ($this->_tpl_vars['message']): ?>
						<div class="message_output" style="padding: 3px 0 3px 0; width: 300px; margin: 0 auto; text-align: center">
							<p style="font-size:18px"><font color="red"><?php echo $this->_tpl_vars['message']; ?>
</font></p>
						</div>
					<?php endif; ?>
			<div class="wenti-biao">
				<table width="100%" height="46" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#999999" >
					<tr align="center" bgcolor="#f0f0f0"  >
						<td>id</td>
						<td>用户名</td>
						<td>类型</td>
						<td>区域</td>
						<td colspan="2">操作</td>
					</tr>
					<?php if ($this->_tpl_vars['gaoceng']): ?>
						<?php $_from = $this->_tpl_vars['gaoceng']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
						<tr bgcolor="#FFFFFF">
							<td align="center"><?php echo $this->_tpl_vars['item']['id']; ?>
</td>
							<td align="center"><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
							<td align="center"><?php echo $this->_tpl_vars['item']['group']; ?>
</td>
							<td align="center"> - </td>
							<td align="center">
								<a href="#" class="biandan_juzhong" onClick="modify_user(<?php echo $this->_tpl_vars['item']['id']; ?>
);" >修改</a></td>
							<td align="center">
								<a href="#" class="biandan_juzhong" onClick="delete_user(<?php echo $this->_tpl_vars['item']['id']; ?>
);" >删除</a></td>
						</tr> 
						<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
					
					<?php if ($this->_tpl_vars['qudao']): ?>
						<?php $_from = $this->_tpl_vars['qudao']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
						<tr bgcolor="#FFFFFF">
							<td align="center"><?php echo $this->_tpl_vars['item']['id']; ?>
</td>
							<td align="center"><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
							<td align="center"><?php echo $this->_tpl_vars['item']['group']; ?>
</td>
							<td align="center"><?php echo $this->_tpl_vars['item']['ainfo']['name']; ?>
</td>
							<td align="center">
								<a href="#" class="biandan_juzhong" onClick="modify_user(<?php echo $this->_tpl_vars['item']['id']; ?>
);" >修改</a></td>
							<td align="center">
								<a href="#" class="biandan_juzhong" onClick="delete_user(<?php echo $this->_tpl_vars['item']['id']; ?>
);" >删除</a></td>
						</tr> 
						<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
					<tr bgcolor="#FFFFFF"><td colspan="6"><a href="#" class="biandan_juzhong" onClick="add_user();">添加用户</a></td></tr>
				</table>
			</div>

<div id="modify_api" title="用户管理">
	<form id="modify_api_form" method="post">
		<input value="0" name="id" id="user_id" type="hidden" />
		用户名：<input type="text" name="name" id="user_name" style="width:150px" /> </br/>
		类　型：<select id="user_level" name="level" style="width:150px" onChange="select_area();">
			<option value="10">高层</option>
			<option value="5">渠道</option>
		</select></br/>
		密　码：<input type="password" name="pwd" id="user_pwd" style="width:150px" /> <span id="chgpwd"><input type="checkbox" name="chgpwd" value="1" />修改密码</span></br/>
		<span id="user_area_span" style="display:none;">
		区　域：<select name="acode" id="user_area" style="width:150px">
				<?php $_from = $this->_tpl_vars['able_areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?><option value="<?php echo $this->_tpl_vars['item']['code']; ?>
"><?php echo $this->_tpl_vars['item']['name']; ?>
</option><?php endforeach; endif; unset($_from); ?>
			</select></br/>
		</span>
		<input type="button" value="提交" onClick="user_submit();"/>
	</form>
</div>
		</td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<?php echo '
$j(function(){
		$j(".message_output").fadeOut(5000);		//消息框淡出
		//modify api ajax - todo
		$j(\'#modify_api\').dialog({
			autoOpen: false,
			width: 400,
			bgiframe: true,
			resizable: false,
			modal: true, //遮罩
			close: function(){  }
		});
});
function modify_user(id)
{
	$j(\'#chgpwd\').show();
	$j.ajax({
		url:\'index.php?c=user&a=edit&id=\'+id,
		dataType:\'json\',
		success:function(data){
			if(!data) alert(\'未知错误\');
			else if(data.status) {
				data = data.data;
				$j(\'#user_id\').val(data.id);
				$j(\'#user_name\').val(data.name);
				$j(\'#user_level\').val(data.level);
				if(data.level < 10){
					$j(\'#user_area\').val(data.acode);
					$j(\'#user_area_span\').show();
				}
				$j(\'#modify_api\').dialog("open");
			}
		}
	});
}
function select_area(){
	var level = $j(\'#user_level\').val();
	if(level < 10){
		$j(\'#user_area_span\').show();
	}else{
		$j(\'#user_area_span\').show();
	}
}
function delete_user(id){
	var res = confirm(\'确定删除该用户吗？\');
	if(!res) return true;
	$j.ajax({
		url:\'index.php?c=user&a=delete&id=\'+id,
		dataType:\'json\',
		success:function(data){
			if(!data) alert(\'未知错误\');
			else if(data.status) { alert(\'删除成功\'); location.reload(); }
			else alert(\'删除失败\'+ data.msg);
		}
	});
}
//添加用户
function add_user()
{
	$j(\'#chgpwd\').hide();
	$j(\'#modify_api\').dialog("open");
}
function user_submit(){
	$j.ajax({
		url:\'index.php?c=user&a=update\',
		data:$j(\'#modify_api_form\').serialize(),
		type: "post",
		dataType:\'json\',
		success:function(data){
			if(!data) alert(\'未知错误\');
			else if(data.status) { alert(\'成功\'); location.reload(); }
			else alert(\'失败\'+ data.msg);
		}
	});
}
'; ?>

</script>