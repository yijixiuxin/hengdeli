<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>{$page_title|default:"亨得利神秘顾客问卷调查系统"}</title>
{*	<link rel="stylesheet" type="text/css" href="./css/list.css" />*}
	<link rel="stylesheet" type="text/css" href="./css/css.css" />
	
	<link rel="stylesheet" type="text/css" href="./css/jqueryui.css" />
	<link rel="stylesheet" type="text/css" href="./css/tables.css" /><!--{* 表样式 *}-->
	<link rel="stylesheet" type="text/css" href="./css/art_dialog/aero/aero.css" /><!--{* 对话框 *}-->
	<script type="text/javascript" language="JavaScript" src="./js/artDialog.js"></script><!--{* 对话框 *}-->
{*
	<link rel="stylesheet" type="text/css" href="./css/jindu.css" />
	<link rel="stylesheet" type="text/css" href="./css/tishi/bubble-tooltip.css" media="screen">
	
	<link rel="stylesheet" type="text/css" href="./css/tupian/lightbox.css" media="screen" />
	<!--图片 -->

	<!--提示 -->
	<link rel="stylesheet" type="text/css" href="./skin/aero/aero.css" id="artDialogSkin"  />
	<script type="text/javascript" language="JavaScript" src="./js/artDialog.js"></script>
	
	<link rel="stylesheet" type="text/css" href="./css/fenxi-tub.css" />
	<!--图标样式 -->
	<link rel="stylesheet" type="text/css" href="./css/voteresult_1.css" />
	
	<script type="text/javascript" language="JavaScript" src="./js/tishi/bubble-tooltip.js"></script>
	<!--提示 -->
	
	<script type="text/javascript" language="JavaScript" src="./js/FusionCharts.js"></script>
	<!--饼图 -->
	<script type="text/javascript" language="JavaScript" src="./js/fenxi.js"></script>
	<!--自定义 -->
	<script type="text/javascript" language="JavaScript" src="./js/picjs/FancyZoom.js"></script>
	<!-- 图片效果 -->
	<script	type="text/javascript" language="JavaScript" src="./js/picjs/FancyZoomHTML.js"></script>
	<!-- 图片效果 -->
	
	{if $duomeiti}
	<script type="text/javascript" language="JavaScript" src="./js/tupian/prototype.js"></script>
	<!--图片 -->
	<script type="text/javascript" language="JavaScript" src="./js/tupian/scriptaculous.js?load=effects"> </script>
	<!--图片 -->
	<script type="text/javascript" language="JavaScript" src="./js/tupian/lightbox.js"></script>
	<!--图片 -->
	{else}
	<script type="text/javascript" language="JavaScript" src="./js/fun.js"></script>
	{/if}
*}
	<script type="text/javascript" language="JavaScript" src="./js/jquery-1.6.3.js"></script><!-- checked -->
	<script type="text/javascript" language="JavaScript" src="./js/jquery-ui.js"></script><!-- checked -->
	<link rel="stylesheet" type="text/css" href="./css/fenxi-tub.css" />
{*
	<link rel="stylesheet" type="text/css" href="./css/tishi/bubble-tooltip.css" media="screen">
	<!--图标样式 -->
	<link rel="stylesheet" type="text/css" href="./css/voteresult_1.css" />
*}
</head>
<body>
<script type="text/javascript">{literal}var $j = jQuery.noConflict();{/literal}</script>
<!-- {*include file="part/js_function.tpl"*} -->
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
													<td style="text-align:left">当前组别 :<font color="red"> {$context.cuser.group} </font> </td>
													<td style="text-align:left;width:40%">当前用户 :<font color="red"> {$context.cuser.name} </font>  <a href="zhuxiao.php">注销</a></td>
												</tr>
												<tr>
													<td style="text-align:left"> 最新数据 : <font color="red"> {if $max_year}{$max_year}年 -{/if} 第 2 期 </font></td>
													<td style="text-align:left"> 当前期数 : <font color="red"> {if $year}{$year}年 -{/if} 第 {$context.cstage.stage} 期 </font> </td>
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
									{if $navs}
										{foreach from=$navs key=key item=item}
											<td align="center"><img src="images/index_14.gif" width="5" height="42" alt="" />
											</td><td  align="center" style="padding:0 12px;"><a href="index.php?c={$item.c}&a={$item.a}" class="anniu">{$item.title}</a></td>
										{/foreach}
									{/if}
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
				<td width="700" style="text-align: left">{*if $indicator}200{else}464{/if*}
					<a href="index.php">首页</a>
					{if $location}
						{foreach from=$location key=key item=item}
							 &gt; {if $item.url}<a href="index.php?{$item.url}">{$item.title}</a>{else}{$item.title}{/if}
						{/foreach}
					{/if}
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>