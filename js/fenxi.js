var tblx ='tu_b';
function leixing(tblxv){
	tblx =tblxv;
}
function show(url,neirong){
if (document.getElementById("bingtu"+neirong).style.display =="none"){
	document.getElementById("bingtu"+neirong).style.display="";
    document.getElementById("nei"+neirong).innerHTML ="<p>&nbsp;</p><p align='center'>该指标各网点平均得分</p>  <p>&nbsp;</p> <iframe src="+url+" width='100%' height='300'  frameborder='0'></iframe>";
	}
	else{
		
		document.getElementById("bingtu"+neirong).style.display="none"};
}
function ditu_api(url,neirong){
	document.getElementById("nei"+neirong).innerHTML ="<iframe src="+url+" width='500' height='300' frameborder='0'> </iframe>";	
}

function tubiao_kuang(iii,ii){
	///for (i=1;i<5;i++){
	///	alert(iii)
	//document.getElementById('cl1-1').src ='images/dc_cl_002-1.png';	
    //adocument.getElementById('cl1-2').src ='images/dc_cl_004-1.png';	
	//document.getElementById('cl5').src ='images/dc_cl_005-1.png';	
	//document.getElementById('cl6').src ='images/dc_cl_006-1.png';	
	document.getElementById('cl'+iii).src ='images/dc_cl_00'+ii+'.png';	
}

function tubiao_lk(iii,ii){
	///for (i=1;i<5;i++){
		///alert(iii)
	//document.getElementById('cl1-1').src ='images/dc_cl_002-1.png';	
    //adocument.getElementById('cl1-2').src ='images/dc_cl_004-1.png';	
	//document.getElementById('cl5').src ='images/dc_cl_005-1.png';	
	//document.getElementById('cl6').src ='images/dc_cl_006-1.png';	
	document.getElementById('cl'+iii).src ='images/dc_cl_00'+ii+'-1.png';	
}



function addURLParam(){

alert (tblx);
}
