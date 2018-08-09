$(document).ready(function(){
    $("#menu img,#menu .iconMenu").attr("draggable", "false");
    $(".objTool").hover(function(){$("#tooltip").text($(this).attr("title")).show().offset({top:$(this).offset().top+$(this).outerHeight()+10,left:$(this).offset().left});},function(){$("#tooltip").text("").hide();})
        .click(function(){$("#tooltip").hide();});
    $("#menu img,#menu .iconMenu").click(abrirOp);
    $("#ntabm").change(function(){
        var n = $(this).val();
        if(n>10){
            $(this).val("10");
            n=10;
        }else if(n<1){
            $(this).val("1");
            n=1;
        }
        $("#ttab").html("");
        for(i=1;i<=10;i++){
            $("#ttab").html($("#ttab").html()+"<tr><td>"+n+" × "+i+" = "+(n*i)+"</td></tr>");
        }
    });
    $(window).resize(resizeLogo);
    resizeLogo();

    $("#blq").mousedown(function(){
        fecharOp();
    });
});

function resizeLogo(){
    if($(window).width()<450){
        $("#mylogo").width(90);
    }else{
        $("#mylogo").width(300);
    }
}

function exibir(e){
    document.getElementById(e).style.display="block";
}
function ocultar(e){
    document.getElementById(e).style.display="none";
}

function apenasNumeros(string){
    var numsStr = string.replace(/[^0-9]/g,'');
    return parseInt(numsStr);
}

function apenasLetras(string){
    return string.replace(/[^a-z]/g,'');
}

function abrirOp(){
    $("#orc").css("right","-305px");
    $("#blq").fadeIn(300);
}
function fecharOp(){
    $("#orc").css("right","-415px");
    $("#blq").fadeOut(300);
}