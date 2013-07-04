{include file="admin/header.tpl"}
<form method="post" action="project.php">
	<input type="hidden" name="id" value="{$project_info.id}" />
	<input type="hidden" name="a" value="update" />
	<input type="hidden" name="year" value="2013" />
	问卷 <input type="text" name="sid" value="{$project_info.sid}" size="50"/><br/>
	title <input type="text" name="title" value="{$project_info.title}" size="50"/><br/>
	备注 <textarea name="info" cols="50" rows="5">{$project_info.info}</textarea><br/>
	<input type="submit" value="提交" />
</form>
{include file="admin/footer.tpl"}