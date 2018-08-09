var horas = 0;
var minutos = 0;
var segundos = 0;
var ra;
var H = 0;
var M = 0;
var S = 0;

function setTempo(h,m,s){
	horas = h;
	minutos = m;
	segundos = s;
	contaTempo();
	setInterval(contaTempo,1000);
	if(horas==0&&minutos==0&&segundos==0){
		conta();
		setInterval(conta,1000);
	}else{
		ampulheta();
		ra = setInterval(ampulheta,1000);
	}
}

function conta(){
	segundos++;
	if(segundos==60){
		minutos++;
		segundos = 0;
	}
	if(minutos==60){
		horas++;
		minutos = 0;
	}
	var zh = "";
	var zm = "";
	var zs = "";
	if(horas<10){
		zh = "0";
	}else{
		zh = "";
	}
	if(minutos<10){
		zm = "0";
	}else{
		zm = "";
	}
	if(segundos<10){
		zs = "0";
	}else{
		zs = "";
	}
	$("#cabExe span").text(zh+horas+":"+zm+minutos+":"+zs+segundos);
}

function contaTempo(){
	S++;
	if(S==60){
		M++;
		S = 0;
	}
	if(M==60){
		H++;
		M = 0;
	}
	var zh = "";
	var zm = "";
	var zs = "";
	if(H<10){
		zh = "0";
	}else{
		zh = "";
	}
	if(M<10){
		zm = "0";
	}else{
		zm = "";
	}
	if(S<10){
		zs = "0";
	}else{
		zs = "";
	}
	$("#tm").val(zh+H+":"+zm+M+":"+zs+S);
}

function ampulheta(){
	if(minutos==0){
		if(horas>0){
			horas--;
			minutos = 59;
			segundos = 60;
		}
	}
	if(segundos==0){
		minutos--;
		segundos = 60;
	}
	segundos--;
	var zh = "";
	var zm = "";
	var zs = "";
	if(horas<10){
		zh = "0";
	}else{
		zh = "";
	}
	if(minutos<10){
		zm = "0";
	}else{
		zm = "";
	}
	if(segundos<10){
		zs = "0";
	}else{
		zs = "";
	}
	var t = zh+horas+":"+zm+minutos+":"+zs+segundos;
	$("#cabExe span").text(t);
	if(horas==0&&minutos==0&&segundos==0){
		clearInterval(ra);
		document.getElementById("formCorrige").submit();
	}
}