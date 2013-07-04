{include file="admin/header.tpl"}

<table width="95%" class="StormyWeatherFormTABLE" style="margin:auto;">
	<tr>
		<th>期数</th>
		<th>项目id</th>
		<th colspan="2">操作</th>
	</tr>
{if $stage_list}
	{foreach from=$stage_list key=key item=stage}
	<tr>
		<td> {$stage.stage} </td>
		<td> {$stage.pid} </td>
		<td><a href="stage.php?a=statis&stage={$stage.stage}">统计<a/></td>
		<td><a href="javascript:;" onClick="upfile({$stage.stage});">上传数据<a/></td>
	</tr>
	{/foreach}
{/if}
</table>
<div id="waiting" title='loading'>
	<form action="stage.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="a" value="upfile"/>
		<input type="file" name="file"/>
		<input type="hidden" name="stage" id="tostage" value="" />
		<input type="submit" />
	</form>
</div>
{include file="admin/footer.tpl"}
{literal}
<script type="text/javascript">
$(function(){
	$('#waiting').dialog({
			autoOpen: false,
			width: 400,
			bgiframe: true,
			resizable: false,
			modal: true,
	});
});
function upfile(stage){
	$('#tostage').val(stage);
	$('#waiting').dialog("open");
}
</script>
{/literal}