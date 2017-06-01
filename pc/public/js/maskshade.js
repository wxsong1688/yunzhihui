/**
 * Created by Administrator on 2015/10/23.
 */


function setHeight(){
	var h1 = window.innerWidth;
	var $modalNew = $(".modal-new");
	var h2 = $modalNew.width();
	var h3 = (h1-h2)/2/h1*100;
	$modalNew.css("left",h3+"%");
}
/*<div class="modal-new text-center">
 <div class="modal-tt">
 <p class="float-left modal-tt1">充值（text1）</p>
 <a class="modal-close1 float-right">×</a>
 <div class="clear"></div>
 </div>
 <p class="register-suc-text modal-tt1 modal-ttl">请您打开汇付天下页面完成充值操作（text2）</p>
 <p class="register-success"><span></span>恭喜您注册成功（text3）</p>
 <p class="register-suc-text">恭喜您注册成功，您的登录账号为：（text4）<span>*******（text5）</span></p>
 <a class="btnnew inline-block moadl-finish"  href="#(href1)">已完成（btnTxt1）</a>
 <a class="btnnew1 inline-block reOperate" href="#(href2)">重新充值（btnTxt2）</a>
 </div>*/

//参数请参照上面html代码,如果没有其中一项将其置为空
function modalNew(thisBodyID,text1,text2,text3,text4,text5,text6,btnTxt1,btnTxt2,href1,href2){
	var thisBody = document.getElementById(thisBodyID);
	shadeOut(thisBodyID,thisBody,"imsec2-ul-out");
	var tem1 = document.createElement("div");
	tem1.setAttribute("class", "modal-new text-center");
	document.getElementById(thisBodyID).appendChild(tem1);
	var wrapper1 = '<div class="modal-tt">' +
		'<p class="float-left modal-tt1">'+text1+'</p>'+
		'<p class="modal-close1 float-right" id="closeWin" onclick="'+'closeModal()'+'">×</p>'+
		'<div class="clear"></div>'+
		'</div>';
	var wrapper2 = '<p class="register-suc-text modal-tt1 modal-ttl">'+text2+'</p>';
	var wrapper3 = '<p class="register-success"><span></span>'+text3+'</p>';
	var wrapper4 = '<p class="register-success">'+text4+'</p>';
	var wrapper5 = '<p class="register-suc-text">'+text5+'<span>'+text6+'</span></p>';
	var btn0 = '<a class="btnnew inline-block moadl-finish" href="'+href1+'"'+'onclick="'+'closeModal()"'+'>'+btnTxt1+'</a>';
	var btn1 = '<a class="btnnew1 inline-block reOperate" href="'+href2+'"'+'onclick="'+'closeModal()"'+'>'+btnTxt2+'</a>';
	tem1.innerHTML += wrapper1;
	if(text2.trim()!=""){
		tem1.innerHTML += wrapper2;
	}
	if(text3.trim()!=""){
		tem1.innerHTML += wrapper3;
	}
	if(text4.trim()!=""){
		tem1.innerHTML += wrapper4;
	}
	if(text5.trim()!=""){
		tem1.innerHTML += wrapper5;
	}
	if(btnTxt1.trim()!=""){
		tem1.innerHTML += btn0;
	}
	if(btnTxt2.trim()!=""){
		tem1.innerHTML += btn1;
	}
	setHeight();
}
function closeModal(){
	$('.modal-new').hide();
	$('.imsec2-ul-out').hide();
}
//遮罩底层
function shadeOut(bodyId,sha){
	var para = document.createElement("div");
	para.setAttribute("class", sha);
	document.getElementById(bodyId).appendChild(para);
	getHeight1(bodyId,sha);
}
function getHeight1(bodyId,ss){
	var he = $("#"+bodyId).height();
	$("."+ss).height(he);
}
function popBox(bodyId,txt1,txt2,txt3,txt4,txt5,width,href1,href2){
	var thisBody = document.getElementById(bodyId);
	var para = document.createElement("div");
	para.setAttribute("class", "maskShade-modal");
	var para1 = document.createElement("div");
	para1.setAttribute("class", "show-center modal");
	//<p class="register-success text-center"><span></span>恭喜您注册成功</p>
	var para2 = document.createElement("div");
	var para3 = document.createElement("div");
	var para4 = document.createElement("div");
	var para5 = document.createElement("div");
	var para6 = document.createElement("a");
	var para7 = document.createElement("p");
	var para8 = document.createElement("p");
	var para9 = document.createElement("span");
	var para10 = document.createElement("a");
	var para11 = document.createElement("span");
	var para12 = document.createElement("a");
	para2.setAttribute("class", "show-center");
	para3.setAttribute("class", "show-center imsec2-ul-inner");
	para4.setAttribute("class", "modal-section1 text-center");
	para5.setAttribute("class", "text-right");
	para6.setAttribute("class", "modal-close1");
	para6.setAttribute("onclick", "$('.maskShade-modal').hide();$('.imsec2-ul-out').hide();");
	para7.setAttribute("class", "register-success");
	para8.setAttribute("class", "register-suc-text");
	para10.setAttribute("class", "btn0 inline-block");
	para10.setAttribute("href", href1);
	para12.setAttribute("class", "btn0 btn-bgc inline-block");
	para12.setAttribute("href", href2);
	var node=document.createTextNode("×");
	para6.appendChild(node);
	var node1=document.createTextNode(txt1);
	para7.appendChild(para11);
	para7.appendChild(node1);
	var node2=document.createTextNode(txt2);
	para8.appendChild(node2);
	var node3=document.createTextNode(txt3);
	para9.appendChild(node3);
	var node4=document.createTextNode(txt4);
	para10.appendChild(node4);
	var node5=document.createTextNode(txt5);
	para5.appendChild(para6);
	para8.appendChild(para9);
	para4.appendChild(para5);
	para4.appendChild(para7);
	para4.appendChild(para8);
	para12.appendChild(node5);
	para3.appendChild(para4);
	para2.appendChild(para3);
	para1.appendChild(para2);
	para.appendChild(para1);
	shadeOut(bodyId,thisBody,"imsec2-ul-out");
	thisBody.appendChild(para);
	para2.style.width=width;
	if(txt4.trim() != ""){
		para4.appendChild(para10);
	}
	if(txt5.trim() != ""){
		para4.appendChild(para12);
	}
}
//遮罩底层
function shadeOut(bodyId,thisBody,sha){
	var para = document.createElement("div");
	para.setAttribute("class", sha);
	thisBody.appendChild(para);
	getHeight(bodyId,sha);
}

function getHeight(bodyId,ss){
	var he = $("#"+bodyId).height();
	$("."+ss).height(he);
}
