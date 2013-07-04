<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript" src="./js/FusionCharts.js"></script>
</head>
<body>
<div id="chartdiv_bing_1" align="left">there should be a chart!</div>
	<script type="text/javascript">//Column3D.swf	Line.swf
		var myChart_bing_1 = new FusionCharts("./flash/{$chart.flash}", "myChartId_zhu_1", "{$chart.width}", "{$chart.height}", "0", "0");
		myChart_bing_1.addParam('wmode','transparent');
		myChart_bing_1.setDataXML("{$chart.xml}");
		myChart_bing_1.render("chartdiv_bing_1");
	</script>
</body>
</html>