<?php /* Smarty version 2.6.26, created on 2013-06-27 16:56:15
         compiled from user.tpl */ ?>
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
<?php echo '
	$j(function(){ $j(".message_output").fadeOut(5000);});
'; ?>

</script>

		<td valign="top">
			<?php if ($this->_tpl_vars['message']): ?>
				<div class="message_output" style="padding: 3px 0 3px 0; width: 300px; margin: 0 auto; text-align: center">
					<p style="font-size:18px"><font color="red"><?php echo $this->_tpl_vars['message']; ?>
</font></p>
				</div>
			<?php endif; ?>
<!-- 修改密码 -->
<script type="text/javascript">
<!--
	<?php echo '
	function CheckForm(){
		if($j("#pwd").val() == ""){
			art.dialog( {content:\'新密码不能为空！\'},function(){$j("#pwd").focus();}); 
			$j("#pwd").focus();
			return false;	
		}
		if($j("#pwd").val() != $j("#pwd1").val()){
			art.dialog( {content:\'两次密码输入不相同！\'},function(){$j("#pwd1").focus();}); 
			$j("#pwd1").focus();
			return false;	
		}
		if($j("#pwd").val().length < 6 ){
			art.dialog( {content:\'密码不能小于6位！\'},function(){$j("#pwd").focus();}); 
			$j("#pwd").focus();
			return false;	
		}
		$j("#form1").submit();
	}
	
	function CheckForm2(){
		$j("#form2").submit();
	}
	'; ?>

	//-->
</script>

			<div class="wenti-biao">
				<table width="50%" height="46" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" style="margin:auto">
					
					<form id="form1" name="aduser" method="post" action="index.php?c=user" onSubmit="" >
						<tr>
							<td>登陆名：</td>
							<td><?php echo $this->_tpl_vars['context']['cuser']['name']; ?>
</td>	<!-- header.tpl 显示在最上面 来自top.php -->
						</tr>
		
						<tr>
							<td>旧密码：</td><td><input type="password" id="oldpwd" name="oldpwd" value=""></td>
						</tr>
		
						<tr>
							<td>新密码：</td><td><input type="password" id="pwd" name="pwd" value=""></td>
						</tr>
						<tr>
							<td>密码确认：</td><td><input type="password" id="pwd1" name="pwd1" value=""></td>
						</tr>
		
						<tr>
							<td colspan="2" align="center">
								<input type="button" name="button" id="button" value="提交" onClick="CheckForm();" />
							</td>
						</tr>
					</form>
				</table>
			</div>
		</td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>