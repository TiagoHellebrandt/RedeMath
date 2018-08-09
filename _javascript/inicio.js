$(document).ready(function(){
	// $(window).scroll(function(){
	// 	if($(this).scrollTop()>10) { 
	// 		$("#cabecalho").css({
	// 			"background-color":"#fff",
	// 			"height":"100px",
	// 			"box-shadow":"-5px 3px 25px rgba(1,1,1,0.6)"
	// 		});
	// 	}else{ 
	// 		$("#cabecalho").css({
	// 			"background-color":"transparent",
	// 			"height":"150px",
	// 			"box-shadow":"none"
	// 		});
	// 	}
	// });
	// $(window).trigger("scroll");
	// $("#cabecalho").css("transition","1s");
	// $("#btn-estudar").click(function(){
	// 	abrirOp();
	// 	$("#orc").css("right","-25px");
	// });
	writing($("h2"),50,1600);
});

function writing(el,tm,dl) {
	var delay = setInterval(function(){
		var c = 0;
		var str = el.text();
		var repeat = setInterval(function(){
			el.text(str.substring(0,c));
			c++;
			if(c>str.length) {
				clearInterval(repeat);
			}
		},tm);
		clearInterval(delay);
	},dl);
}