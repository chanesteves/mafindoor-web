var search = new Search();

function Search(){
    
}

var current_building = null;
var current_floor = null;

var current_longitude = null;
var current_latitude = null;

var current_annotations = [];
var current_sub_categories = [];

var map;
var popups = [];

Search.prototype.bindSearch = function () {
	$('#pnl-results').hide();

	if ("geolocation" in navigator)
		navigator.geolocation.getCurrentPosition(function(position){ 
			current_longitude = position.coords.longitude;
			current_latitude = position.coords.latitude;

			$('.building-link').each(function () {
				$(this).attr('href', $(this).attr('href') + '?lng=' + current_longitude + '&lat=' + current_latitude);
			});
		});

	mapboxgl.accessToken = 'pk.eyJ1IjoiY2hhbmVzdGV2ZXMiLCJhIjoiY2ptc2Zjc3NrMDFhczNxbXJwZ2RqajVnbyJ9.Xuv8n5uFzywa-ZRcppeAKA';

	var active = $('.nav-link.active');

	if (active && active.length > 0) {
		search.bindMap(active);
	}
	else {
		map = new mapboxgl.Map({
		    container: 'map',
		    style: 'mapbox://styles/chanesteves/cjmtws0js27yg2ss345spgpwl',
		    center: [123.751505, 13.146568],
			zoom: 12
		});

		$('#modal-select-venue').modal('show');

		if (current_longitude && current_latitude) {
			var building_obj = {
				'lng' : current_longitude,
				'lat' : current_latitude
			};

			building.showBuildings(building_obj, function (data) {
				if (data.status == 'OK') {
					$('.building-list').empty();

					data.buildings.forEach(function (building) {
						if (building.floors.length > 0)
							$('.building-list').append('<div class="row">'
														+ '<div class="col-md-12">'
															+ '<a href="/search/buildings/' + building.slug + '?lng=' + current_longitude + '&lat=' + current_latitude + '" data-id="' + building.id + '" data-floor-id="' + building.floors[0].id + '" data-map-url="' + building.floors[0].map_url + '" data-min-zoom="' + building.floors[0].min_zoom + '" data-max-zoom="' + building.floors[0].max_zoom + '" data-zoom="' + building.floors[0].zoom + '" data-longitude="' + building.floors[0].longitude + '" data-latitude="' + building.floors[0].latitude + '" class="btn btn-block btn-secondary btn-lg building-link">'
																+ '<div class="row">'
																	+ '<div class="col-xs-3">'
																		+ '<img src="' + (building.logo && building.logo != '' ? building.logo : '/images/buildings/shop.png') + '" class="building-logo">'
																	+ '</div>'	
																	+ '<div class="col-xs-9">'
																		+ '<div class="building-name"><h5>' + building.name + '</h5></div>'
																		+ '<div class="building-address">' + building.address + '</div>'
																	+ '</div>'	
																+ '</div>'
															+ '</a>'
														+ '</div>'
													+ '</div>');
					});
				}
			});
		}
	}

	$('.building-link').unbind().on('click', function (e) {
		e.preventDefault();

		var id = $(this).attr('data-id');
		var floor_id = $(this).attr('data-floor-id');
		var longitude = $(this).attr('data-longitude');
		var latitude = $(this).attr('data-latitude');
		var zoom = $(this).attr('data-zoom');

		$('#modal-select-venue').modal('hide');

		var that = $(this);

		map.flyTo({
		  center: [longitude, latitude],
		  zoom: zoom,
		  speed: 1.5,
		  curve: 1,
		  easing(t) {
		    return t;
		  }
		}).once('moveend', function () {
			$('.building-link').removeClass('active');
			$('.building-link').find('i').removeClass('icon-location-pin').addClass('icon-arrow-right-circle');
			search.bindMap(that);
			$('.building-link[data-floor-id=' + floor_id + ']').addClass('active').find('i').removeClass('icon-arrow-right-circle').addClass('icon-location-pin');
		});

		var building_activity_obj = {
			object_id : id,
			object_type : 'App\\Building',
			request_type : 'search'
		};

		activity.store(building_activity_obj, function (data) {
			console.log(data);
		});
	});
}

Search.prototype.bindMap = function (control) {
	current_annotations = [];

	var href = $(control).attr('href');

	window.history.pushState('', 'Mafindoor', href);

	$('#search-fld').val('');
	$('#pnl-results').empty().hide();

	var url = $(control).attr('data-map-url');
	var longitude = Number($(control).attr('data-longitude'));
	var latitude = Number($(control).attr('data-latitude'));
	var zoom = Number($(control).attr('data-zoom'));
	var min_zoom = Number($(control).attr('data-min-zoom'));
	var max_zoom = Number($(control).attr('data-max-zoom'));

	map = new mapboxgl.Map({
	    container: 'map',
	    style: url,
	    center: [longitude, latitude],
		zoom: zoom,
		minZoom: min_zoom,
		maxZoom: max_zoom
	});

	var floor_id = $(control).attr('data-floor-id');

	map.on('load', function () {
		floor.show(floor_id, function (data) {
			if (data.status == 'OK') {
				current_floor = data.floor;
				current_building = data.floor.building;

				$('#pnl-search').show();
				$('#pnl-floors').empty().show();

				data.floor.building.floors.forEach(function (floor) {
					$('#pnl-floors').append('<span>'
											+ '<a href="/search/buildings/' + current_building.slug + '/floors/' + floor.slug + '" data-floor-id="' + floor.id + '" data-map-url="' + floor.map_url + '" data-min-zoom="' + floor.min_zoom + '" data-max-zoom="' + floor.max_zoom + '" data-zoom="' + floor.zoom + '" data-longitude="' + floor.longitude + '" data-latitude="' + floor.latitude + '" class="btn floor-link ' + (floor.id == current_floor.id ? 'active' : '') + '">'
												+ floor.label
											+ '</a>'
										+	'</span>');

					floor.annotations.forEach(function (annotation) {
						current_annotations.push(annotation);
					});
				});

				$('.floor-link').unbind().on('click', function (e) {
					e.preventDefault();

					search.bindMap($(this));
				});

				current_sub_categories = [];

				var current_sub_categor_ids = [];

				current_annotations.forEach(function (annotation) {
					if (current_sub_categor_ids.indexOf(annotation.sub_category.id) < 0) {
						current_sub_categories.push(annotation.sub_category);
						current_sub_categor_ids.push(annotation.sub_category.id);
					}
				});

		    
		    	var feature = {
			      'type' : 'Feature',
			      'properties' : {
			        'title' : data.floor.building.name,
			        'poi' : '',
			        'call-out' : data.floor.building.name
			      },
			      'geometry' : {
			        'type' : 'Point',
			        'coordinates' : [
			          data.floor.longitude,
			          data.floor.latitude
			        ]
			      }
			    };

			    map.addLayer({
			        "id": 'floor-' + data.floor.id,
			        "type": "symbol",
			        "minzoom": data.floor.min_zoom,
			        "maxzoom": data.floor.min_zoom + 0.5,
			        "source": {
			            "type": "geojson",
			            "data": {
			                "type": "FeatureCollection",
			                "features": [feature]
			            }
			        },
			        "layout": {
			            "text-field": "{title}" + '\n(zoom in for more)',
			            "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
			            "text-offset": [0, 0],
			            "text-anchor": "center",
			            "text-size": 20,
			            "text-allow-overlap": true
			        },
			        "paint": {
		                "text-color": "#000",
		                "text-halo-color": "#fff",
		                "text-halo-width": 2
		            }
			    });

				data.floor.annotations.forEach(function (annotation) {
					if (annotation.sub_category && annotation.sub_category.category && annotation.sub_category.category.name.toUpperCase() != 'OTHERS') {
						var feature = {
					      'type' : 'Feature',
					      'properties' : {
					        'title' : annotation.map_name,
					        'poi' : annotation.sub_category.name,
					        'call-out' : annotation.map_name
					      },
					      'geometry' : {
					        'type' : 'Point',
					        'coordinates' : [
					          annotation.longitude,
					          annotation.latitude
					        ]
					      }
					    };

					    map.addLayer({
					        "id": 'annotation-' + annotation.id,
					        "type": "symbol",
					        "minzoom": annotation.min_zoom,
					        "maxzoom": annotation.max_zoom,
					        "source": {
					            "type": "geojson",
					            "data": {
					                "type": "FeatureCollection",
					                "features": [feature]
					            }
					        },
					        "layout": {
					            "text-field": "{title}",
					            "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
					            "text-offset": [0, 0],
					            "text-anchor": "center",
					            "text-size": 12,
					            "text-allow-overlap": true
					        },
					        "paint": {
				                "text-color": "#202",
				                "text-halo-color": "#fff",
				                "text-halo-width": 2
				            }
					    });
					}
				});

				map.on('zoom', function (e) {
					var mode = $(control).attr('data-mode');

					if (mode != 'ANNOTATION')
						search.showAnnotations(e, data);
				});

				map.on('click', function (e) {
					var mode = $(control).attr('data-mode');

					if (mode != 'ANNOTATION')
						search.showAnnotations(e, data);
					else
						search.showResult(control, data, longitude, latitude);
				});

				var mode = $(control).attr('data-mode');

				if (mode == 'ANNOTATION')
					search.showResult(control, data, longitude, latitude);
			

				var sub_category_id = $(control).attr('data-sub-category-id');
				var sub_category_name = $(control).attr('data-sub-category-name');

				if (sub_category_id && sub_category_id)
					search.searchAnnotationBySubCategory(sub_category_id, sub_category_name);

				$('#search-fld').off('keydown').on('keydown', function (e) {
					if (e.which == 40) {
						e.preventDefault();

						var active_result = $('#pnl-results a.active');

						if (active_result.length == 0) {
							$('#pnl-results a').eq(0).addClass('active');
						}
						else {
							$('#pnl-results a').removeClass('active');
							
							active_result.eq(0).next().next().addClass('active');	
						}

						active_result = $('#pnl-results a.active');

						if (active_result.length > 0) {
							var height = 0;
							var offset = 0;

							$('#pnl-results .result-link').each(function () {
								height += 40;

								if ($(this).attr('href') == active_result.attr('href'))
									offset = height;
							});

							if ((offset + 40) > ($('#pnl-results').height() - $('#pnl-results').scrollTop()))
								$('#pnl-results').animate({
				                    scrollTop: $('#pnl-results').scrollTop() + 40
				                }, 1);
						}
					}
					else if (e.which == 38) {
						e.preventDefault();

						var active_result = $('#pnl-results a.active');

						if (active_result.length == 0) {
							$('#pnl-results a').eq($('#pnl-results a').length - 1).addClass('active');
						}
						else {
							$('#pnl-results a').removeClass('active');
							
							active_result.eq(0).prev().prev().addClass('active');	
						}

						active_result = $('#pnl-results a.active');

						if (active_result.length > 0) {
							var height = 0;
							var offset = 0;

							$('#pnl-results .result-link').each(function () {
								height += 40;

								if ($(this).attr('href') == active_result.attr('href'))
									offset = height;
							});

							if ((offset - 40) < $('#pnl-results').scrollTop())
								$('#pnl-results').animate({
				                    scrollTop: $('#pnl-results').scrollTop() - 40
				                }, 1);
						}
					}
					else if (e.which == 13) {
						e.preventDefault();

						var active_result = $('#pnl-results a.active');

						active_result.trigger('click');
						$(this).blur();
					}
				});

				$('#search-fld').off('keyup').on('keyup', function (e) {
					if (e.which != 40 && e.which != 38)
						search.searchAnnotation($(this).val().trim());
				});

				$('#search-fld').off('focus').on('focus', function () {
					search.searchAnnotation('');
				});

				$(document).on('click', function (e) {
					var mode = $(control).data('mode');

					if ($(e.target).is(':visible') && $(e.target).closest('.header-search').length == 0 && mode != 'ANNOTATION')
						$('#pnl-results').hide();	
				});
			}
		});
	});
}

Search.prototype.searchAnnotation = function (value) {
	$('#pnl-results').empty().show();

	if (value == '') {
		current_sub_categories.forEach(function (sub_category) {
			var icon = sub_category.icon ? sub_category.icon : '';

			if (icon.trim() == '')
				icon = sub_category.category && sub_category.category.icon && sub_category.category.icon != '' ? sub_category.category.icon : '';

			if (icon.trim() == '')
				icon = '/images/buildings/shop.png';

			$('#pnl-results').append('<hr/><a class="result-link sub-category" href="/search/buildings/' + (current_building ? current_building.slug : '') + '/floors/' + (current_floor ? current_floor.slug : '') + '/subCategories/' + sub_category.slug + '" data-id="' + sub_category.id + '" data-name="' + sub_category.name + '">'
										+ '<div class="result-item">'
											+ '<div class="row">'
												+ '<div class="col-xs-2">'
													+ '<img class="thumbnail-sm" src="' + icon + '" />'
												+ '</div>'
												+ '<div class="col-xs-10">'
													+ '<div class="name"><b>' + sub_category.name + '</b></div>'
												+ '</div>'
											+ '</div>'
										+ '</div>'
									+ '</a>');
		});

		$('.result-link.sub-category').unbind().on('click', function (e) {
			e.preventDefault();

			var id = $(this).attr('data-id');
			var name = $(this).attr('data-name');

			search.searchAnnotationBySubCategory(id, name);

			var href = $(this).attr('href');

			window.history.pushState('', 'Mafindoor', href);
		});
	}
	else {
		var filtered = current_annotations.filter(function (item) { 
			return item.name.toLowerCase().indexOf(value.toLowerCase().trim()) > -1; 
		});

		filtered.forEach(function (item) {
			var logo = item.logo ? item.logo : '';

			if (logo.trim() == '')
				logo = item.sub_category && item.sub_category.category && item.sub_category.category.icon && item.sub_category.category.icon != '' ? item.sub_category.category.icon : '';

			if (logo.trim() == '')
				logo = '/images/buildings/shop.png';

			$('#pnl-results').append('<hr/><a class="result-link annotation" href="/search/buildings/' + item.floor.building.slug + '/floors/' + item.floor.slug + '/annotations/' + item.slug + '" data-building-id="' + (item.floor && item.floor.building ? item.floor.building.id : '') + '" data-floor-id="' + item.floor_id + '" data-map-url="' + item.floor.map_url + '" data-min-zoom="' + item.min_zoom + '" data-max-zoom="' + item.max_zoom + '" data-zoom="' + ((item.min_zoom + item.max_zoom) / 2) + '" data-longitude="' + item.longitude + '" data-latitude="' + item.latitude + '" data-mode="ANNOTATION" data-annotation-id="' + item.id + '" data-annotation-name="' + item.name + '" data-annotation-map-name="' + item.map_name + '" data-annotation-logo="' + (item.logo && item.logo != '' ? item.logo : (item.sub_category.icon && item.sub_category.icon != '' ? item.sub_category.icon : (item.sub_category.category.icon && item.sub_category.category.icon != '' ? item.sub_category.category.icon : ''))) + '" >'
										+ '<div class="result-item">'
											+ '<div class="row">'
												+ '<div class="col-xs-2">'
													+ '<img class="thumbnail-sm" src="' + logo + '" />'
												+ '</div>'
												+ '<div class="col-xs-10">'
													+ '<div class="name"><b>' + item.name + '</b></div>'
													+ '<div class="sub-name"><b>' + (item.floor ? item.floor.name : '') + '</b></div>'
												+ '</div>'
											+ '</div>'
										+ '</div>'
									+ '</a>');
		});

		$('.result-link.annotation').unbind().on('click', function (e) {
			e.preventDefault();

			var annotation_id = $(this).attr('data-annotation-id');
			var floor_id = $(this).attr('data-floor-id');
			var building_id = $(this).attr('data-building-id');
			var longitude = $(this).attr('data-longitude');
			var latitude = $(this).attr('data-latitude');
			var zoom = $(this).attr('data-zoom');

			var that = $(this);

			map.flyTo({
			  center: [longitude, latitude],
			  zoom: zoom,
			  speed: 1.5,
			  curve: 1,
			  easing(t) {
			    return t;
			  }
			}).once('moveend', function () {
				search.bindMap(that);
			});

			var annotation_activity_obj = {
				object_id : annotation_id,
				object_type : 'App\\Annotation',
				request_type : 'search'
			};

			activity.store(annotation_activity_obj, function (data) {
				console.log(data);
			});
		});
	}
}

Search.prototype.searchAnnotationBySubCategory = function (sub_category_id, name) {
	$('#pnl-results').empty().append('<div class="sub-category-title text-center">'
										+ '<b>' + name + '</b>'
									+ '</div>').show();

	var filtered = current_annotations.filter(function (item) { 
		return item.sub_category && item.sub_category.id == sub_category_id; 
	});

	filtered.forEach(function (item) {
		var logo = item.logo ? item.logo : '';

		if (logo.trim() == '')
			logo = item.sub_category && item.sub_category.category && item.sub_category.category.icon && item.sub_category.category.icon != '' ? item.sub_category.category.icon : '';

		if (logo.trim() == '')
			logo = '/images/buildings/shop.png';

		$('#pnl-results').append('<hr/><a class="result-link annotation" href="/search/buildings/' + item.floor.building.slug + '/floors/' + item.floor.slug + '/annotations/' + item.slug + '" data-building-id="' + (item.floor && item.floor.building ? item.floor.building.id : '') + '" data-floor-id="' + item.floor_id + '" data-map-url="' + item.floor.map_url + '" data-min-zoom="' + item.min_zoom + '" data-max-zoom="' + item.max_zoom + '" data-zoom="' + ((item.min_zoom + item.max_zoom) / 2) + '" data-longitude="' + item.longitude + '" data-latitude="' + item.latitude + '" data-mode="ANNOTATION" data-annotation-id="' + item.id + '" data-annotation-name="' + item.name + '" data-annotation-map-name="' + item.map_name + '" data-annotation-logo="' + (item.logo && item.logo != '' ? item.logo : (item.sub_category.icon && item.sub_category.icon != '' ? item.sub_category.icon : (item.sub_category.category.icon && item.sub_category.category.icon != '' ? item.sub_category.category.icon : ''))) + '" >'
									+ '<div class="result-item">'
										+ '<div class="row">'
											+ '<div class="col-xs-2">'
												+ '<img class="thumbnail-sm" src="' + logo + '" />'
											+ '</div>'
											+ '<div class="col-xs-10">'
												+ '<div class="name"><b>' + item.name + '</b></div>'
												+ '<div class="sub-name"><b>' + (item.floor ? item.floor.name : '') + '</b></div>'
											+ '</div>'
										+ '</div>'
									+ '</div>'
								+ '</a>');


		$('.result-link.annotation').unbind().on('click', function (e) {
			e.preventDefault();

			var annotation_id = $(this).attr('data-annotation-id');
			var floor_id = $(this).attr('data-floor-id');
			var building_id = $(this).attr('data-building-id');
			var longitude = $(this).attr('data-longitude');
			var latitude = $(this).attr('data-latitude');
			var zoom = $(this).attr('data-zoom');

			var that = $(this);

			map.flyTo({
			  center: [longitude, latitude],
			  zoom: zoom,
			  speed: 1.5,
			  curve: 1,
			  easing(t) {
			    return t;
			  }
			}).once('moveend', function () {
				search.bindMap(that);
			});

			var annotation_activity_obj = {
				object_id : annotation_id,
				object_type : 'App\\Annotation',
				request_type : 'search'
			};

			activity.store(annotation_activity_obj, function (data) {
				console.log(data);
			});
		});
	});

	var sub_category_activity_obj = {
		object_id : sub_category_id,
		object_type : 'App\\SubCategory',
		request_type : 'search'
	};

	activity.store(sub_category_activity_obj, function (data) {
		console.log(data);
	});
}

Search.prototype.showAnnotations = function (e, data) {						
	popups.forEach(function (popup) {
		popup.remove();
	});

	var zoom = e.target.getZoom();

	data.floor.annotations.forEach(function (annotation) {
		if (annotation.sub_category && annotation.sub_category.category && annotation.sub_category.category.name.toUpperCase() == 'OTHERS') {
			var options = {
				closeOnClick : false,
				offset : [0.5, 0.85],
				anchor : 'center'
			};

			var popup = new mapboxgl.Popup({options: options})
			  .setLngLat(new mapboxgl.LngLat(annotation.longitude, annotation.latitude))
			  .setHTML('<div class="annotation-background"><img src="' + (annotation.logo && annotation.logo != '' ? annotation.logo : (annotation.sub_category && annotation.sub_category.icon && annotation.sub_category.icon != '' ? annotation.sub_category.icon : (annotation.sub_category && annotation.sub_category.category && annotation.sub_category.category.icon && annotation.sub_category.category.icon != '' ? annotation.sub_category.category.icon : '/images/buildings/shop.png'))) + '" class="annotation-background-image"></div>');

			if (zoom <= annotation.max_zoom && zoom >= annotation.min_zoom) {
				popup.addTo(map);
				popups.push(popup);
			}
		}
	});
}

Search.prototype.showResult = function (control, data, longitude, latitude) {
	var floor_id = $(control).attr('data-floor-id');
	var annotation_id = $(control).attr('data-annotation-id');
	var annotation_name = $(control).attr('data-annotation-name');
	var annotation_map_name = $(control).attr('data-annotation-map-name');
	var annotation_logo = $(control).attr('data-annotation-logo');

	popups.forEach(function (popup) {
		popup.remove();
	});

	map.removeLayer('floor-' + data.floor.id.toString());

	current_annotations.forEach(function (annotation) {
		if (annotation.sub_category && annotation.sub_category.category && annotation.sub_category.category.name.toUpperCase() != 'OTHERS' && annotation.floor_id == data.floor.id)
			map.removeLayer('annotation-' + annotation.id.toString());
	});

	var feature = {
      'type' : 'Feature',
      'properties' : {
        'title' : annotation_name,
        'poi' : annotation_name,
        'call-out' : annotation_name
      },
      'geometry' : {
        'type' : 'Point',
        'coordinates' : [
          longitude,
          latitude
        ]
      }
    };

    map.addLayer({
        "id": 'search-annotation-' + annotation_id,
        "type": "symbol",
        "minzoom": map.getMinZoom(),
        "maxzoom": map.getMaxZoom(),
        "source": {
            "type": "geojson",
            "data": {
                "type": "FeatureCollection",
                "features": [feature]
            }
        },
        "layout": {
            "text-field": "{title}",
            "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
            "text-offset": [0, 0],
            "text-anchor": "center",
            "text-size": 12,
            "text-allow-overlap": true
        },
        "paint": {
            "text-color": "#202",
            "text-halo-color": "#fff",
            "text-halo-width": 2
        }
    });

    var options = {
		closeOnClick : false,
		offset : [0.5, 0.85],
		anchor : 'center'
	};

	var popup = new mapboxgl.Popup({options: options})
	  .setLngLat(new mapboxgl.LngLat(longitude, latitude))
	  .setHTML('<div class="annotation-background"><img src="' + annotation_logo + '" class="annotation-background-image"></div>');

	popup.addTo(map);

	$('#pnl-results').empty().append('<div class="annotation-title text-center">'
										+ '<a href="/search/buildings/' + data.floor.building.slug + '/floors/' + data.floor.slug + '" data-floor-id="' + data.floor.id + '" data-map-url="' + data.floor.map_url + '" data-min-zoom="' + data.floor.min_zoom + '" data-max-zoom="' + data.floor.max_zoom + '" data-zoom="' + data.floor.zoom + '" data-longitude="' + data.floor.longitude + '" data-latitude="' + data.floor.latitude + '" class="btn btn-sm btn-secondary back-annotation"><i class="fa fa-arrow-left"></i></a>'
										+ '<b>' + annotation_name + '</b>'
										+ '<div>' + data.floor.name + '</div>'
									+ '</div>').show();

	$('.back-annotation').unbind().on('click', function (e) {
		e.preventDefault();

		search.bindMap($(this));
	});

	var prediction = {};

	prediction.longitude = longitude;
	prediction.latitude = latitude;

	var sortedAnnotations = current_annotations.filter(function (item) { return item.floor_id == floor_id; });

	var sortedAnnotations = search.sortByDistance(prediction, sortedAnnotations);
	var nears = sortedAnnotations.splice(0, 4);

	if (nears.length > 0)
		$('#pnl-results').append('<div class="text-center near-container" style="color: #5caafe;"><b>Near</b></div>');

	nears.forEach(function (near) {
		if (near.id != annotation_id) {
			$('#pnl-results').append('<div class="near-container"><hr/><a class="result-link annotation" href="/search/buildings/' + (current_building ? current_building.slug : '') + '/floors/' + (current_floor ? current_floor.slug : '') + '/annotations/' + near.id + '" data-building-id="' + (near.floor && near.floor.building ? near.floor.building.id : '') + '" data-floor-id="' + near.floor_id + '" data-map-url="' + near.floor.map_url + '" data-min-zoom="' + near.min_zoom + '" data-max-zoom="' + near.max_zoom + '" data-zoom="' + ((near.min_zoom + near.max_zoom) / 2) + '" data-longitude="' + near.longitude + '" data-latitude="' + near.latitude + '" data-mode="ANNOTATION" data-annotation-id="' + near.id + '" data-annotation-name="' + near.name + '" data-annotation-map-name="' + near.map_name + '" data-annotation-logo="' + (near.logo && near.logo != '' ? near.logo : (near.sub_category.icon && near.sub_category.icon != '' ? near.sub_category.icon : (near.sub_category.category.icon && near.sub_category.category.icon != '' ? near.sub_category.category.icon : ''))) + '" >'
									+ '<div class="result-item">'
										+ '<div class="row">'
											+ '<div class="col-xs-2">'
												+ '<img class="thumbnail-sm" src="' + (near.logo && near.logo != '' ? near.logo : (near.sub_category.icon && near.sub_category.icon != '' ? near.sub_category.icon : (near.sub_category.category.icon && near.sub_category.category.icon != '' ? near.sub_category.category.icon : '/images/buildings/shop.png'))) + '" />'
											+ '</div>'
											+ '<div class="col-xs-10">'
												+ '<div class="name"><b>' + near.name + '</b></div>'
												+ '<div class="sub-name"><b>' + near.floor.name + '</b></div>'
											+ '</div>'
										+ '</div>'
									+ '</div>'
								+ '</a></div>');
		}
	});

	$('.result-link.annotation').unbind().on('click', function (e) {
		e.preventDefault();

		var annotation_id = $(this).attr('data-annotation-id');
		var floor_id = $(this).attr('data-floor-id');
		var building_id = $(this).attr('data-building-id');
		var longitude = $(this).attr('data-longitude');
		var latitude = $(this).attr('data-latitude');
		var zoom = $(this).attr('data-zoom');

		var that = $(this);

		map.flyTo({
		  center: [longitude, latitude],
		  zoom: zoom,
		  speed: 1.5,
		  curve: 1,
		  easing(t) {
		    return t;
		  }
		}).once('moveend', function () {
			search.bindMap(that);
		});

		var annotation_activity_obj = {
			object_id : annotation_id,
			object_type : 'App\\Annotation',
			request_type : 'search'
		};

		activity.store(annotation_activity_obj, function (data) {
			console.log(data);
		});
	});
}

Search.prototype.sortByDistance = function (pointRef, pointArray) {
    var comparator = function(a,b) { return a.value - b.value; };
    var reorder = function(e) { return pointArray[e.index]; };
    var distanceFromArray = function(b,i) {
      return { index: i, value: search.simpleDistance(pointRef, b)};
    };
    return pointArray.map(distanceFromArray).sort(comparator).map(reorder);
}

Search.prototype.simpleDistance = function (pointA, pointB) {
    var x = pointA.longitude - pointB.longitude,
        y = pointA.latitude - pointB.latitude;

    return Math.sqrt(x*x + y*y);
}

Search.prototype.reloadPageContent = function (data, message, callback) {
    search.bindSearch();
}

$(document).ready(function(){
	search.bindSearch();
});