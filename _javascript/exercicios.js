$(document).ready(function(){

	$("td").innerWidth("100px");
	$("td").innerHeight("100px");

	$("#oprsList").val("");
	$("#nvlList").val("");
	$("#tmpList").val("");

	$("#varConfig").hide();
	var cSelecao = 0;
	$("#configCont td").click(function(){
		$(this).toggleClass("selecao");
		if($(this).hasClass("selecao")){
			cSelecao++;
			$("#oprsList").val($("#oprsList").val()+"-"+$(this).text());
		}else{
			cSelecao--;
			$("#oprsList").val($("#oprsList").val().replace("-"+$(this).text(),""));
		}
		if(cSelecao==0){
			$(this).addClass("selecao");
			if(!$("#configCont .configMaior td:contains('"+$(this).text()+"')").hasClass("selecao")){
				$("#configCont .configMaior td:contains('"+$(this).text()+"')").trigger("click");
			}
			cSelecao++;
		}
		$("#configCont td").each(function(){
			if($(this).hasClass("selecao")){
				if(!$("#configCont .configMenor li:contains('"+$(this).text()+"')").hasClass("selecaoLi")){
					$("#configCont .configMenor li:contains('"+$(this).text()+"')").trigger("click");
				}
			}else{
				if($("#configCont .configMenor li:contains('"+$(this).text()+"')").hasClass("selecaoLi")){
					$("#configCont .configMenor li:contains('"+$(this).text()+"')").trigger("click");
				}
			}
		});
		$("#qtsQtd").attr("min",cSelecao);
		if(Number($("#qtsQtd").val())<cSelecao){
			$("#qtsQtd").val(cSelecao);
		}
	});
	$("#configCont li").click(function(){
		$(this).toggleClass("selecaoLi");
		$("#configCont li").each(function(){
			if($(this).hasClass("selecaoLi")){
				if(!$("#configCont .configMaior td:contains('"+$(this).text()+"')").hasClass("selecao")){
					$("#configCont .configMaior td:contains('"+$(this).text()+"')").trigger("click");
				}
			}else{
				if($("#configCont .configMaior td:contains('"+$(this).text()+"')").hasClass("selecao")){
					$("#configCont .configMaior td:contains('"+$(this).text()+"')").trigger("click");
				}
			}
		});
	});
	$(".IOp").trigger("click");

	$("#configNivel button").click(function(){
		$("#configNivel button").removeClass().css("border-bottom", "none");
		$(this).addClass("selecao").css("border-bottom", "3px solid #00a2ff");
		$("#nvlList").val($(this).text());
		$("#configNivel button").each(function(){
			if($(this).hasClass("selecao")){
				$("#configNivel .configMenor li:contains('"+$(this).text()+"')").addClass("selecaoLi");
			}else{
				$("#configNivel .configMenor li:contains('"+$(this).text()+"')").removeClass("selecaoLi");
			}
		});
	});
	$("#configNivel li").click(function(){
		$("#configNivel li").removeClass();
		$(this).addClass("selecaoLi");
		$("#nvlList").val($(this).text());
		$("#configNivel li").each(function(){
			if($(this).hasClass("selecaoLi")){
				$("#configNivel .configMaior button:contains('"+$(this).text()+"')").addClass("selecao").trigger("click");
			}else{
				$("#configNivel .configMaior button:contains('"+$(this).text()+"')").removeClass("selecao");
			}
		});
	});
	$(".padrao").trigger("click");

	$("#configTempo button").click(function(){
		$("#configTempo button").removeClass().css("border-bottom", "none");
		$(this).addClass("selecao").css("border-bottom", "3px solid #00a2ff");
		$("#configTempo button").each(function(){
			if($(this).hasClass("selecao")){
				$("#configTempo .configMenor li:contains('"+$(this).text()+"')").addClass("selecaoLi");
			}else{
				$("#configTempo .configMenor li:contains('"+$(this).text()+"')").removeClass("selecaoLi");
			}
		});
	});
	$(".comTempo").click(function(){
		$(".balao").show();
		$("#h").focus();
	});
	$(".semTempo").click(function(){
		$(".balao").hide();
	})
	$("#configTempo li").click(function(){
		$("#configTempo li").removeClass();
		$(this).addClass("selecaoLi");
		$("#configTempo li").each(function(){
			if($(this).hasClass("selecaoLi")){
				$("#configTempo .configMaior button:contains('"+$(this).text()+"')").addClass("selecao").trigger("click");
			}else{
				$("#configTempo .configMaior button:contains('"+$(this).text()+"')").removeClass("selecao");
			}
		});
	});
	$(".semTempo").trigger("click");

	$("#frmComecar").submit(function(){
		if($("#cTempo").hasClass("selecao")) {
			if($("#h").val()==""&& $("#min").val()==""&& parseInt($("#s").val())<5){
				$("#s").focus();
				$("#s").val("");
				return false;
			}
			if($("#h").val()==""&& $("#min").val()==""&& $("#s").val()==""){
				$("#h").focus();
				return false;
			}
			if($("#h").val()==""){
				$("#h").val("0");
			}
			if($("#min").val()==""){
				$("#min").val("0");
			}
			if($("#s").val()==""){
				$("#s").val("0");
			}
			var segundos = parseInt($("#s").val())+parseInt(parseInt($("#min").val())*60)+parseInt(parseInt($("#h").val())*3600);
			if(segundos==0){
				$("#h").focus();
				return false;
			}
			var tempo = $("#h").val()+":"+$("#min").val()+":"+$("#s").val();
			$("#tmpList").val(tempo);
		}else {
			$("#tmpList").val("");
		}
	});
	$(window).resize(function(){
		if($(window).width()<420){
			$(".configMaior").hide();
			$(".configMenor").show();
		}else{
			$(".configMaior").show();
			$(".configMenor").hide();
		}
	});
	$(window).trigger("resize");
});