<?php /* Smarty version 2.6.26, created on 2013-06-27 05:31:36
         compiled from admin/header.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../js/jquery-1.6.3.js"></script>
	<script src = "../js/jquery-ui.js" type="text/javascript"></script>
	<title>管理后台</title>
</head>
<body>

<?php echo '
<style type="text/css">
.StormyWeatherFormTABLE {
    border-color: #DEE3EF #7386A5 #7386A5 #DEE3EF;
    border-style: solid;
    border-width: 1px;
}
.StormyWeatherDataTD,  .StormyWeatherFormTABLE td{
    background-color: #DEE3EF;
    border-color: #FFFFFF #C6CBDE #C6CBDE #FFFFFF;
    border-style: solid;
    border-width: 1px;
    color: #000000;
    font-size: 12px;
}
.StormyWeatherColumnTD,  .StormyWeatherFormTABLE th {
    background-color: #9CAECE;
    border-color: #7386A5 #DEE3EF #DEE3EF #7386A5;
    border-style: solid;
    border-width: 1px;
    color: #FFFFFF;
    font-size: 12px;
    font-weight: bold;
}
</style>
'; ?>

<?php if ($this->_tpl_vars['message']): ?>
<div style="clear:both;margin:10px auto;width:95%;text-align:center;border:1px solid #000000;"><?php echo $this->_tpl_vars['message']; ?>
</div>
<?php endif; ?>

<div style="width:100%; text-align:center; margin:10px 0;">
	<a style="margin:0 10px;" class="submit ui-state-default ui-corner-all" href="../index.php">回首页</a>
	<a style="margin:0 10px;" class="submit ui-state-default ui-corner-all" href="stage.php">期数列表</a>
	<a style="margin:0 10px;" class="submit ui-state-default ui-corner-all" href="project.php">项目列表</a>
</div>