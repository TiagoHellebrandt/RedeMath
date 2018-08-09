$(document).ready(function(){
    document.execCommand("enableObjectResizing", false, "false");
    document.execCommand("enableInlineTableEditing", false, "false");
    $("#pgt-mmc-mdc input[value='mmc']").click(function(){
        blqsubmit = false;
        $("#calcula").submit();
        $("#pgt-mmc-mdc").fadeOut();
    });
    // Botao mais calculos
    $("#btnmcc").click(function(){
        if($("#ex").html()==""){
            $("#ex").html("\"");
        }
        if(statusTeclado){
            cacheTeclado = true;
        }
        $("#mcc").show().removeClass().addClass("topHeightPlus");
        fecharTeclado();
        $("#ex").attr("readonly","true");
        statusMcc = true;
    });
    // Inicio do teclado
    $("#teclado").offset({
        left: function(){return $(window).width()/2-$(this).innerWidth()/2;},
        top: function(){return $(window).height()/2-$(this).innerHeight()/2;}
    });
    $("#pgt-mmc-mdc input[value='mdc']").click(function(){
        $("#ex").html($("#ex").html()+"|");
        blqsubmit = false;
        $("#calcula").submit();
        $("#pgt-mmc-mdc").fadeOut();
    });
    $("#infoEx").hide();
    $("#ex").focus(function(){
        if(!infoExStatus){
            $("#infoEx").show();
            infoExStatus=true
        }
        if($("#resultado").length){
            hideCalc();
        }
    });

    $("#calcula").submit(function(){
        if(!statusMcc){
            calcular();
        }
        return false;
    }).click(function(){
        $("#ex").focus();
    }).hover(function(){formBorda(focoEx,true);},function(){formBorda(focoEx,false);});
    $("#ex")
        .focus(function(){abrirBtnsbm();focoEx=true;})
        .blur(function(){fecharBtnsbm();focoEx=false;});
    $("#btntcd").hover(function(){
        if(!statusTeclado){
            $(this).attr("src","../_imagens/tecladoIcone-RedeMath-hoverHide.png");
        }else{
            $(this).attr("src","../_imagens/tecladoIcone-RedeMath-hoverShow.png");
        }
    },function(){
        if(!statusTeclado){
            $(this).attr("src","../_imagens/tecladoIcone-RedeMath-hide.png");
        }else{
            $(this).attr("src","../_imagens/tecladoIcone-RedeMath-show.png");
        }
    }).click(function(){
        if(!statusTeclado){
            abrirTeclado();
        }else{
            fecharTeclado();
        }
    });
    $("#btnmcc").hover(function(){
        $(this).attr("src","../_imagens/maiscalculos-over.png");
    },function(){
        $(this).attr("src","../_imagens/maiscalculos.png");
    });
    var infoExStatus = false;
    var miniTeclado = false;

    $(window).load(resizeWindow);
    $(window).resize(resizeWindow);
    function resizeWindow(){
        $("#areaCalc").css("margin-left",function(){return (($("#areaCalc").innerWidth()/2)*(-1))+"px";});
        $("#logo").css("margin-left",function(){return ($("#logo").width()/2)*(-1)+"px";});
        $("#logo").css("background-size",function(){return $("#logo").width()+"px";});
        $("#btnsbm").css("margin-left",function(){return (($("#calcula").width()/2)-46)+"px";});
        $("#pgt-mmc-mdc").css("margin-left",function(){return (($("#calcula").width()/2)-50)+"px";});
        $("#mcc").width(($("#calcula").width()/2)+parseInt($("#btnsbm").css("margin-left"))-5);
        $("#mcc").css("margin-left",function(){return (($("#calcula").innerWidth()/2)*(-1))+"px";});
        if($(window).width()<450){
            $("#teclado").css({
                "background-image":"none",
                "top":"50%",
                "left":"50%",
                "margin-top":"55px",
                "margin-left":"-152px",
                "width":"290px",
                "height":"190px"
            }).draggable("disable");
            $("#btntcd").hide();
            $("#ex").width($("#areaCalc").width()-130);
            $("#cabTecla").hide();
            $("#teclado table").css({"margin-top":"0px","border-spacing":"0px"});
            if($("#btnsbm").css("display")=="none"){
                $("#btnmcc").css("margin-left",function(){return (($("#areaCalc").width()/2)-46)+"px";});
            }else{
                $("#btnmcc").css("margin-left",function(){return (($("#areaCalc").width()/2)-86)+"px";});
            }
            if(!statusTeclado&&!miniTeclado&&!$("#resultado").length){
                abrirTeclado();
                miniTeclado = true;
            }
        }else{
            if($("#btnsbm").css("display")=="none"){
                $("#btnmcc").css("margin-left",(($("#areaCalc").width()/2)-79-11)+"px");
            }else{
                $("#btnmcc").css("margin-left",(($("#areaCalc").width()/2)-135)+"px");
            }
            $("#teclado button:hover").css("box-shadow","-1px 2px 10px rgba(0,0,0,0.5)");
            if(!$("#resultado").length){
                $("#btntcd").show();
            }
            if($("#ex").html()!=""){
                $("#btntcd").css("margin-left","125px");
            }else{
                $("#btntcd").css("margin-left","170px");
            }
            if(!statusTeclado&&miniTeclado){
                fecharTeclado();
                miniTeclado = false;
            }
            fronteiraTeclado();
            $("#cabTecla").show();
            $("#teclado table").css({"margin-top":"30px","border-spacing":"2px"});
            $("#teclado").css({
                "background-image":"url('../_imagens/titulo-teclado.png')",
                "width":"308px",
                "height":"235px",
            }).draggable("enable");
        }
    }

    var posisaoX = "";
    var posisaoY = "";
    $("#teclado").draggable({
        drag: function(event){
            posisaoX = $("#teclado").offset().left;
            posisaoY = $("#teclado").offset().top;
        },
        stop: fronteiraTeclado
    });
    $(".btnst").hover(function(){
        $(this).css({
            "margin-left":parseInt($(this).css("margin-left"))+4+"px",
            "width":parseInt($(this).css("width"))+3+"px",
            "margin-top":parseInt($(this).css("margin-top"))-1+"px"
        });
    },function(){
        $(this).css({
            "margin-left":function(){return (($("#areaCalc").width()/2)-46)+"px";},
            "width":"30px",
            "margin-top":"-15px"
        });
    }).focus(function(){$(this).trigger("hover")});

    setInterval(function(){
        $("#btnmcc")
            .addClass("animated swing")
            .one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){
                $(this)
                    .removeClass("animated swing")
                    .off("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend");
            });
    },2000);
});
var blqsubmit = true;// bloqueio para o submit
var statusTeclado = false;
var cacheTeclado = false;
function atencaoBtntcd(){
    $("#btntcd")
        .addClass("animated swing")
        .one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){
            $(this)
                .removeClass("animated swing")
                .off("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend");
        });
}

var btntcdIntervalo = setInterval(atencaoBtntcd,2500);

function showBtns(){
    $("#ex").removeAttr("readonly");
    $("#btnsbm").show();
    $("#btnmcc").show();
    $("#btntcd").show();
    $("#calcula").css({
        "background-image":"none",
        "cursor":"auto"
    });
    $("#ex").css({
        "cursor":"auto"
    }).select();
}

function hideBtns(){
    $("#ex").attr("readonly","readonly");
    $("#btnsbm").hide();
    $("#btnmcc").hide();
    $("#btntcd").hide();
    $("#calcula").css({
        "background-image":"url(../_imagens/fecharIcon.png)",
        "cursor":"pointer"
    });
    $("#ex").css({
        "cursor":"pointer"
    });
}


function abrirTeclado(){
    statusTeclado=true;
    $("#btntcd").attr("src","../_imagens/tecladoIcone-RedeMath-hoverShow.png");
    $("#teclado")
        .css("-webkit-animation-duration","1s")
        .css("-moz-animation-duration","1s")
        .show()
        .removeClass()
        .addClass("animated bounceIn")
        .one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){
            $(this).removeClass();
        });
    clearInterval(btntcdIntervalo);
}

function fecharTeclado(){
    statusTeclado=false;
    $("#btntcd").attr("src","../_imagens/tecladoIcone-RedeMath-hoverHide.png");
    $("#teclado")
        .css("-webkit-animation-duration","0.4s")
        .css("-moz-animation-duration","0.4s")
        .removeClass().addClass("animated bounceOut").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){
            if(!statusTeclado){
                $(this).hide();
            }
        });
    clearInterval(btntcdIntervalo);
    btntcdIntervalo = setInterval(atencaoBtntcd,2500);
}

var focoEx = false;
function btnsEx(){
    if($("#ex").html()!=""){
        if(!$("#resultado").length){
            $("#btnsbm").show();
        }else{
            $("#btnsbm").hide();
        }
        $("#btntcd").css("margin-left","125px");
        if($(window).width()>=450){
            $("#btnmcc").css("margin-left",(($("#areaCalc").width()/2)-135)+"px");
        }else{
            $("#btnmcc").css("margin-left",function(){return (($("#areaCalc").width()/2)-86)+"px";});
        }
        if($(window).width()>=450){
            $("#ex").width(280);
        }
    }else{
        $("#btnsbm").hide();
        $("#btntcd").css("margin-left",(($("#areaCalc").width()/2)-44-11)+"px");
        if($(window).width()>=450){
            $("#btnmcc").css("margin-left",(($("#areaCalc").width()/2)-79-11)+"px");
        }else{
            $("#btnmcc").css("margin-left",function(){return (($("#areaCalc").width()/2)-46)+"px";});
        }
        if($(window).width()>=450){
            $("#ex").width(325);
        }
    }
}

function abrirBtnsbm(){
    btnsEx();
}
function fecharBtnsbm(){
    btnsEx();
}

function validaEx(ex,id){
    if((ex.indexOf('+')>=0||ex.indexOf('-')>=0||ex.indexOf('*')>=0||ex.indexOf('/')>=0||ex.indexOf('÷')||ex.indexOf(':')||ex.indexOf('×')||ex.indexOf('[')||ex.indexOf('{')||ex.indexOf('–')||ex.indexOf('('))&&ex.length>=3&&!(apenasNumeros(ex)==""&&apenasLetras(ex)=="")){
        return true;
    }else{
        document.getElementById(id).select();
        return false;
    }
}

function formBorda(focoEx,ad){
    if((!focoEx)&&(ad)){
        $("#calcula").css("box-shadow","0 0 10px rgba(0,0,0,0.3)");
    }
    if((!focoEx)&&(!ad)){
        $("#calcula").css("box-shadow","0 0 10px rgba(0,0,0,0.2)");
    }
}

var contaParentese = 0;

function preenche(id){
    var ex = document.getElementById("ex");
    var el = document.getElementById(id);
    var valor = el.innerHTML;
    if(valor=="()"){
        if(contaParentese%2==0){
            valor = "(";
        }else{
            valor = ")";
        }
        contaParentese++;
    }
    if(valor=="÷"){
        valor = "/";
    }
    if(valor=="x<sup>2</sup>"){
        if(ex.innerHTML!=""){
            var texto = ex.innerHTML.split("");
            var ue = "";// Ultimo elememto
            for(i=texto.length-1;i>=0;i--){
                if(texto[i]==")"&&i==texto.length-1){
                    i--;
                }
                if(texto[texto.length-1]==")"){
                    if(texto[i]!="("){
                        ue = texto[i] + ue;
                    }else{
                        break;
                    }
                }else{
                    if(texto[i]!="+"&&texto[i]!="-"&&texto[i]!="*"&&texto[i]!="/"&&texto[i]!="="&&texto[i]!="("&&texto[i]!=")"){
                        ue = texto[i] + ue;
                    }else{
                        if(texto[i]=="-"){
                            ue = texto[i] + ue;
                        }
                        break;
                    }
                }
            }
            if(ue!=""){
                valor = "*("+ue+")";
            }else{
                valor = "";
            }
        }else{
            valor = "";
        }
    }
    if(valor=="←"){
        var texto = ex.innerHTML.split("");
        texto.pop();
        var v = "";
        for(i=0;i<texto.length;i++){
            v += texto[i];
        }
        valor = v;
        ex.innerHTML = "";
    }
    ex.innerHTML += valor;
    btnsEx();
    $("#pgt-mmc-mdc").fadeOut();
}

function fronteiraTeclado(){
	var e = $("#teclado");
    var posisaoX = e.offset().left;
    var posisaoY = e.offset().top;
	if(posisaoX<0){
        e.offset({left:0});
    }
    if(posisaoX+e.innerWidth()>$(window).width()){
        e.offset({left: $(window).width()-e.innerWidth()});
    }
    if(posisaoY+e.innerHeight()>$(window).height()){
        e.offset({top: $(window).height()-e.innerHeight()});
    }
    if(posisaoY<0){
        e.offset({top: 0});
    }
}

function dwEx(){
    //$("#ex").focus();
}

function upEx(){
    abrirBtnsbm();
}

function hideCalc(){
    $("#areaCalc").css("top","50%");
    $("#novidade").remove();
    $("#resultado").remove();
    $("#backgroundRes").removeClass().addClass("outBgRes").height(0);
    $("#logo").css("background-image","url(../_imagens/LogoRedeMath.png)");
    $("#menu img").attr("src","../_imagens/menu-icone.png");
    $("#menu img").unbind("mouseover");
    $("#menu img").unbind("mouseout");
    $("#menu img")
        .bind("mouseover",function(){$(this).css("background-color","rgba(0,162,255,0.2)");})
        .bind("mouseout",function(){$(this).css("background-color","rgba(0,0,0,0)");});
    showBtns();
    $("#fblike").fadeOut();
    if(statusMcc){
        $("#mcc").fadeIn();
    }
}

function calcular(){
    if($("#ex").html()!=""){
        if($("#ex").html().search(";")>0&&$("#ex").html().search("\"")<0){
            $("#pgt-mmc-mdc").fadeIn();
            $("#ex").blur();
            $("#ex").focus(function(){
                $("#pgt-mmc-mdc").fadeOut();
            });
            if(blqsubmit){
                return false;
            }else{
                blqsubmit = true;
            }
        }
        $("#areaCalc").offset({top:(50+$("#logo").height())});
        $("#backgroundRes").removeClass().addClass("inBgRes").height(210);
        $("#menu img").attr("src","../_imagens/menu-icone-branco.png");
        $("#logo").css("background-image","url(../_imagens/LogoRedeMathBranco.png");
        $("#menu img")
            .bind("mouseover",function(){$(this).css("background-color","#4cbdff");})
            .bind("mouseout",function(){$(this).css("background-color","rgba(0,0,0,0)");});
        if(statusTeclado){
            fecharTeclado();
        }
        $.ajax({
            url: "../resultado/resultado.php",
            dataType: "html",
            method: "GET",
            data: {ex:$("#ex").html()},
            beforeSend: function(){
                if($("#resultado").length){
                    $("#resultado").remove();
                }
                hideBtns();
                $("#ex").blur();
                $("#loading").show();
            },
            fail: function(){
                console.log("erro");
            },
            success: function(e){
                $("#loading").hide();
                if(statusMcc){
                    $("#mcc").fadeOut();
                }
                $("body").append(e);
                $("#fblike").fadeIn();
                $("#resultado").before("<iframe id='novidade' src='../novidade.html' style='position: relative;margin-left: -256px;margin-top: 300px;margin-bottom: 50px;left: 50%;height: 279px;width: 512px;border: none;box-shadow: 0 0 50px rgba(0,0,0,0.5);'></iframe>");
                $("#resultado")
                    .hide()
                    .offset({top:0})
                    .fadeIn();
                onResultados();
                $("#btnsbm").hide();
                infoStep = new InfoStep();
                $("#infosteps").prepend("<h1></h1>");
                $("#infosteps").append("<button id='proximo'>Proximo</button>");
                $("#infosteps").append("<button id='anterior'>Anterior</button>");
                $("#proximo").click(function(){infoStep.proximo();});
                $("#anterior").click(function(){infoStep.anterior();});
                $("#btnInfo").hover(function(){
                    $(this).attr("src","../_imagens/infoIconHover.png");
                },function(){
                    $(this).attr("src","../_imagens/infoIcon.png");
                }).click(function(){
                    infoStep.toggle();
                });
                if ($(".steps").length>0) {
                    $("#btnInfo").show();
                }
            }
        });
        $("#infoEx").hide();
        abrirBtnsbm();
    }else{
        $("#ex").focus();
    }
    return false;
}

var statusMcc = false;

function setStatusMcc(value){
    statusMcc = value;
}

var infoStep;

function InfoStep(){
    this.passo = 1;
    var status = false;
    this.toggle = function(){
        if(status){
            this.stop();
        }else{
            this.atualiza();
        }
    };
    this.stop = function(){
        $("#infosteps div").hide();
        $(".steps").removeClass("myStep");
        $("#infosteps").hide();
        status = false;
    };
    this.atualiza = function(){
        $("#infosteps div").hide();
        $(".infostep"+this.passo).show();
        $(".steps").removeClass("myStep");
        $(".step"+this.passo).addClass("myStep");
        $("#infosteps h1").text("Passo "+this.passo);
        $("#infosteps").show();
        $(window).scrollTop($(".step"+this.passo).scrollTop()+$(window).height()/2);
        status = true;
    };
    this.proximo = function(){
        if($("#infosteps div").length>this.passo){
            this.passo++;
        }
        this.atualiza();
    };
    this.anterior = function(){
        if(this.passo>1){
            this.passo--;
        }
        this.atualiza();
    };
};
