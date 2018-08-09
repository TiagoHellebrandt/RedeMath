// Declaração de constante
//define("CM",37.795275591);
const CM = 37.795275591;
// Declaração de variaveis
var stage,COR="#fff",LARGURA=3000,ALTURA=3000,ctx,linhas=[],press=false,itens,la=0,
	fundo = {
		x:0,
		y:0,
		largura: LARGURA,
		altura: ALTURA,
		desenha: function(){
			ctx.beginPath();
			ctx.fillStyle = COR;
			ctx.fillRect(this.x,this.y,this.largura,this.altura);
			ctx.stroke();
		}
	},
	grade = {
		cor: "#888",// Cor da linhas da grade
		distancia: 60,// Distancia em px entre as linhas da grade
		largura: 0.3,
		x: 0,
		y: 0,
		desenha: function(){
			while(this.y<=ALTURA){
				this.y += this.distancia;
				ctx.beginPath();
				ctx.strokeStyle = this.cor;
				ctx.lineWidth = this.largura;
				ctx.moveTo(0,this.y);
				ctx.lineTo(LARGURA,this.y);
				ctx.stroke();
			}
			if(this.y>ALTURA){
				this.y = 0;
			}
			while(this.x<=LARGURA){
				this.x += this.distancia;
				ctx.beginPath();
				ctx.strokeStyle = this.cor;
				ctx.lineWidth = this.largura;
				ctx.moveTo(this.x,0);
				ctx.lineTo(this.x,ALTURA);
				ctx.stroke();
			}
			if(this.x>LARGURA){
				this.x = 0;
			}
		}
	},
	tool = {
		status: 0,
		PAN: 0,
		LINE: 1,
		NONE: 2,
		setPan: function(){
			this.status=this.PAN;
			stage.style.cursor="url(../_imagens/tool-pan.png),move";
			$("#stage").draggable({
				stop:function(){
					if(stage.offsetLeft>=0){
						$("#stage").offset({left:0});
					}
					if(stage.offsetLeft+LARGURA<=$(window).width()){
						$("#stage").offset({left:$(window).width()-LARGURA});
					}
					if(stage.offsetTop>=0){
						$("#stage").offset({top:0});
					}
					if(stage.offsetTop+ALTURA<=$(window).height()){
						$("#stage").offset({top:$(window).height()-ALTURA});
					}
				}
			});
		},
		setLine: function(){
			this.status=this.LINE;
			stage.style.cursor="crosshair";
			$("#stage").draggable("destroy");
		},
		setSelect: function(){
			if(itens.style.display=="none"){
				$("#itens").show();
			}else{
				$("#itens").hide();
			}
		},
		setRemove: function(event){
			for(var i=0;i<linhas.length;i++){
				if(linhas[i].status){
					linhas.splice(i,1);
					i--;
				}
			}
			la = 0;
			for(var i=0;i<linhas.length;i++){
				linhas[i].setNome("Reta "+letras[la]);
				la++;
			}
			atualizaItens(event);
			atualiza(event);
			connect.status=false;
			if(linhas.length==0){
				$("#itens").hide();
			}
		},
		setNone: function(){
			stage.style.cursor="no-drop";
			this.status = this.NONE;
		}
	},
	connect = {
		x: 0,
		y: 0,
		status: false,
		raio: 5,
		setPosition: function(x,y){
			this.x = x;
			this.y = y;
		},
		desenha: function(){
			ctx.beginPath();
			ctx.strokeStyle = "#000";
			ctx.fillStyle = "#fff";
			ctx.arc(this.x,this.y,this.raio,0,2*Math.PI);
			ctx.fill();
			ctx.stroke();
		}
	};

// Após carregar o DOM
$(document).ready(function(event){
	configStage();
	atualiza(event);
	document.getElementById("toolPan").addEventListener("click",function(){tool.setPan();});
	document.getElementById("toolLine").addEventListener("click",function(){tool.setLine();});
	document.getElementById("toolSelect").addEventListener("click",function(){tool.setSelect();});
	document.getElementById("toolRemove").addEventListener("click",function(event){tool.setRemove(event);});
	$("#toolPan").trigger("click");
	configTools();
});

function configTools(){// Configura as ferramentas
	itens = document.createElement("ul");
	itens.id = "itens";
	document.querySelector("#cabExe").appendChild(itens);
}

function configStage(){// Configura o palco
	stage = document.createElement("canvas");
	stage.id = "stage";
	stage.width = LARGURA;
	stage.height = ALTURA;
	stage.style.background = COR;
	stage.style.border = "1px solid #000";
	ctx=stage.getContext("2d");
	document.getElementById("palco").appendChild(stage);
	stage.addEventListener("mousemove",function(event){atualiza(event);});
	stage.addEventListener("mousedown",function(event){press=true;$("#itens").hide();alterTool(event);});
	stage.addEventListener("mouseup",function(event){end(event);});
}

function alterTool(event){// Altera a ferramenta
	if(press){
		if(tool.status==tool.PAN){
			stage.style.cursor="url(../_imagens/tool-panActive.png),move";
		}else if(tool.status==tool.LINE){
			novaLinha(event);
		}
	}
}

function Linha(){
	this.nome;
	this.cor="#000";
	this.largura=1;
	this.beginX;
	this.beginY;
	this.endX;
	this.endY;
	this.status=false;
	this.spaceConnect = 20;
	this.ready=false;
	this.length;
	this.getLength = function(){
		return Math.sqrt(Math.pow(this.beginY-this.endY,2)+Math.pow(this.beginX-this.endX,2));
	}
	this.setBeginLine = function(x,y){
		this.beginX = x;
		this.beginY = y;
	};
	this.setEndLine = function(x,y){
		this.endX = x;
		this.endY = y;
	};
	this.setNome = function(nome){
		this.nome = nome;
	}
	this.getNome = function(){
		return this.nome;
	};
	this.select = function(){
		this.status = true;
		this.cor = "#00a2ff";
		this.largura = 2;
	};
	this.deselect = function(){
		this.status = false;
		this.cor = "#000";
		this.largura = 1;
	};
	this.desenha = function(){
		ctx.beginPath();
		ctx.lineWidth = this.largura;
		ctx.strokeStyle = this.cor;
		ctx.moveTo(this.beginX,this.beginY);
		ctx.lineTo(this.endX,this.endY);
		ctx.stroke();
	};
	this.connection = function(event){
		if(this.ready){
			if(this.beginX-this.spaceConnect<getPosition(event).x&&this.beginX+this.spaceConnect>getPosition(event).x&&this.beginY-this.spaceConnect<getPosition(event).y&&this.beginY+this.spaceConnect>getPosition(event).y){
				connect.setPosition(this.beginX,this.beginY);
				connect.desenha();
				connect.status = true;
			}else if(this.endX-this.spaceConnect<getPosition(event).x&&this.endX+this.spaceConnect>getPosition(event).x&&this.endY-this.spaceConnect<getPosition(event).y&&this.endY+this.spaceConnect>getPosition(event).y){
				connect.setPosition(this.endX,this.endY);
				connect.desenha();
				connect.status = true;
			}else{
				connect.status = false;
			}
		}else{
			connect.status = false;
		}
		return connect.status;
	};
}

function atualiza(event){// Atualiza todos elementos no palco
	if(press){
		if(tool.status==tool.PAN){
			
		}else if(tool.status==tool.LINE){
			if(!linhas[linhas.length-1].ready){
				linhas[linhas.length-1].setEndLine(getPosition(event).x,getPosition(event).y);
			}
		}
	}else{
		if(tool.status==tool.PAN){
			stage.style.cursor="url(../_imagens/tool-pan.png),move";
		}else if(tool.status==tool.LINE){

		}
	}
	desenha();
	if(tool.status==tool.LINE){
		for(var i=0;i<linhas.length;i++){
			if(linhas[i].connection(event)){
				break;
			}
		}
	}
}

function desenha(){// Desenha todos elementos no palco
	fundo.desenha();// Desenha o fundo
	grade.desenha();// Desenha a grade
	for(var i=0;i<linhas.length;i++){// Desenha todas as linhas
		linhas[i].desenha();
	}
}

function novaLinha(event){// Cria uma nova linha
	linhas.push(new Linha());
	linhas[linhas.length-1].setNome("Reta "+letras[la]);
	la++;
	if(!connect.status){
		linhas[linhas.length-1].setBeginLine(getPosition(event).x,getPosition(event).y);
	}else{
		linhas[linhas.length-1].setBeginLine(connect.x,connect.y);
	}
	atualizaItens(event);
	atualiza(event);
}

function getPosition(event){// Retorna as coordenadar do mouse
	return {x:(stage.offsetLeft*(-1))+event.clientX,y:(stage.offsetTop*(-1))+event.clientY};
}

function atualizaItens(event){
	$("#itens li").remove();
	for(var i=0;i<linhas.length;i++){
		$("#itens").append("<li><table><tr><td></td><td>"+linhas[i].getNome()+"</td></tr></table></li>");
	}
	$("#itens li").unbind();
	$("#itens li").bind("click",function(){
		for(var i=0;i<linhas.length;i++){
			if($(this).text()==linhas[i].getNome()){
				if(!linhas[i].status){
					linhas[i].select();
					$(this).find("td:first-child").css("background-image","url(../_imagens/checkbox1.png)");
				}else{
					linhas[i].deselect();
					$(this).find("td:first-child").css("background-image","url(../_imagens/checkbox0.png)");
				}
			}
		}
		atualiza(event);
	});
	atualiza(event);
}

function end(event){// Quando o clicque do mouse é solto
	if(tool.status==tool.LINE){
		if(connect.status){
			linhas[linhas.length-1].setEndLine(connect.x,connect.y);
		}
		linhas[linhas.length-1].ready=true;
		for(var i=0;i<linhas.length;i++){
			if(linhas[i].beginX==linhas[i].endX&&linhas[i].beginY==linhas[i].endY){
				linhas.splice(i,1);
				i--;
			}
		}
		atualizaItens(event);
		atualiza(event);
	}
	press=false;
	tool.setNone();
	identifyShape();
}

function connection(reta,x,y,connected){
	this.reta=reta;
	this.x=x;
	this.y=y;
	this.connected=connected;
}

function getConnections(){
	var connections = [];
	for(var i=0;i<linhas.length;i++){
		for(var j=0;j<linhas.length;j++){
			if(linhas[i].getNome()==linhas[j].getNome()){continue;}
			if(searchObjectAttr(connections,"reta",j)){continue;}
			if(linhas[i].beginX==linhas[j].beginX&&linhas[i].beginY==linhas[j].beginY){
				connections.push(new connection(i,linhas[i].beginX,linhas[i].beginY,j));
			}
			if(linhas[i].beginX==linhas[j].endX&&linhas[i].beginY==linhas[j].endY){
				connections.push(new connection(i,linhas[i].beginX,linhas[i].beginY,j));
			}
			if(linhas[i].endX==linhas[j].beginX&&linhas[i].endY==linhas[j].beginY){
				connections.push(new connection(i,linhas[i].endX,linhas[i].endY,j));
			}
			if(linhas[i].endX==linhas[j].endX&&linhas[i].endY==linhas[j].endY){
				connections.push(new connection(i,linhas[i].endX,linhas[i].endY,j));
			}
		}
	}
	return connections;
}

function searchObjectAttr(array,attr,valor){// Pesquisa dentro de um array de OBJ iguais um valor em um ATTR
	for(var i=0;i<array.length;i++){
		if(eval("array[i]."+attr+"=="+valor)){
			return true;
		}
	}
	return false;
}

function isConnected(r1,r2){// Está conectado?
	var connections = getConnections();
	for(var i=0;i<connections.length;i++){
		if((connections[i].reta==r1&&connections[i].connected==r2)||(connections[i].reta==r2&&connections[i].connected==r1)){
			return true;
		}
	}
	return false;
}

function identifyShape(){// Identificar forma
	isTriangle();
}

function isTriangle(){// É triangulo?
	if(linhas.length==3&&isConnected(0,1)&&isConnected(0,2)&&isConnected(1,2)){
		console.log("É um triangulo!");

	}
}