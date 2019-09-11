var dashboard = new Dashboard();

function Dashboard(){
    
}

Dashboard.prototype.bindDashboard = function () {
	const months = [
	  'Jan',
	  'Feb',
	  'Mar',
	  'Apr',
	  'May',
	  'Jun',
	  'Jul',
	  'Aug',
	  'Sep',
	  'Oct',
	  'Nov',
	  'Dec'
	]

	var data = $('#searches-by-venue').data('searches');
		
	var result = [];
	for (var x = 0; x < data.length; x++) {
	    var x_label = data[x].label;
		obj = {};
		obj['label'] = data[x].label;
	    
	    for (var y = 0; y < data.length; y++){
	    	if(x_label === data[y].label){
				obj[data[y].type] = data[y].total;
	    	}
	    }
	    
	    var found = result.some(function (r) {
	        return r.label === x_label;
        }); 

	    if(!found){
			result.push(obj);
	    }
	}

	Morris.Bar({
		element : 'searches-by-venue',
		data : result,
		xkey : ['label'],
		ykeys : ['mobile','web'],
		labels : ['Mobile','Web'], 
		barColors: ["#FFCE56", "#4BC0C0"],
		pointSize : 2,
		hideHover : 'auto'
	});

	var searches_by_platform = $('#searches-by-platform').data('searches');
		
	data = [];
	searches_by_platform.forEach(function(value){
		var color = "";
		if(value.label === 'mobile'){
			color = "#36A2EB";
		}else if(value.label === 'web'){
			color = "#ff6384";
		}
		data.push({
			label : value.label.charAt(0).toUpperCase() + value.label.slice(1).toLowerCase(),
			data : value.data,
			color: color
		});
	});

	$.plot($("#searches-by-platform"), data, {
		series : {
			pie : {
				show : true,
				innerRadius: 0.5,
				radius : 1,
				label : {
					show : false,
					radius : 2 / 3,
					formatter : function(label, series) {
						return '<div style="font-size:11px;text-align:center;padding:4px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
					},
					threshold : 0.1
				}
			}
		},
		legend : {
			show : true,
			noColumns : 1, // number of colums in legend table
			labelFormatter : null, // fn: string -> string
			labelBoxBorderColor : "#000", // border color for the little label boxes
			container : null, // container (as jQuery object) to put legend in, null means default on top of graph
			position : "ne", // position of default legend container within plot
			margin : [5, 10], // distance from grid edge to default legend container within plot
			backgroundColor : "#efefef", // null means auto-detect
			backgroundOpacity : 0 // set to 0 to avoid background
		},
		grid : {
			hoverable : true,
		},
		tooltip : true,
		tooltipOpts: {
			content : "<h4>%s: <strong>%y</strong></h4>",
			defaultTheme: true,
		}
	});

	var recent_traffic = $('#recent-traffic').data('traffic');

	data = [];
	recent_traffic.forEach(function(item) {
		data.push([(new Date(item.dt)).getTime(), item.traffic]);
	});

	$.plot($("#recent-traffic"), [data],  {
	    series : {
			lines : {
				show : true,
				lineWidth : 1,
				fill : true,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.15
					}]
				}
			},
			points: { 
				show: true,
				radius: 3,
            	fill: true,
			},
			shadowSize : 0
		},
	    xaxis: {
	        mode: "time",
	        tickSize: [1, "day"],
	        tickFormatter: function (v, axis) {
	        	var dt = new Date(v);

	        	return months[dt.getMonth()] + ' ' + dt.getDate() + ', ' + dt.getFullYear();
	        }
	    },
	    selection : {
			mode : "x"
		},
	    grid: {
	        hoverable: true,
	        borderWidth: 2,
	        borderColor: "#187da0"
	    },
	    tooltip : true,
		tooltipOpts : {
			content : "Your traffic for <b>%x</b> was <span>%y</span>",
			dateFormat : "%y-%0m-%0d",
			defaultTheme : false
		},
	    colors: ["#187da0"]
	});
}

Dashboard.prototype.reloadPageContent = function (data, message, callback) {
    dashboard.bindDashboard();
}

$(document).ready(function(){
	dashboard.bindDashboard();
});