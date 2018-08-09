$(document).ready(function(){
        $(window).resize(function(){
            $("table").each(function(){
                $(this).css("width",$(this).width()+"px");
            });
        });
        $(window).trigger("resize");
        $("#respostas").hide();
        $("#tm").hide();
        $("#nv").hide();

        $(".questao:eq("+questao+")").show();
        if(questao==$(".questao").length-1){
                $("#avancar").val("Corrigir");
        }

        widthProgresso += progresso;
        $("#barraProgresso").width(widthProgresso+"%");

        $("#formCorrige").submit(function(){
            if(questao<$(".questao").length-1){
                questao++;
                $(".questao").hide();
                $(".questao:eq("+questao+")").show();
                $(".questao:eq("+questao+") input").focus();
                widthProgresso += progresso;
                $("#barraProgresso").width(widthProgresso+"%");
                if(questao==$(".questao").length-1){
	            	$("#barraProgresso").text("Quase lá!");
	                $("#avancar").val("Corrigir");
	            }
                return false;
            }else {
                $(this).submit();
            }
        });
        $(".questao li").click(function(){
        	unSelectOp();
        	selectOp($(this));
        });
});
		var questao = 0;
        var progresso = 0;
        var widthProgresso = 0;

function setProgresso(prg){
    progresso = prg;
}

function selectOp(el) {
	el.find("td:eq(0)").addClass("selectQuestao");
	el.find("input:radio").prop("checked", true);
}

function unSelectOp() {
	$(".questao *").find("td:eq(0)").removeClass("selectQuestao");
	$(".questao:eq("+questao+") input:radio").prop("checked", false);
}