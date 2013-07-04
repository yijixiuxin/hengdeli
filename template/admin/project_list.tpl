{include file="admin/header.tpl"}
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
{if $project_list}
	{foreach from=$project_list key=key item=project}
	<tr>
		<td>{$project.id}</td>
		<td>{$project.year}</td>
		<td>{$project.sid}</td>
		<td>{$project.title}</td>
		<td>{$project.info}</td>
		<td><a href="project.php?a=gedit&id={$project.id}">查看问题<a/></td>
		<td><a href="project.php?a=edit&id={$project.id}">修改信息<a/></td>
	{if $project.status eq "0"}
		<td colspan="2"><a href="project.php?a=active&id={$project.id}">激活<a/></td>
	{elseif $project.status eq "1"}
		<td><a href="project.php?a=die&id={$project.id}">停止使用<a/></td>
		<td><a href="project.php?a=calc&id={$project.id}">统计<a/></td>
	{else}<td colspan="2"><a href="project.php?a=calc&id={$project.id}">统计<a/></td>{/if}
	</tr>
	{/foreach}
{/if}
</table>
{include file="admin/footer.tpl"}