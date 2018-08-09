$(document).ready(function(){
    $(".icone").addClass("animated flipInY");
    $(window).resize(function(){
        var quantidade = 0;
        while(($(".icone").outerWidth(true))*quantidade<=$(window).width()){
            quantidade++;
        }
        quantidade--;
        if($("#calculosIcn").width()>=$(window).width()){
            quantidade--;
        }
        $("#calculosIcn").width(($(".icone").outerWidth(true)+5)*quantidade);
    }).trigger("resize");

    $("#iconeSm").click(function(){$("#infoSm").fadeIn();});
    $("#iconeSb").click(function(){$("#infoSb").fadeIn();});
    $("#iconeMt").click(function(){$("#infoMt").fadeIn();});
    $("#iconeDv").click(function(){$("#infoDv").fadeIn();});
    $("#iconeEqc1").click(function(){$("#infoEqc1").fadeIn();});
    $("#iconeEqc2").click(function(){$("#infoEqc2").fadeIn();});
    $("#iconeMmc").click(function(){$("#infoMmc").fadeIn();});
    $("#iconeMdc").click(function(){$("#infoMdc").fadeIn();});
    $("#iconeExn").click(function(){$("#infoExn").fadeIn();});
    $("#iconeDt").click(function(){$("#infoDt").fadeIn();});
    $("#iconeRq").click(function(){$("#infoRq").fadeIn();});
    $("#iconeR3s").click(function(){$("#infoR3s").fadeIn();});
    $("#iconeJr").click(function(){$("#infoJuros").fadeIn();});
    $(".icone").click(function(){$("#sombraBloqueio").fadeIn();});
    $(".infoOp input[value='Fechar']").click(function(){$("#sombraBloqueio").fadeOut();$(".infoOp").fadeOut();});
    $("#sombraBloqueio").click(function(){$(this).fadeOut();$(".infoOp").fadeOut();});
});