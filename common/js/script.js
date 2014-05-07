$(function () {
	var userId = localStorage.getItem("userId");
	var fileName = localStorage.getItem("fileName");
	
	var lang = language("PL","main","all");
	
	list(lang,userId);
	
	$('#logon').click(function(){
		var uid = checkUser($('#login').val(),$('#pass').val());
		localStorage.setItem('userId', uid);
		location.reload(true);
	});
	$('#files').change(function(){
		var sid = this.value;
		localStorage.setItem('fileName', sid);
		location.reload(true);
	});
	
	$('#container').highcharts({
		chart: {
			zoomType: 'x',
			spacingRight: 20
		},
		title: {
			text: lang.graph.title
		},
		subtitle: {
			text: document.ontouchstart === undefined ?
				lang.graph.desc :
				lang.graph.desc2
		},
		xAxis: {
			type: 'datetime',
			maxZoom: 20 * 1000, // 
			title: {
				text: lang.graph.xaxis
			}
		},
		yAxis: {
			title: {
				text: lang.graph.yaxis
			}
		},
		tooltip: {
			shared: true
		},
		legend: {
			enabled: false
		},
		plotOptions: {
			area: {
				fillColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
					stops: [
						[0, Highcharts.getOptions().colors[0]],
						[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
					]
				},
				lineWidth: 1,
				marker: {
					enabled: false
				},
				shadow: false,
				states: {
					hover: {
						lineWidth: 1
					}
				},
				threshold: null
			}
		},
	
		/*series: [{
			type: 'line',
			name: lang.graph.series,
			pointInterval: 1000,
			pointStart: Date.UTC(2006, 0, 01),
			data: loadColor()[1]
		}/*,{
			type: 'area',
			name: lang.graph.series,
			pointInterval: 1000,
			pointStart: Date.UTC(2006, 0, 01),
			data: loadColor2()[1]
		}]*/
		
		series: load(lang,userId,fileName)
	});
	
	function load(lng,id,name){
		var series = new Array();

		$.ajax({
			url: "http://"+window.location.host+"/PZ/common/php/read.php",
			data: "function=readFile&uid="+id+"&name="+name,
			type: "POST",
			async: false,
			dataType: "json",
			success: function(json){
				$.each(json,function(key,val){
					$.each(val,function(ka,va){
						var datas = new Array();
						$.each(va.signal,function(k,v){
							var point = {
								y: v,
								color: '#2f7ed8'
							};
							datas.push(point);
						});
						var ser = {
							type: 'line',
							name: lng.graph.series + va.id,
							pointInterval: va.period,
							pointStart: va.start,
							data: datas
						};
						series.push(ser);
					});
				});
			},
			error: function (data) {
                alert('ERROR: ' + data.toString());
            }
		});
		
		return series;
	}
	
	function list(lng,id){
		//var sel = $('<select>').attr('name','files');
		$('#files').append($("<option>").attr('value','-1').text('Wybierz...'));
		$.ajax({
			url: "http://"+window.location.host+"/PZ/common/php/read.php",
			data: "function=listFiles&uid="+id,
			type: "POST",
			dataType: "json",
			success: function(json){
				$.each(json,function(key,val){
					$.each(val,function(k,v){
						if(v.replace(".dat","") === fileName)
							$('#files').append($("<option>").attr('value',v.replace(".dat","")).attr('selected','selected').text(v.replace(".dat","")));
						else
							$('#files').append($("<option>").attr('value',v.replace(".dat","")).text(v.replace(".dat","")));
					});
				});
			},
			error: function (json) {
				alert('ERROR: An error occured  in function activityInfo!');
			}
		});
	}
	
	
	
	function loadColor(){
		var datas = [];
		
		$.ajax({
			url: "http://"+window.location.host+"/PZ/common/php/read.php",
			data: "name=c",
			type: "POST",
			async: false,
			dataType: "json",
			success: function(json){
				$.each(json,function(key,val){
					$.each(val,function(k,v){
						var point = {
							y: v,
							color: '#000000'
						};
						if(v > 200){
							point.color = '#BF0B23';
						}
						else{
							point.color = '#2f7ed8';
						}
						datas.push(point);
						
					});
				});
			},
			error: function (data) {
                alert('ERROR: ' + data.toString());
            }
		});
		
		return datas;
	}
	
	function loadColor2(){
		var datas = [];
		var errors = [];
		var p0 = {
			y: null,
			color: '#2f7ed8'
		};
		
		$.ajax({
			url: "http://"+window.location.host+"/PZ/common/php/read.php",
			data: "name=a",
			type: "POST",
			async: false,
			dataType: "json",
			success: function(json){
				
				$.each(json,function(key,val){
					$.each(val,function(k,v){
						//point.y = v;
						
						var point = {
							y: v,
							color: '#000000'
							//backgroundColor: '#2f7ed8'
						};
						if(v > 128){
							point.color = '#BF0B23';
							datas.push(point);
							errors.push(p0);
						}
						else{
							point.color = '#2f7ed8';
							datas.push(p0);
							errors.push(point);
						}
						//datas.push(point);
						
					});
				});
			},
			error: function (data) {
                alert('ERROR: ' + data.toString());
            }
		});
		
		var aa = [];
		aa.push(datas);
		aa.push(errors);
		
		return aa;
	}
	
	function color(){
		
	}
	
	function language(language,pack,page){
		var result = "";
		
		$.ajax({
			url: "http://"+window.location.host+"/PZ/common/php/content.php",
			data: "language="+language+"&pack="+pack+"&page="+page,
			type: "POST",
			dataType: "json",
			async: false,
			success: function(json){
				$.each(json,function(key,val){
					result = val;
				});
			},
			error: function (json) {
				alert('ERROR: An error occured  in function activityInfo!');
			}
		});
		
		return result;
	}
	
	function checkUser(login,pass){
		var result = 0;
		
		$.ajax({
			url: "http://"+window.location.host+"/PZ/common/php/database.php",
			data: "function=checkUser&p1="+login+"&p2="+pass,
			type: "POST",
			async: false,
			dataType: "json",
			success: function(json){
				$.each(json,function(key,val){
					result = val.user_id;
				});
			},
			error: function (data) {
                alert('ERROR: checkUser');
            }
		});
		
		return result;
	}
});