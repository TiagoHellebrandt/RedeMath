function onResultados(){
    // $("#btn-err").click(function(){
    //     $("#bloco-err").toggle();
    // });
    // $("#cnl-err").click(function(){
    //     $("#bloco-err").hide();
    // });
    // $("#form-err").submit(function(){
    //     if($("#nm-err").val()!=""||$("#eml-err").val()!=""||$("#msg-err").val()!=""){
    //         if($("#nm-err").val()==""){
    //             $("#nm-err").focus();
    //             return false;
    //         }
    //         if($("#eml-err").val()==""){
    //             $("#eml-err").focus();
    //             return false;
    //         }else{
    //             if(!validacaoEmail(document.getElementById("eml-err"))){
    //                 $("#eml-err").focus().select().css("border-color","#f00");
    //                 $("#eml-inv-err").fadeIn();return false;
    //             }
    //         }

    //     }
    // });
    // $("#eml-err").keyup(function(){
    //     $("#eml-inv-err").fadeOut();
    // });
    var startLike = setInterval(function(){
        $(".fb-like").addClass("animated jello").on("webkitAnimationEnd MozAnimationEnd MSAnimationEnd oanimationend animationend",function(){
            $(this).removeClass("animated jello");
        });
    },1000);
// $(window).resize(redimensiona);
// $(window).load(redimensiona);
}
function validacaoEmail(field) { usuario = field.value.substring(0, field.value.indexOf("@")); dominio = field.value.substring(field.value.indexOf("@")+ 1, field.value.length); if ((usuario.length >=1) && (dominio.length >=3) && (usuario.search("@")==-1) && (dominio.search("@")==-1) && (usuario.search(" ")==-1) && (dominio.search(" ")==-1) && (dominio.search(".")!=-1) && (dominio.indexOf(".") >=1)&& (dominio.lastIndexOf(".") < dominio.length - 1)) {return true;} else{return false;} }
// function redimensiona(){
//     // if($(window).width()<450){
//     //     $("#curtiecomp").hide();
//     //     $("#curti").show();
//     //     // $("#btn-err").css("margin-left","30px");
//     // }else{
//     //     $("#curtiecomp").show();
//     //     $("#curti").hide();
//     //     // $("#btn-err").css("margin-left","60px");
//     // }
//     // $("#bloco-err").offset({left: $("#btn-err").offset().left});
// }