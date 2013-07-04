<link rel="stylesheet" type="text/css" href="./css/tree/SimpleTree.css" />
<script type="text/javascript" language="JavaScript" src="./js/SimpleTree.js"></script>
<div class="st_tree">
<ul>
{if $nav_arrays}
	{foreach from=$nav_arrays item=ngroup}
	<li><a href="javascript:;">{$ngroup.title}</a></li>
	<ul><!--{*show="true"*}-->
		{foreach from=$ngroup.datas item=nav}
		<li><a href="{$nav.url}">{$nav.title}</a></li>
		{/foreach}
	</ul>
	{/foreach}
{/if}
</ul>
</div>
{literal}
<script>
$j(document).ready(function(){
	$j(".st_tree").SimpleTree({
		click:function(a){
			if("false" == $j(a).attr("hasChild")){
				location.href=$j(a).attr("href");
			}
				//alert($j(a).attr("ref"));
		}
	});
});
</script>
{/literal}