<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; Charset=UTF-8">
	<style>
	.ch{
		background:#BCF5A9; 
		float:left; 
		width:100px; 
		height:50px; 
		border: 1px dashed #00FF00; 
		text-transform:uppercase; 
		text-align:center; 
		padding-top: 30px;
	}
	.uch{
		float:left; 
		width:100px; 
		height:50px; 
		border: 1px dashed #F2F2F2; 
		text-transform:uppercase; 
		text-align:center; 
		padding-top: 30px;
	}
	</style>
	<script>
	var fall = true,
	jumpon = false,
	jboom = false,
	jtimer,
	speed = 1;
	(function()
	{
		if (window.addEventListener) {
			if ('onwheel' in document)
				window.addEventListener("wheel", onWheel);
			else if ('onmousewheel' in document) 
				window.addEventListener("mousewheel", onWheel);
			else
				window.addEventListener("MozMousePixelScroll", onWheel);
			} 
			else 
				window.attachEvent("onmousewheel", onWheel);
		function onWheel(e) 
		{
			var delta = e.deltaY || e.detail;
			if (document.getElementById('ball'))
			{
				if (delta > 0) 
				{
					if (speed < 25)
					{
						if (speed < 2)
							speed+=(speed/2);
						else speed+=1;
					}
				}
				else if((speed - speed/2) > 0.2)
				{
					if (speed <= 2)
						speed -=(speed/2);
					else speed -= 1;
				}
				document.getElementById('speed').innerHTML = (speed*50)+' px/sec';
			}
		}
	})()
	function navigate(src){
		var dst=document.getElementById('scene');
		var req = newRequest();
		if (req)
		{
			req.open('get',src);
			req.onreadystatechange = function (){
				if (req.readyState == 4)
				{
					//alert(req.responseText);
					if (src == 'anim.html')
					{
						var x = document.getElementById('navia');
						if (!jumpon) 
						{
							dst.innerHTML = req.responseText;
							//alert(req.responseText+jumpon);
							jump(); 
							jumpon = true;
						}
					}
					else 
					{
						clearTimeout(jtimer); 
						jumpon = false;
						dst.innerHTML = req.responseText;
						if (src == 'list.php') 
						{
							var x = document.getElementById('navil');
							initList();
						}
						if (src == 'graf.php') 
						{
							var x = document.getElementById('navig');
							initGraf();
						}
					}
					var y = document.getElementsByClassName('ch');
					for (var i=0; i < y.length; i++)
					{
						var a = y[i].classList;
						a.remove('ch');
						a.add('uch');
					}
					x.classList.remove('uch');
					x.classList.add('ch');
				}
			}
			req.send(null);
		}
		else
		dst.innerHTML = '<p>Sorry, the connection failed</p>';
	}
	function jump(){
		var ball = document.getElementById('ball'),
		top = ball.getBoundingClientRect().top,
		bot = ball.getBoundingClientRect().bottom;
		if (fall)
		{
			if (bot < (document.documentElement.clientHeight))
				ball.style.top = (top+speed)+'px';
			else 
			{
				fall = !fall;
				jboom = true;
				ball.style.top = (document.documentElement.clientHeight-100)+'px';
				jtimer = setTimeout(boom,20);
			}
		}
		else
		{
			if (top > ((document.documentElement.clientHeight)/2))
				ball.style.top = (top-speed)+'px';
			else 
			{
				fall = !fall;
				ball.style.top = (top+speed)+'px';
			}
		}
		if (!jboom) jtimer = setTimeout(jump,20);
	}
	function boom(){
		var bal = document.getElementById('ball'),
		obj = document.getElementById('balli'),
		h = obj.style.height.split('px')[0],
		w = obj.style.width.split('px')[0],
		top = bal.getBoundingClientRect().top;
		if (!jboom)
		{
			if((Number(h) + speed)>= 100)
			{
				obj.style.height = '100px';
				obj.style.width = '100px';
				bal.style.width = '100px';
				jtimer = setTimeout(jump,20);
			}
			else 
			{
				obj.style.height = (Number(h) + speed) +'px';
				obj.style.width = (Number(w)-speed) + 'px';
				bal.style.width = (Number(w)-speed) + 'px';
				bal.style.top = (top-speed)+'px';
				jtimer = setTimeout(boom,20);
			}
		}
		else
		{
			obj.style.height = (h - speed) +'px';
			obj.style.width = (Number(w)+speed) + 'px';
			bal.style.width = (Number(w)+speed) + 'px';
			bal.style.top = (top+speed)+'px';
			if (h < 85) jboom = false;
			jtimer = setTimeout(boom,20);
		}
	}
	function newRequest(){
		try {return new XMLHttpRequest()}
		catch(exept)
		{
			try {return new ActiveXObject('Msxml2.XMLHTTP')}
			catch(exept)
			{
				try {return new ActiveXObject('Microsoft.XMLHTTP')}
				catch(exept) {return null;}
			}
		}
	}
	function initList(){
		var src = document.getElementById('list'),
		nodes = src.childNodes;
		window.listnodes = [];
		for (var i = 0; i<5; i++)
		{
			window.listnodes.push({
				id:nodes[i*2+1].firstChild.value,
				value:nodes[i*2+1].childNodes[1].value,
				number:i,
				object:nodes[i*2+1],
				up:nodes[i*2+1].getBoundingClientRect().top,
				down:nodes[i*2+1].getBoundingClientRect().bottom,
				left:nodes[i*2+1].getBoundingClientRect().left,
				dragable:false
			});
		}
		window.listnodes.forEach(
			function(item)
			{
				function sendListData(){
					var req = newRequest();
					if (req)
					{
						var json = JSON.stringify(window.listnodes);
						req.open("POST", 'test.php', true)
						req.setRequestHeader('Content-type', 'application/json; charset=utf-8');
						req.onreadystatechange = function() {};
						req.send(json);
					}
				}
				item.object.childNodes[1].onchange = function (e){
					item.value = this.value;
					sendListData();
				}
				item.object.onmousedown = function (e){
					item.dragable = true;
					window.listnodes.forEach(
						function(item){
							item.object.style.position = 'absolute';
							item.object.style.left = item.left+'px';
							item.object.style.top = item.up+'px';
						});
					moveobj(e);
					item.object.style.zIndex = 200;
					function moveobj(e){
						item.object.style.top = e.pageY - item.object.offsetHeight / 2 + 'px';
					}
					document.onmousemove = function(e) {
						moveobj(e);
						var t = item.up,
						b = item.down,
						a = item.object.style.top.split('px')[0],
						x = Number(a) + 15;
						if ((window.listnodes[item.number - 1])&&(x < window.listnodes[item.number - 1].down))
						{
							item.up = window.listnodes[item.number - 1].up;
							item.down = window.listnodes[item.number - 1].down;
							window.listnodes[item.number - 1].up = t;
							window.listnodes[item.number - 1].down = b;
							window.listnodes[item.number - 1].object.style.top = t+'px';
							var x = window.listnodes[item.number - 1];
							window.listnodes[item.number - 1] = item;
							window.listnodes[item.number] = x;
							item.number--;
							x.number++;
						}
						if ((window.listnodes[item.number + 1])&&(x > window.listnodes[item.number + 1].up))
						{
							item.up = window.listnodes[item.number + 1].up;
							item.down = window.listnodes[item.number + 1].down;
							window.listnodes[item.number + 1].up = t;
							window.listnodes[item.number + 1].down = b;
							window.listnodes[item.number + 1].object.style.top = t+'px';
							var x = window.listnodes[item.number + 1];
							window.listnodes[item.number + 1] = item;
							window.listnodes[item.number] = x;
							item.number++;
							x.number--;
						}
					}
					item.object.onmouseup = function() {
						sendListData();
						item.object.style.zIndex = 100;
						item.object.style.top = item.up+'px';
						document.onmousemove = null;
						item.object.onmouseup = null;
					}
				}
				item.object.ondragstart = function() {
					return false;
				}
			});
	}
	function initGraf(){
		var arr = document.getElementsByTagName('input'),
		str='',
		holst = document.getElementById('holst'),
		context = holst.getContext("2d");
		for(var i=0; i<arr.length; i++)
		{
			str += arr[i].value+" ";
		}
		function netgraf()
		{
			for (var x = 0.5; x < 261; x += 20) {
				context.moveTo(x, 0);
				context.lineTo(x, 300);
			}
			for (var y = 0.5; y < 301; y += 20) {
				context.moveTo(0, y);
				context.lineTo(260, y);
			}
			context.strokeStyle = "#f2f2f2";
			context.stroke();
			context.beginPath();
			context.moveTo(20.5, 0);
			context.lineTo(16.5, 8);
			context.moveTo(24.5, 8);
			context.lineTo(20.5, 0);
			context.lineTo(20.5, 300);
			context.moveTo(0, 280.5);
			context.lineTo(280, 280.5);
			context.lineTo(272, 276.5);
			context.moveTo(272, 284.5);
			context.lineTo(280, 280.5);
			context.strokeStyle = "#000000";
			context.stroke();
			context.font = "normal 10px sans-serif";
			for (var i = 1; i < 14; i++)
			{
				var x = 12,
				y = 300 - 20 - 20*i;
				if (i > 9) x = 6;
				context.fillText(i, x, y);
			}
			for (var i = 1; i < 13; i++)
			{
				var y = 290,
				x = 20*i+20;
				context.fillText(i, x, y);
			}
		}
		function linegraf()
		{
			holst.width = holst.width;
			netgraf();
			context.beginPath();
			context.moveTo(20.5, (280 - arr[0].value*20));
			context.fillText(arr[0].value,21,(280 - arr[0].value*20 + 11));
			for (var i = 1; i < arr.length; i++)
			{
				var x = 20*i + 20,
				y = 280 - arr[i].value*20;
				context.lineTo(x,y);
			}
			context.strokeStyle = "#ff0000";
			context.stroke();
			for (var i = 1; i < arr.length; i++)
			{
				var a = Number(arr[i-1].value),
				b = Number(arr[i].value),
				x = 20*i + 20,
				y = 280 - arr[i].value*20;
				if(i != 11) var c = Number(arr[i+1].value);
				//context.fillRect(x-1,y-1,4,4);
				if (a > b)
				{
					if(i != 11)
					{
						if (b > c)
						{
							x++;
							y--;
						}
						else
						{
							x -= 2;
							y += 11;
						}
					}
					else y += 11;
				}
				else
				{
					y--;
					if(i != 11)
					{
						if (b > c)
							x -= 6;
						else
							x -= 11;
					}
				}
				context.fillText(b,x,y);
			}
		}
		function histograf()
		{
			holst.width = holst.width;
			netgraf();
			for (var i = 0; i < arr.length; i++)
			{
				var x = 20*i + 20,
				y = 280 - arr[i].value*20;
				if (i%2 == 0)
					context.fillStyle = "#C8FE2E";
				else context.fillStyle = "#2EFE2E";
				context.fillRect(x+1,y,20,(arr[i].value*20 - 1));
				context.fillStyle = "#000000";
				if (arr[i].value <10)
					x+=4;
				context.fillText(arr[i].value,x+4,y-2);
			}
		}
		histograf();
		document.getElementById('line').onclick = function() {linegraf();}
		document.getElementById('histo').onclick = function() {histograf();}
	}
	window.onload = function () {
		navigate('list.php');
	}
	document.onkeydown = function checkKeycode(event)
	{
		var keycode;
		if(!event) var event = window.event;
		if (event.keyCode) keycode = event.keyCode; // IE
		else if(event.which) keycode = event.which; // all browsers
		var down='',
		up ='';
		if (document.getElementById('ball'))
		{
			down = 'graf.php';
			up = 'list.php';
		}
		else if(document.getElementById('holst'))
		{
			down = 'list.php';
			up = 'anim.html';
		}
		else
		{
			down = 'anim.html';
			up = 'graf.php';
		}
		if (keycode == 37)
			navigate(down);
		else if (keycode == 39)
			navigate(up);
	}
	</script>
	</head>
	
	<body>
		<div id="bar" style="width:100%; height:100px; background:#D8F6CE;">
			<div style="width:400px; position:fixed; left:0px; right:0px; margin:auto; padding-top: 10px;">
				<div id="navil" onclick="navigate('list.php')" class="uch">Список</div>
				<div style="display:block; float:left; width:30px; height:80px;"></div>
				<div id="navig" onclick="navigate('graf.php')" class="uch">график</div>
				<div style="display:block; float:left; width:30px; height:80px;"></div>
				<div id="navia" onclick="navigate('anim.html')" class="uch">анимация</div>
			</div>
		</div>
		<div id="scene">
		</div>
	</body>
</html>
