$(document).ready(function(){
	$("#list-mcc li").click(function(){
		$("#voltar").show();
		$(this).parent().hide();
	});
	$("#btrg3sp").click(function(){
		$("#rga3smp").show();
		statusMcc = listaMcc.rgr3sp;
	});
	$("#btJuros").click(function(){
		$("#juros").show();
		statusMcc = listaMcc.juros;
	});

	// (#rga3smp) Regra de 3 simples
	$("#rga3smp input[type='number']").change(function(){
		var c = 0;
		$(".rg3cmp").each(function(){
			if($(this).val()!=""&&$(this).val()!="x"){
				c++;
			}
		});
		if(c==3){
			$(".rg3cmp").each(function(){
				if($(this).val()==""){
					$(this)
						.css("background-color","#f1f1f1")
						.attr("readonly","true")
						.attr("type","text")
						.val("x");
				}
			});
		}else{
			$(".rg3cmp").each(function(){
				if($(this).val()=="x"){
					$(this)
						.css("background-color","#ffffff")
						.removeAttr("readonly")
						.attr("type","number");
				}
			});
		}
	}).keyup(function(){$(this).trigger("change");}).focus(function(){$(this).trigger("change");});
	$("#juros input[type='number']").change(function(){
		if(parseInt($("#txttempo").val())>1){
			$("#cmptempot>option[value='ad']").text("Dias");
			$("#cmptempot>option[value='asn']").text("Semanas");
			$("#cmptempot>option[value='am']").text("Meses");
			$("#cmptempot>option[value='ab']").text("Bimestres");
			$("#cmptempot>option[value='at']").text("Trimestres");
			$("#cmptempot>option[value='asm']").text("Semestres");
			$("#cmptempot>option[value='aa']").text("Anos");
		}else{
			$("#cmptempot>option[value='ad']").text("Dia");
			$("#cmptempot>option[value='asn']").text("Semana");
			$("#cmptempot>option[value='am']").text("Mês");
			$("#cmptempot>option[value='ab']").text("Bimestre");
			$("#cmptempot>option[value='at']").text("Trimestre");
			$("#cmptempot>option[value='asm']").text("Semestre");
			$("#cmptempot>option[value='aa']").text("Ano");
		}
		$("#txtmontante").attr("min",$("#txtcapital").val());

		var c = 0; // Contador
		var ctd = 0; // Contador txtDinheiro
		$(".juroscmp").each(function(){
			if($(this).val()!=""&&$(this).val()!="?"){
				c++;
				if($(this).hasClass("txtDinheiro")){
					ctd++;
				}
			}
		});
		if(c==3){
			$(".juroscmp").each(function(){
				if($(this).val()==""){
					$(this)
						.css("background-color","#f1f1f1")
						.attr("readonly","true")
						.attr("type","text")
						.val("?");
				}
			});
		}else{
			$(".juroscmp").each(function(){
				if($(this).val()=="?"){
					$(this)
						.css("background-color","#ffffff")
						.removeAttr("readonly")
						.attr("type","number");
				}
			});
		}
		if($("#txttaxa").val()=="?"){
			$("#cmptaxat")
				.attr("disabled","true")
				.css("background-image","none");
			$("#cmptaxat").val($("#cmptempot").val());
		}else{
			$("#cmptaxat")
				.removeAttr("disabled")
				.css("background-image","url('_imagens/setaSelect.png')");
		}
		if((c==2)&&(ctd==2)){
			$(".txtDinheiro").each(function(){
				if($(this).val()==""){
					$(this)
						.css("background-color","#f1f1f1")
						.attr("readonly","true")
						.attr("type","text")
						.val("?");
				}
			});
		}
	}).keyup(function(){$(this).trigger("change");}).focus(function(){$(this).trigger("change");});
	$("#cmptempot").change(function(){$("#juros input[type='number']").trigger("change");});
	$("#fechar").click(function(){
		if($("#ex", parent.document).html().indexOf("\"")>=0){
			$("#ex", parent.document).html("");
			$("#ex", parent.document).focus();
		}
		$("#ex",parent.document).removeAttr("readonly");
		$("#mcc", parent.document).removeClass().addClass("topHeightLess").on("webkitAnimationEnd mozAniationEnd MSAnimationEnd oanimationend animationend",function(){
			$(this).off().hide();
		});
		if(parent.cacheTeclado){
			parent.abrirTeclado();
			parent.cacheTeclado = false;
		}
		window.parent.setStatusMcc(false);
	});
	$("#voltar").click(function(){
		$(".frm").hide();
		$("#list-mcc").show();
		$(this).hide();
		statusMcc = "";
	});
	$("#calcula",parent.document).submit(function(){
		if(window.parent.statusMcc){
			$("#ex",parent.document).html("\"");
			$(".rg3cmp").trigger("change");
			$(".juroscmp").trigger("change");
			if(statusMcc===listaMcc.rgr3sp){
				if($("#ex",parent.document).html()=="\""){
					var preencheu = true;
					$("#rga3smp input").each(function(){
						if($(this).val()==""){
							$(this).focus();
							preencheu = false;
							return false;
						}
					});
					if(preencheu){
						$("#ex",parent.document).html("\""+$("#gdz1sp").val()+":"+$("#rg3cmp1").val()+","+$("#rg3cmp3").val()+";"+$("#gdz2sp").val()+":"+$("#rg3cmp2").val()+","+$("#rg3cmp4").val()+"|"+$("#ppcSp").val()+"[rga3smp]"+"\"");
					}
				}
			}
			if(statusMcc===listaMcc.juros){
				if($("#ex",parent.document).html()=="\""){
					var preencheu = true;
					$("#juros input").each(function(){
						if($(this).val()==""){
							$(this).focus();
							preencheu = false;
							return false;
						}
					});
					if($("#txtmontante").val()!=""&&$("#txtmontante").val()!="?"&&$("#txtcapital").val()!=""&&$("#txtcapital").val()!="?"){
						if(Number($("#txtmontante").val())<Number($("#txtcapital").val())){
							$("#txtmontante").focus().select();
							$(".aviso").fadeIn();
							preencheu = false;
							return false;
						}
					}
					if(preencheu){
						$("#ex",parent.document).html("\""+$("#txtcapital").val()+";"+$("#txtmontante").val()+";"+$("#txtjuros").val()+";"+$("#txttaxa").val()+$("#cmptaxat").val()+";"+$("#txttempo").val()+$("#cmptempot").val()+"|["+$("#jtipo").val()+"]\"");
					}
				}
			}
			if(window.parent.statusMcc){
				window.parent.calcular();
				switch (statusMcc){
					case listaMcc.rgr3sp:
						$("#ex",parent.document).html("Regra de 3 simples");
					break;
					case listaMcc.juros:
						$("#ex",parent.document).html("Juros");
					break;
				}
			}
		}
		return false;
	});
});
var statusMcc = ""; // Estado atual (calculo atual)
var listaMcc = { // Lista de calculos do mais calculos
	rgr3sp: 0, // Regra de 3 simples
	juros: 1 // Juros
};