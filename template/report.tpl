{include file="header.tpl"}
{if $page_style}{$page_style}{/if}
		<td width="202" height="167" valign="top" style="text-align:left;">
			<form method="post">
				<div class="st_tree" style="text-align:center;">
					<select name="stage" style="width:150px;margin-bottom:5px;">
						<option value="0">测评期数</option>
					{if $stages}
						{foreach from=$stages item=item}
						<option value="{$item.stage}" {if $item.c}selected="selected"{/if}>第{$item.stage}期</option> 
						{/foreach}
					{/if}
					</select>
					{if $areas}
					<select name="acode" id="acode" style="width:150px;margin-bottom:5px;">
						<option value="">区域</option>
						{foreach from=$areas item=item}
						<option value="{$item.code}" {if $item.c}selected="selected"{/if}>{$item.name}</option> 
						{/foreach}
					</select>
					{/if}
					<input type="submit" class="" value="提交" style="width:150px;margin:0 auto;">
				</div>
			</form>
			{include file="left-tree.tpl"}
		</td>
		<td valign="top">
			{if $creportInfo}
				<div style="padding:10px 0;">
					<div style="margin:0;text-align:left;">
						本期为第{$context.cstage.stage}期<br/>
						<!--{*执行时间{$context.cstage.start_date} - {$context.cstage.end_date}<br/>*}-->
						<!--{*检查盛时{$creportInfo.ctype1}家，尚时{$creportInfo.ctype2}家；新增表行{$creportInfo.cadd}家，新减表行{$creportInfo.cdelete}家。<br/>*}-->
					</div>
				</div>
			{/if}

			{if $table_type eq "analyse" || $table_type eq "analyse_percent"}
				{if $table_name}<p style="line-height:2.5em;font-size:13px">{$table_name}</p>{/if}
				<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="the_tb">
					<tr bgcolor="#f0f0f0">
						<th rowspan="2"></th>
						<th colspan="4">频次分布</th>
						<th rowspan="2">{if $page_type eq 'report'}集团{else}区域{/if}平均成绩</th>
						<th rowspan="2">众数</th>
						<th rowspan="2">中位数</th>
					</tr>
					{if $table_type eq "analyse"}
					<tr bgcolor="#f0f0f0">
						<th>[0,65分)</th>
						<th>[65分,75分)</th>
						<th>[75分,85分)</th>
						<th>[85分,100分]</th>
					</tr>
					{else}
					<tr bgcolor="#f0f0f0">
						<th>[0,65%)</th>
						<th>[65%,75%)</th>
						<th>[75%,85%)</th>
						<th>[85%,100%]</th>
					</tr>
					{/if}
					{if $c_analyse}
					<tr bgcolor="#FFFFFF">
						<td>本期</td> <td>{$c_analyse.d}</td><td>{$c_analyse.c}</td><td>{$c_analyse.b}</td> <td>{$c_analyse.a}</td> <td>{$c_analyse.average}</td><td>{$c_analyse.mode}</td><td>{$c_analyse.mid}</td>
					</tr>
					{/if}
					{if $p_analyse}
					<tr bgcolor="#FFFFFF">
						<td>上期</td> <td>{$p_analyse.d}</td><td>{$p_analyse.c}</td><td>{$p_analyse.b}</td> <td>{$p_analyse.a}</td> <td>{$p_analyse.average}</td><td>{$p_analyse.mode}</td><td>{$p_analyse.mid}</td>
					</tr>
					{/if}
					{if $compare}
					<tr bgcolor="#FFFFFF">
						<td>变化情况</td> <td>{$compare.d}</td><td>{$compare.c}</td><td>{$compare.b}</td> <td>{$compare.a}</td> <td>{$compare.average}</td><td>{$compare.mode}</td><td>{$compare.mid}</td>
					</tr>
					{/if}
				</table>
				<!--div style="float:left;width:100%;" class="info-area">
					<div class="btn" style="float:right;">
						<a id="S_Menu_12" class="a-btn b2" href="javascript:void(0);">柱图</a>
						<a id="S_Menu_10" class="a-btn b3" href="javascript:void(0);">线图</a>
					</div>
				</div-->
				<!--{*<div class="ofuns" style="width:100%;float:left;margin-top:5px;">
					<a href="javascript:chart_window('{$chart_id.c}', '{$chart_id.a}', 'line');" class="abtn abtn-chart-xian">线图</a>
					<a href="javascript:chart_window('{$chart_id.c}', '{$chart_id.a}', 'zhu');" class="abtn abtn-chart-zhu">柱图</a>
				</div>*}-->
				{if $rank}
					<p style="line-height:2.5em;font-size:13px">{$rank.name}</p>
					<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="the_tb">
						<thead>
							<tr bgcolor="#f0f0f0">
								{foreach from=$rank.titles item=title name=list}
									<th id="dth_{$smarty.foreach.list.index}">{$title}</th>{/foreach}
							</tr>
						</thead>
						<tbody>
							{foreach from=$rank.datas key=key item=data}
								<tr>
									{foreach from=$data item=item name=sublist}
										<td class="dtd_{$smarty.foreach.sublist.index}">{$item}</td>
									{/foreach}
								</tr>
							{/foreach}
						</tbody>
					</table>
				{/if}
			{elseif $table_type eq "three_line"} <!--{* 三线表 *}-->
				{if $table_name}<p style="line-height:2.5em;font-size:13px">{$table_name}</p>{/if}
				<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="the_tb">
					<thead>
						<tr bgcolor="#f0f0f0">
							{foreach from=$table_titles item=title name=list}
								<th id="dth_{$smarty.foreach.list.index}">{$title}</th>{/foreach}
						</tr>
					</thead>
					<tbody>
						{foreach from=$table_datas key=key item=data}
							<tr>
								{foreach from=$data item=item name=sublist}
									<td class="dtd_{$smarty.foreach.sublist.index}">{$item}</td>
								{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table>
			<!--{*
				{if $chart_id}
				<div class="ofuns" style="width:100%;float:left;margin-top:5px;">
					<a href="javascript:chart_window('{$chart_id.c}', '{$chart_id.a}', 'line');" class="abtn abtn-chart-xian">线图</a>
					<a href="javascript:chart_window('{$chart_id.c}', '{$chart_id.a}', 'zhu');" class="abtn abtn-chart-zhu">柱图</a>
				</div>
				{/if}
			*}-->
			{elseif $table_type eq "muilty_three_line"} <!--{* 多个三线表 *}-->
				{foreach from=$tables item=table_item name=tlist}
				{if $table_item.table_name}<p style="line-height:2.5em;font-size:13px">{$table_item.table_name}</p>{/if}
				<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="tb_{$smarty.foreach.tlist.index}">
					<thead>
						<tr bgcolor="#f0f0f0">
						{foreach from=$table_item.titles item=title name=list}
							<th id="dth_{$smarty.foreach.tlist.index}_{$smarty.foreach.list.index}">{$title}</th>
						{/foreach}
						</tr>
					</thead>
					<tbody>
						{foreach from=$table_item.datas item=data}
							<tr>
							{foreach from=$data item=item name=sublist}
								<td class="dtd_{$smarty.foreach.tlist.index}_{$smarty.foreach.sublist.index} dtd_{$smarty.foreach.sublist.index}">{$item}</td>
							{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table><br/>
				{/foreach}
			{elseif $table_type eq "compare_three_line"} <!--{* 多个三线表 *}-->
				{foreach from=$tables item=table_item name=tlist}
				{if $table_item.table_name}<p style="line-height:2.5em;font-size:13px">{$table_item.table_name}</p>{/if}
				<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="tb_{$smarty.foreach.tlist.index}">
					<thead>
						<tr bgcolor="#f0f0f0">
						{if $table_item.type eq 'ext'}
							{foreach from=$table_item.titles item=title name=list}
								{if $smarty.foreach.list.index == 0}
								<th id="dth_{$smarty.foreach.tlist.index}_{$smarty.foreach.list.index}">{$title}</th>
								{else}
								<th id="dth_{$smarty.foreach.tlist.index}_{$smarty.foreach.list.index}" colspan="2">{$title}</th>
								{/if}
							{/foreach}
						{else}
							{foreach from=$table_item.titles item=title name=list}
								<th id="dth_{$smarty.foreach.tlist.index}_{$smarty.foreach.list.index}">{$title}</th>
							{/foreach}
						{/if}
						</tr>
					</thead>
					<tbody>
						{foreach from=$table_item.datas item=data}
							<tr>
							{foreach from=$data item=item name=sublist}
								<td class="dtd_{$smarty.foreach.tlist.index}_{$smarty.foreach.sublist.index}">{$item}</td>
							{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table><br/>
				{/foreach}
			{elseif $table_type eq "dir"}
				{foreach from=$tables item=item name="tlist"}
				{if $item.table_name}<p style="line-height:2.5em;font-size:13px">{$item.table_name}</p>{/if}
				<table class="three-line" align="center" cellpadding="1" cellspacing="0">
					<thead>
						<tr bgcolor="#f0f0f0">
							<th colspan="2">{$item.title}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$item.datas item=data}
							<tr>
								<td style="text-align:left;padding-left:2em;">{$data.title}</td>
								<td style="width:7em;"><a href="{$data.url}">查看</a></td>
							</tr>
						{/foreach}
					</tbody>
				</table><br/>
				{/foreach}
			{/if}
			
				<div style="height:40px;padding-top:5px;">
					<ul class="f-btn">
						<li><a href="javascript:void(0);" onclick="history.go(-1);" class="fb1">返回</a></li>
						<li><a href="javascript:void(0);" onclick="window.print();" class="fb3">打印</a></li>
					</ul>
				</div>
				<div id="_chart" style="display:none"></div>
			{*if $introInfos}
				<div style="padding:10px 0 10px 10px; text-align:left;">
					{foreach from=$introInfos item=item}{$item}<br/>{/foreach}
				</div>
			{/if*}
		</td>
{include file="footer.tpl"}
{literal}
<script type="text/javascript">
function chart_window(controller, action, type){
	$j("#_chart").load(
		"./part/chart.php?c="+controller+"&a="+action+"&type="+type,
		function(a,b,c){
			art.dialog({
						title:'图表',
						id:'testDialog',
						//lock:true,
						content:a
					});
		}
	);
}
/*
function chart_window_old(controller, action, type){
	$j("#_chart").load(
		"index.php?c="+controller+"&a="+action,
		{type:type,ajax:'chart'},
		function(a,b,c){
			art.dialog({
						title:'图表',
						id:'testDialog',
						lock:true,
						content:a
					});
		}
	);
}*/
//1000-350
//750-350
//500-250
</script>
{/literal}