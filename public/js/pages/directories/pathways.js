var pathways = new Pathways();

var lineMaps = [],
	routeMaps = [];
var pointGeojson = {
	"type": "FeatureCollection",
	"features": [{
		"type": "Feature",
		"geometry": {
			"type": "Point",
			"coordinates": [0, 0]
		},
		"properties": {
			"title": ""
		}
	}]
};
var lineGeojson = {
	"type": "FeatureCollection",
	"features": [{
		"type": "Feature",
		"geometry": {
			"type": "LineString",
			"coordinates": [[0, 0]]
		},
		"properties": {
		}
	}]
};
var lineCount = 0, routeCount = 0;
var routeOrigin, routeDestination;
var adjascent_ids = [];

function Pathways(){
    
}

Pathways.prototype.bindPathways = function () {
	mapboxgl.accessToken = 'pk.eyJ1IjoiY2hhbmVzdGV2ZXMiLCJhIjoiY2ptc2Zjc3NrMDFhczNxbXJwZ2RqajVnbyJ9.Xuv8n5uFzywa-ZRcppeAKA';
	
	$('.lines-map').each(function () {
		var id = $(this).attr('id');
		var floor_id = $(this).data('id');
		var url = $(this).data('url');
		var longitude = $(this).data('longitude');
		var latitude = $(this).data('latitude');
		var zoom = $(this).data('zoom');

		lineMaps[floor_id] = new mapboxgl.Map({
			container: id,
			style: url,
			center: [longitude, latitude],
			zoom: zoom
		});

		lineMaps[floor_id].on('load', function (e) {
			floor.show(floor_id, function (data) {
				if (data.status == 'OK') {
					data.floor.points.forEach(function (point) {
						pointGeojson.features[0].geometry.coordinates = [point.longitude, point.latitude];
				        pointGeojson.features[0].properties.title = point.id.toString();  
				        pointGeojson.features[0].properties.floor_id = point.floor_id;
				          
				        lineMaps[floor_id].addLayer({
				            "id": "circle" + point.id.toString(),
				            "type": "circle",
				            "source": {
				              "type": "geojson",
				              "data": pointGeojson
				            },
				              "paint": {
				                  "circle-radius": 10,
				                  "circle-color": "#5b94c6"
				              }
				        });

				        lineMaps[floor_id].addLayer({
				            "id": "symbol" + point.id.toString(),
				            "type": "symbol",
				            "source": {
				              "type": "geojson",
				              "data": pointGeojson
				            },
				            "paint": {
				                "text-color": "#FFF"              
				              },
				              "layout": {       
				                "text-size": 8,     
				              	"text-field": "{title}"
				              }
				        });

				        lineMaps[floor_id].on('click', "circle" + point.id.toString(), function (e) {
							var coordinates = e.features[0].geometry.coordinates.slice();
							var title = e.features[0].properties.title;
							var floor_id = e.features[0].properties.floor_id;

							var row = $('#tbl-pathways .extra-row.visible').eq(0);
							var o = $(row).find('.origin').val();
							var d = $(row).find('.destination').val();

							if (o.trim() == '') {
								$(row).find('.origin').closest('td').attr('data-floor-id', floor_id);
								$(row).find('.origin').val(title).trigger('change');								
							}
							else if (d.trim() == '') {
								if (title == o)
									return;

								$(row).find('.destination').closest('td').attr('data-floor-id', floor_id);
								$(row).find('.destination').val(title).trigger('change');								
							}
						});
					});

					checkPathwaysEmptyRow();
					data.floor.building.adjascents.forEach(function (adjascent) {
						if (adjascent.origin && adjascent.destination
							&& (adjascent.origin.floor_id == data.floor.id
								|| adjascent.destination.floor_id == data.floor.id)
							&& adjascent_ids.indexOf(adjascent.id) < 0) {
							var row = $('#tbl-pathways .extra-row.visible');

							$(row).attr('data-id', adjascent.id);
				            $(row).find('.origin').val(adjascent.origin.id);
				            $(row).find('.destination').val(adjascent.destination.id);
				            $(row).find('.two-way').removeAttr('checked');

				            $(row).find('.origin').closest('td').attr('data-floor-id', adjascent.origin.floor_id);
				            $(row).find('.destination').closest('td').attr('data-floor-id', adjascent.destination.floor_id);
					
							$(row).removeClass('extra-row');		
			        		checkPathwaysEmptyRow();			            

				            lineGeojson.features[0].geometry.coordinates[0] = [adjascent.origin.longitude, adjascent.origin.latitude];
			        		lineGeojson.features[0].geometry.coordinates[1] = [adjascent.destination.longitude, adjascent.destination.latitude];

			        		lineMaps[floor_id].addLayer({
								"id": "line" + adjascent.id.toString(),
								"type": "line",
									"source": {
									"type": "geojson",
									"data": lineGeojson
								},
								"layout": {
									"line-join": "round",
									"line-cap": "round"
								},
								"paint": {
									"line-color": "#5b94c6",
									"line-width": 8,
									"line-opacity": 0.5
								}
							});

							$(row).attr('data-line-id', adjascent.id.toString());

							adjascent_ids.push(adjascent.id);
						}
					});

					bindTable();
				}
			});
		});
	});	

	$('#nvs-lines-floors .nav-link').unbind('click').on('click', function () {
		var f_id = $(this).data('id');

		setTimeout(function () {
			lineMaps[f_id].resize ();
		}, 1000);			
	});

	$('#nvs-pathways .nav-link').unbind('click').on('click', function () {
		$('#nvs-lines-floors .nav-link').eq(0).trigger('click');
		$('#nvs-routes-floors .nav-link').eq(0).trigger('click');
	});

	function bindTable () {
		$('#tbl-pathways .origin, #tbl-pathways .destination').unbind('change').on('change', function () {
			var val = $(this).val();

		    if (val.trim() != '') {
		        var o = $(this).closest('tr').find('.origin').val();
	        	var d = $(this).closest('tr').find('.destination').val();

	        	var o_floor_id = $(this).closest('tr').find('.origin').closest('td').attr('data-floor-id');
	        	var d_floor_id = $(this).closest('tr').find('.destination').closest('td').attr('data-floor-id');

	        	if (o.trim() != '' && d.trim() != '') {
	        		var o_features = lineMaps[o_floor_id].queryRenderedFeatures({layers: ['circle' + o]});
		        	var d_features = lineMaps[d_floor_id].queryRenderedFeatures({layers: ['circle' + d]});

		        	var o_feature, d_feature;
		        	var o_lng, o_lat, d_lng, d_lat;

		        	if (o_features.length > 0)
		        		o_feature = o_features[0];

		        	if (d_features.length > 0)
		        		d_feature = d_features[0];

	        		lineCount++;
	        		$(this).closest('tr').removeClass('extra-row');		
		        	checkPathwaysEmptyRow();

	        		lineGeojson.features[0].geometry.coordinates[0] = o_feature.geometry.coordinates;
	        		lineGeojson.features[0].geometry.coordinates[1] = d_feature.geometry.coordinates;

	        		while(adjascent_ids.indexOf(lineCount) >= 0) {
	        			lineCount++;
	        		}

	        		if (o_floor_id == d_floor_id) {
		        		lineMaps[o_floor_id].addLayer({
							"id": "line" + lineCount.toString(),
							"type": "line",
								"source": {
								"type": "geojson",
								"data": lineGeojson
							},
							"layout": {
								"line-join": "round",
								"line-cap": "round"
							},
							"paint": {
								"line-color": "#5b94c6",
								"line-width": 8,
								"line-opacity": 0.5
							}
						});					
		        	}

		        	$(this).closest('tr').attr('data-line-id', lineCount.toString());
	        	}
		    }
		});

		$('#tbl-pathways .btn-danger').unbind('click').on('click', function () {
	      	var line_id = $(this).closest('tr').attr('data-line-id');

	      	var o_floor_id = $(this).closest('tr').find('.origin').closest('td').attr('data-floor-id');
	        var d_floor_id = $(this).closest('tr').find('.destination').closest('td').attr('data-floor-id');

	      	$(this).closest('tr').remove();	      	
	      	checkPathwaysEmptyRow();

	      	if (o_floor_id == d_floor_id)
	      		lineMaps[o_floor_id].removeLayer("line" + line_id);
	    });
	}

	function checkPathwaysEmptyRow () {
	    if ($('#tbl-pathways .extra-row.visible').length == 0) {
	      var row = $('#tbl-pathways .extra-row.hidden').clone();

	      $(row).removeClass('hidden').addClass('visible');
	      $('#tbl-pathways tbody').append(row);

	       $("#pnl-pathways").animate({ scrollTop: $("#tbl-pathways").height() }, 10);
	      bindTable();
	    }
	}

	$('#tbl-pathways tr.visible').remove();
	checkPathwaysEmptyRow();
	bindTable();

	$('#frm-pathways').unbind('submit').on('submit', function (event) {
	    event.preventDefault();

	    var id = $('#ddl-building-id').val();
	    var lines = [];

	    $('#tbl-pathways tr.visible').each(function () {
	      var line_id = $(this).attr('data-id');
	      var o = $(this).find('.origin').val();
	      var d = $(this).find('.destination').val();     
	      var t = $(this).find('.two-way').is(':checked') ? 1 : 0;

	      if (o.trim() != '' && d.trim() != '')
	        lines.push({ 'id' : line_id, 'origin' : o, 'destination' : d, 'two_way' : t });
	    });

	    var building_obj = {
	      'id' : id,
	      'adjascents' : lines
	    }

	    modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the lines.', null);

	    building.updateAdjascents(building_obj, function (data) {        
	      if (data.status == 'OK')
	        modal.set('success', 'fa-check-circle', 'Success', 'Building lines successfully updated.', { ok : function () {
	          window.location.reload();
	        }});
	      else
	        modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
	    });
	});

	$('.routes-map').each(function () {
		var id = $(this).attr('id');
		var floor_id = $(this).data('id');
		var url = $(this).data('url');
		var longitude = $(this).data('longitude');
		var latitude = $(this).data('latitude');
		var zoom = $(this).data('zoom');

		routeMaps[floor_id] = new mapboxgl.Map({
			container: id,
			style: url,
			center: [longitude, latitude],
			zoom: zoom
		});

		routeMaps[floor_id].on('load', function (e) {
			floor.show(floor_id, function (data) {
				if (data.status == 'OK') {
					data.floor.annotations.forEach(function (annotation) {
						pointGeojson.features[0].geometry.coordinates = [annotation.longitude, annotation.latitude];
				        pointGeojson.features[0].properties.title = annotation.map_name;  

						routeMaps[annotation.floor_id].addLayer({
				            "id": "annotation" + annotation.id.toString(),
				            "type": "symbol",
				            "source": {
				              "type": "geojson",
				              "data": pointGeojson
				            },
				            "paint": {
				                "text-color": "#000"              
				              },
				              "layout": {       
				                "text-size": 12,     
				              	"text-field": "{title}"
				              }
				        });

				        routeMaps[annotation.floor_id].on('click', "annotation" + annotation.id.toString(), function (e) {
							if (!routeOrigin)
								routeOrigin = annotation;
							else if (!routeDestination)
								routeDestination = annotation;

							if (routeOrigin && routeDestination) {
								if (routeCount > 0)
									routeMaps[annotation.floor_id].removeLayer("route" + routeCount);

								var building_obj = {
							      'id' : data.floor.building_id,
							      'origin' : routeOrigin.id,
							      'destination' : routeDestination.id,
							    }

							    modal.show('info', 'fa-refresh fa-spin', 'Retrieving...', 'Please wait while we are retrieving the route.', null);

							    building.showRoute(building_obj, function (data) {        
							      if (data.status == 'OK') {
							      	modal.show('info', 'fa-info', 'Route', 'This is a' + (data.route_status == 'new' ? '' : 'n') + ' ' + data.route_status + ' route.', { ok : true });

							      	Object.keys(data.floors).forEach(function (e, i) {
							      		var coords = [];
								      	data.floors[e].points.forEach(function (point) {
								      		coords.push([point.longitude, point.latitude]);
								      	});

								      	routeCount++;
								      	lineGeojson.features[0].geometry.coordinates = coords;
								      	routeMaps[e].addLayer({
											"id": "route" + routeCount,
											"type": "line",
												"source": {
												"type": "geojson",
												"data": lineGeojson
											},
											"layout": {
												"line-join": "round",
												"line-cap": "round"
											},
											"paint": {
												"line-color": "#5b94c6",
												"line-width": 8,
												"line-opacity": 0.8
											}
										});
							      	});

									routeOrigin = null;
									routeDestination = null;
							      }							        
							      else {
							        modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
							      }
							    });
							}
						});
					});
				}
			});
		});
	});

	$('#nvs-routes-floors .nav-link').unbind('click').on('click', function () {
		var f_id = $(this).data('id');

		setTimeout(function () {
			routeMaps[f_id].resize ();
		}, 1000);			
	});
}

Pathways.prototype.reloadPageContent = function (data, message, callback) {
    pathways.bindPathways();
}

$(document).ready(function(){
	pathways.bindPathways();
});