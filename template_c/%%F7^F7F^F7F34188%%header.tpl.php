<?php /* Smarty version 2.6.26, created on 2013-06-29 02:50:44
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'header.tpl', 5, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo ((is_array($_tmp=@$this->_tpl_vars['page_title'])) ? $this->_run_mod_handler('default', true, $_tmp, "亨得利神秘顾客问卷调查系统") : smarty_modifier_default($_tmp, "亨得利神秘顾客问卷调查系统")); ?>
</title>
	<link rel="stylesheet" type="text/css" href="./css/css.css" />
	
	<link rel="stylesheet" type="text/css" href="./css/jqueryui.css" />
	<link rel="stylesheet" type="text/css" href="./css/tables.css" /><!---->
	<link rel="stylesheet" type="text/css" href="./css/art_dialog/aero/aero.css" /><!---->
	<script type="text/javascript" language="JavaScript" src="./js/artDialog.js"></script><!---->
	<script type="text/javascript" language="JavaScript" src="./js/jquery-1.6.3.js"></script><!-- checked -->
	<script type="text/javascript" language="JavaScript" src="./js/jquery-ui.js"></script><!-- checked -->
	<link rel="stylesheet" type="text/css" href="./css/fenxi-tub.css" />
</head>
<body>
<script type="text/javascript"><?php echo 'var $j = jQuery.noConflict();'; ?>
</script>
<!--  -->
	<table width="100%"  height="96"  border="0" align="center" cellpadding="0" cellspacing="0" background="images/index_02.jpg">
		<tr>
			<td>
			<table width="950" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-color:Black; margin: 0px auto; ">
				<tr>
					<td width="275" ><img align="left"  src="images/index_01.gif" width="268" height="96" /></td>
					<td valign="top">
					<table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table width="100%"  border="0" align="right" cellpadding="0" cellspacing="0">
									<tr align="right">
										<td width="194" align="right" style="padding-right:10px"><img src="./images/index_06.gif" width="194" height="19" alt="盈联咨询电话" /></td>
										<td style="text-align:right">
											<table width="100%" style="text-align:right" border="0">
												<tr>
													<td style="text-align:left">当前组别 :<font color="red"> <?php echo $this->_tpl_vars['context']['cuser']['group']; ?>
 </font> </td>
													<td style="text-align:left;width:40%">当前用户 :<font color="red"> <?php echo $this->_tpl_vars['context']['cuser']['name']; ?>
 </font>  <a href="zhuxiao.php">注销</a></td>
												</tr>
												<tr>
													<td style="text-align:left"> 最新数据 : <font color="red"> <?php if ($this->_tpl_vars['max_year']): ?><?php echo $this->_tpl_vars['max_year']; ?>
年 -<?php endif; ?> 第 2 期 </font></td>
													<td style="text-align:left"> 当前期数 : <font color="red"> <?php if ($this->_tpl_vars['year']): ?><?php echo $this->_tpl_vars['year']; ?>
年 -<?php endif; ?> 第 <?php echo $this->_tpl_vars['context']['cstage']['stage']; ?>
 期 </font> </td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="right">
							<table border="0" cellpadding="0" cellspacing="0" align="right">
								<tr>
									<td align="center" width="70">
										<a href="index.php"  class="anniu" >首页</a>
									</td>
									<?php if ($this->_tpl_vars['navs']): ?>
										<?php $_from = $this->_tpl_vars['navs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
											<td align="center"><img src="images/index_14.gif" width="5" height="42" alt="" />
											</td><td  align="center" style="padding:0 12px;"><a href="index.php?c=<?php echo $this->_tpl_vars['item']['c']; ?>
&a=<?php echo $this->_tpl_vars['item']['a']; ?>
" class="anniu"><?php echo $this->_tpl_vars['item']['title']; ?>
</a></td>
										<?php endforeach; endif; unset($_from); ?>
									<?php endif; ?>
									<td align="center"><img src="images/index_14.gif" width="5" height="42" alt="" /></td>
									<td align="center" width="70"><a href="index.php?c=user" class="anniu" >用户信息</a></td>							
									<td align="center"><img src="images/index_14.gif" width="5" height="42" alt="" /></td>
									<td align="center" width="70"><a href="#" class="anniu" >关于我们</a></td>
									
								</tr>
							</table>
							</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	
	<table width="965" height="" style="border-color:Black; margin: 0px auto; ">
	<tr>
		<td width="225" height="33" background="images/image_03.gif"></td>
		<td width="740"  background="images/image_04.gif">
		<table width="735" height="27">
			<tr>
				<td width="15"></td>
				<td width="700" style="text-align: left">					<a href="index.php">首页</a>
					<?php if ($this->_tpl_vars['location']): ?>
						<?php $_from = $this->_tpl_vars['location']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
							 &gt; <?php if ($this->_tpl_vars['item']['url']): ?><a href="index.php?<?php echo $this->_tpl_vars['item']['url']; ?>
"><?php echo $this->_tpl_vars['item']['title']; ?>
</a><?php else: ?><?php echo $this->_tpl_vars['item']['title']; ?>
<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>