/* ==================
	Gmap API
================== */

jQuery(document).ready(function() {
	// append Gmap script
	// node detail and hotel
	if (typeof Drupal.settings.hgh_travel != 'undefined' && location.href.indexOf('/hotel/') == -1) {
		if (Drupal.settings.hgh_travel.nid > 0) {
			try {
				var script = document.createElement('script');
				script.type = 'text/javascript';
				script.src = 'http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' + 'callback=gmap_initialize';
				document.body.appendChild(script);
			} catch (e) {
				
			}
		}
	
		// map for destinations page

		if (Drupal.settings.hgh_travel.nid == 'destinations') {
			var script = document.createElement('script');
			script.type = 'text/javascript';
			script.src = 'http://maps.googleapis.com/maps/api/js?sensor=false&' + 'callback=gmap_initialize_destinations';
			document.body.appendChild(script); 
		}
	}
});

function gmap_initialize() {
	jQuery('.showmap').click(function() {
		showmap();
	});
	
	try {
		showHmap();
	} catch (e) {}
}

function showmap() {
	jQuery('.slider-container').hide();
	jQuery('#images-slider').prepend(jQuery('#gmap'));
	jQuery('#captions-wrapper').hide();
	jQuery('#gmap').show();

	jQuery('#images-slider').prepend('<div id="mapWrapperId" class="gmaputil"></div>');
	jQuery('#images-slider').prepend('<div id="mapTourGuideId" class="gmaputil"></div>');
	//jQuery('#imagewrapper').prepend('<div id="switchtophotos" class="gmaputil orange-btn">switch to image view</div>');  

	jQuery('#gmap').gmapZ();
	// jQuery('#switchtophotos').click(hidemap);
}

function hidemap() {
	jQuery('#gmap').hide();
	//$('#imageslider').show();
	//$('.gmaputil').remove();  
}

function gmap_initialize_destinations() {
	jQuery('#captions-wrapper').hide();
	jQuery('.slider-container').hide();
	jQuery('#images-slider').prepend(jQuery('#gmap'));
	
	jQuery('#images-slider').prepend('<div id="mapWrapperId" class="gmaputil"></div>');
	jQuery('#images-slider').prepend('<div id="mapTourGuideId" class="gmaputil"></div>');
	jQuery('#gmap').show();	
	jQuery('#gmap').gmapZ();
}

// hotel map
function showHmap() {	
	var latlng = new google.maps.LatLng(jQuery('#hotel-lat').val(), jQuery('#hotel-long').val());
	var _mapOpt = {
		zoom: 16,
		minZoom:3,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP, // TERRAIN
		disableDoubleClickZoom:true,
		scrollwheel: true
	};
	
	var map = new google.maps.Map(document.getElementById('hotel-gmap'), _mapOpt);
	marker = new google.maps.Marker({
		map:map,
		draggable:false,
		animation: google.maps.Animation.DROP,
		position: latlng
	});
	
}

(function($) {	
	$.fn.gmapZ = function(options) {  
	var _gMapData	= (options) ? options:{};
	activeMarker  	= null;
	prevMarker		= null;
	googleMap   	= null;
	googleMapBounds = null;
	markerArray   	= new Array();
	mapType			= (typeof(_gMapData.maptype) != "undefined") ? _gMapData.maptype : $(this).data('maptype');
	nId	= (mapType == "tours" || mapType == "hotels" || mapType == "destinations") ? ((typeof(_gMapData.id)!="undefined") ? _gMapData.id:$(this).data('nid')) : "";
	
	var latlng = new google.maps.LatLng(16.551, 107.094);
	var _mapOpt = {
		zoom: 5,
		minZoom:3,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.TERRAIN,
			disableDoubleClickZoom:true,
			scrollwheel: true
	};
	
	googleMap = new google.maps.Map($(this).get(0), _mapOpt);
	googleMapBounds = new google.maps.LatLngBounds();
	
	google.maps.event.addListener(googleMap, 'drag', function() {
		if (activeMarker) {
			setBoxPosition(activeMarker);
		}
	});
	
	google.maps.event.addListener(googleMap, 'zoom_changed', function() {
		if (activeMarker) {
			setBoxPosition(activeMarker);
		}
	});
	
	google.maps.event.addListener(googleMap, 'click', function() {
		$("#mapWrapperId").html("");
		if (""+prevMarker+"" != "null") {prevMarker.setIcon(prevMarker.defaultIcon);}
	});
	
	$(document).on('click', '#mapWrapperId .close', function() {
		$("#mapWrapperId").html("");
		if (""+prevMarker+"" != "null") {
			prevMarker.setIcon(prevMarker.defaultIcon);
		}
	});
	
	var _reqURI = Drupal.settings.basePath + "map-data/"+mapType+"/"+nId;
	$.ajax({
		type	:'GET',
		url		:_reqURI,
		dataType: "json",
		success: function(data) {
			if (mapType == "tours") {
				addMarkers({map:googleMap, markers:data.tours, icons:data.icons});
				drawPath({map:googleMap, nodes:data.tours});
				addTourGuide({map:googleMap, title:data.title, nodes:data.tours});
			}
			
			if (mapType == "hotels") {
				 addMarkers({map:googleMap, markers:data.hotels, icons:data.icons});
			}
			
			if (mapType == "destinations") {
				addMarkers({map:googleMap, markers:data.countries, icons:data.icons});
			}
			
			if (mapType == "cities") {
				addMarkers({map:googleMap, markers:data.cities, icons:data.icons});
			}
		}
	});
	
	addMarkers = function(_markerOpt) {
	  var _icoShadow = _markerOpt.icons.shadow;
	  for (var _x=0; _x < _markerOpt.markers.length; _x++) {
			var _icoImg = _markerOpt.icons.icon;
			var _icoImgOv = _markerOpt.icons.over;
			var _latlng = new google.maps.LatLng(_markerOpt.markers[_x].pos.lat, _markerOpt.markers[_x].pos.long);
			
			var marker_width = 40;
			var marker_height = 40;
			
			var markershadow_width = 45;
			var markershadow_height = 45;
			
			if(mapType == "destinations") {
				_icoImg = _icoImg.replace( ".png", _markerOpt.markers[_x].tours + ".png" );
				_icoImgOv = _icoImgOv.replace( ".png", _markerOpt.markers[_x].tours + ".png" );
				
				marker_width = 60;
				marker_height = 60;
				
				marker_width = 60;
				marker_height = 60;
			}
			
			var _markerImg = new google.maps.MarkerImage(
				_icoImg,
				new google.maps.Size(marker_width, marker_height),
				new google.maps.Point(0,0),
				new google.maps.Point(20, 49)
			);
			
			var _markerShadow = new google.maps.MarkerImage(
				_icoShadow,
				new google.maps.Size(markershadow_width, markershadow_height),
				new google.maps.Point(0,0),
				new google.maps.Point(21, 49)
			);
			
			var _markerImgOv = new google.maps.MarkerImage(
				_icoImgOv,
				new google.maps.Size(marker_width, marker_height),
				new google.maps.Point(0,0),
				new google.maps.Point(20, 49)
			);
		
			if (mapType == "tours") {
				var _tpl = "<div [id] class=\"gmap-tourdaysbox\"><div class=\"hd\">[label]<span class=\"close\"></span></div><div class=\"data\"><div class=\"img\">";
				
				if(_markerOpt.markers[_x].image != "" && _markerOpt.markers[_x].image != "\/" ) {
					_tpl =  _tpl +	"<img src=\"[img]\" width=\"120\" height=\"72\" border=\"0\" alt=\"[label]\"/>";
				}
				_tpl =  _tpl +	"</div><div class=\"text\">[text]</div><div class=\"clear\"></div></div><div class=\"options\">[options]</div></div>";
		
				_tpl = _tpl.replace("[id]", "id=\"marker_"+_x+"\"");
				_tpl = _tpl.replace(/\[label\]/g, _markerOpt.markers[_x].label);
				
				if(_markerOpt.markers[_x].image != "" && _markerOpt.markers[_x].image != "\/" ) {
					_tpl = _tpl.replace("[img]", _markerOpt.markers[_x].image);
				}
				
				_tpl = _tpl.replace("[text]", _markerOpt.markers[_x].desc);
				_tpl = _tpl.replace("[options]", _markerOpt.markers[_x].opts);
				$('.gmap-tourdaysbox .close').click($(this).hide());
			}
			
			var _tours_text = 'TOURS';
			var _hotels_text = 'HOTELS';
			var _inspirations_text = 'INSPIRATIONS';
			var _travel_guide_text = 'TRAVEL GUIDE';
			var _things_text = 'THINGS TO DO';
			var _promotion_text = 'PROMOTION';

			if (Drupal.settings.hgh_travel.language == 'fr') {
				_tours_text = 'TOUR';
				_hotels_text = 'Hôtel';
				_inspirations_text = 'INSPIRATIONS';
				_travel_guide_text = 'GUIDE DE VOYAGE';
				_things_text = 'CHOSES À FAIRE';
				_promotion_text = 'PROMOTION';
			}

			if (mapType == "destinations" || mapType=="country-destinations") {
				var _tpl = "<div [id] class=\"gmap-databox gmap-destinationbox\"><div class=\"hd ptsanssmallheader\"><span class=\"boxtitle\">[label]</span><div class=\"floatright close\"></div></div>"+
				"<div class=\"data\">"+
					"<div class=\"tabs\" style=\"position:absolute;\">"+
						"<div class=\"head\" data-id=\"tab-toursId\" data-contenttype=\"tours\">"+_tours_text+"</div>"+
						"<div class=\"head\" data-id=\"tab-hotelsId\" data-contenttype=\"hotels\">"+_hotels_text+"</div>"+
						"<div class=\"head\" data-id=\"tab-inspirationId\" data-contenttype=\"inspirations\">"+_inspirations_text+"</div>"+
						"<div class=\"head\" data-id=\"tab-travelguideId\" data-contenttype=\"travelguide\">"+_travel_guide_text+"</div>"+
						"<div class=\"head\" data-id=\"tab-thingstodoId\" data-contenttype=\"thingstodo\">"+_things_text+"</div>"+
						"<div class=\"head\" data-id=\"tab-promotionId\" data-contenttype=\"promotion\">"+_promotion_text+"</div>"+
						"<div class=\"clear\"></div>"+
					"</div>"+
					"<div class=\"tab-data\" style=\"margin-top:30px;\">"+
						"<div class=\"datadiv\" id=\"tab-toursId\" data-loaded=\"false\"></div>"+
						"<div class=\"datadiv\" id=\"tab-hotelsId\" data-loaded=\"false\"></div>"+
						"<div class=\"datadiv\" id=\"tab-inspirationId\" data-loaded=\"false\"></div>"+
						"<div class=\"datadiv\" id=\"tab-travelguideId\" data-loaded=\"false\"></div>"+
						"<div class=\"datadiv\" id=\"tab-thingstodoId\" data-loaded=\"false\"></div>"+
						"<div class=\"datadiv\" id=\"tab-promotionId\" data-loaded=\"false\"></div>"+
					"</div>"+
				"</div>"+
				"<div class=\"clear\"></div>"+
				"</div>";
				_tpl = _tpl.replace("[id]", "id=\"marker_"+_x+"\"");
				_tpl = _tpl.replace(/\[label\]/g, _markerOpt.markers[_x].label);	
			}
		
			var _mapMarker = new google.maps.Marker({
				position: _latlng, 
				map: _markerOpt.map,
				title:_markerOpt.markers[_x].title,
				icon: _markerImg,
				shadow:_markerShadow,
				template:_tpl,
				markerNum:_x,
				defaultIcon:_markerImg,
				overIcon:_markerImgOv,
				termId:_markerOpt.markers[_x].tid,
				dataLoaded:{"hotels":false, "tours":false, "bestvalue":false, "inspirations":false, "travelguide":false, "thingstodo":false, "promotion":false}
			});
			
			googleMapBounds.extend(_latlng);
			google.maps.event.addListener(_mapMarker, 'mouseover', markerClick);
			google.maps.event.addListener(_mapMarker, 'mouseout', function(_opt) {prevMarker=this});
			
			if (mapType == "destinations") {
				google.maps.event.addListener(_mapMarker, 'click', function() {
					$("#gmap").gmapZ({"id":this.termId, "maptype":"country-destinations"});
					$("#mapWrapperId").html("");
					$("#mapTourGuideId").html("");
				});
			}
			
			markerArray.push(_mapMarker);
		}
		
		// fitbount incase there are too much markers
		//if (mapType != "destinations") {
			googleMap.fitBounds(googleMapBounds);
		//}
	}
		
	markerClick = function(_mkr) {
		if (""+prevMarker+"" != "null") {prevMarker.setIcon(prevMarker.defaultIcon);}
		this.setIcon(this.overIcon);
		var _marker = this;
		$("#mapWrapperId").html(this.template);
		setBoxPosition(this);
		activeMarker = this;
		$("#marker_"+this.markerNum+"").fadeIn(200, function() { setBoxPosition(activeMarker); });
		this.dataLoaded = {"hotels":false, "tours":false, "bestvalue":false, "inspirations":false, "travelguide":false, "thingstodo":false, "promotion":false};
		if (mapType == "destinations" || mapType == "cities") {
			$("#marker_"+this.markerNum+"").find(".tabs .head").each(function() {
				$(this).click(function() {
					
					var _rerurnId = $(this).data('id');
					$(this).parent().find(".head").removeClass("selected");
					$(this).addClass("selected");
					
					var _imgPath = "/sites/default/themes/adaptivetheme/hgh/images/";
					_tabClick = function(_opts, _pg) {
						return $.ajax({
							type	:'GET',
							url		: Drupal.settings.basePath+'map-data/'+activeMarker.termId+'/'+_opts.data('contenttype')+'/page/'+_pg,
							dataType: "json",
							success: function(data) {
								var _nav  = "";
								if (_opts.data('contenttype') == "tours" || _opts.data('contenttype') == "hotels") {
									_nav = "<div class=\"navbar\">";
									_nav += (data.currentpage < data.totalpages)? "<div style=\"float:right;\"><img class=\"nextbt\" data-page=\"[pg]\" src=\""+_imgPath+"next.png\" width=\"7\" border=\"0\" alt=\"\" /></div>":"";
									_nav += (data.currentpage >  1)? "<div style=\"float:right;\"><img class=\"prevbt\" data-page=\"[pg]\" src=\""+_imgPath+"prev.png\" width=\"7\" border=\"0\" alt=\"\" /></div>":"";
									_nav += "<div style=\"float:right;\">Showing [pg] of [tot]</div>";
									_nav += "<div style=\"clear:both; overflow:hidden; line-height:0px; height:0px;\"></div>";
									_nav += "</div>";
									_nav = _nav.replace(/\[pg\]/g, data.currentpage);
									_nav = _nav.replace("[tot]", data.totalpages);
								}
								
								var _mkr  = $("#marker_"+_marker.markerNum+"");
								_mkr.find(".tab-data .datadiv").fadeOut(10);
																
								var _content = "<div class=\"tbcontent"+(_rerurnId=="tab-toursId" ? " first": (_rerurnId == "tab-promotionId" ? " last-DEL" : ""))+"\">"+data.html+"</div>"+_nav;
								
								$("#"+_rerurnId+"").html(_content);
								$("#"+_rerurnId+"").find(".nextbt").click(function() {
									_tabClick(_opts, parseInt($(this).data("page"))+1);										
								});
								$("#"+_rerurnId+"").find(".prevbt").click(function() {
									_tabClick(_opts, parseInt($(this).data("page"))-1);							
								})
								$("#"+_rerurnId+"").fadeIn(200, function() {setBoxPosition(activeMarker)});
							}
						});
					}
					
					var _loaded = activeMarker.dataLoaded[$(this).data('contenttype')];
					
					if (!_loaded) {
						activeMarker.dataLoaded[$(this).data('contenttype')] = true;
						_tabClick($(this), 1);
					}else{
						$("#marker_"+_marker.markerNum+"").find(".tab-data .datadiv").fadeOut(10);
						$("#"+_rerurnId+"").fadeIn(200, function() {setBoxPosition(activeMarker)});
					}
				})
				
			})

			$($("#marker_"+this.markerNum+"").find(".tabs .head")[0]).trigger('click');	
		}			
	}
	
	drawPath = function(_markers) {
		var _path = new Array();
		for (var _x=0; _x<_markers.nodes.length; _x++) {
			_path.push( new google.maps.LatLng(_markers.nodes[_x].pos.lat, _markers.nodes[_x].pos.long));
		}
		var _drawPath = new google.maps.Polyline({
			path: _path,
			strokeColor: "#ff9900",
			strokeOpacity: 1.0,
			strokeWeight: 3
		});

		_drawPath.setMap(_markers.map);
	}
	
	setBoxPosition = function(_marker) {
		var _markerPos  = getMarkerPos(_marker);
		var _mapDiv   = $("#"+_marker.map.getDiv().id+"");
		var _mapWidrh = _mapDiv.css("width");
		var _mapHeight  = _mapDiv.css("height");
		
		var _boxW = parseInt($("#marker_"+_marker.markerNum+"").css("width"));
		var _boxH = parseInt($("#marker_"+_marker.markerNum+"").css("height"));
		var _top  = _mapDiv.position().top+_markerPos.y+5;
		var _left   = _markerPos.x+5;
		
		var _maxL   = parseInt(_mapWidrh) - _boxW -10;
		var _maxH = parseInt(_mapHeight)+_mapDiv.position().top - _boxH -10;
	  
		if (_left > _maxL) {_left = _maxL;}
		if (_left < _mapDiv.position().left ) {_left = 10;}
		if (_top > _maxH) {_top = _maxH;}
		if (_top < _mapDiv.position().top) {_top = _mapDiv.position().top+10;}
		
		$("#marker_"+_marker.markerNum+"").css("left", _left+"px");
		$("#marker_"+_marker.markerNum+"").css("top", _top+"px");
	}
	
	getMarkerPos = function(marker) {
		var _scale = Math.pow(2, marker.map.getZoom());
		var _nw = new google.maps.LatLng(
			marker.map.getBounds().getNorthEast().lat(),
			marker.map.getBounds().getSouthWest().lng()
		);
		var worldCoordinateNW = marker.map.getProjection().fromLatLngToPoint(_nw);
		var worldCoordinate = marker.map.getProjection().fromLatLngToPoint(marker.getPosition());
		var pixelOffset = new google.maps.Point(
			Math.floor((worldCoordinate.x - worldCoordinateNW.x) * _scale),
			Math.floor((worldCoordinate.y - worldCoordinateNW.y) * _scale)
		);
		return pixelOffset;
	}
	
	addTourGuide = function(data) {
		var _tmpl = "<div id=\"tourGuideBoxId\" class=\"gmap-tourguidebox\"><div class=\"hd\">[label]</div><div class=\"data\"></div></div>";
		_tmpl = _tmpl.replace("[label]", data.title);
		$("#mapTourGuideId").html(_tmpl);
		var _mapDiv   = $("#"+googleMap.getDiv().id+"");
		var _left = parseInt(_mapDiv.css("width")) - parseInt($("#tourGuideBoxId").css("width")) - 20;
		var _top  = parseInt(_mapDiv.css("top"))+80;
		$("#tourGuideBoxId").css("left", _left+"px");
		$("#tourGuideBoxId").css("top", _top+"px");
		$("#tourGuideBoxId").fadeIn(500);
		$("#tourGuideBoxId").find(".data").css("max-height", (parseInt(_mapDiv.css("height"))-150)+"px");
		$("#tourGuideBoxId").find(".data").css("background-image", 'none');
		
		for(var _x =0; _x < data.nodes.length; _x++) {
			$("#tourGuideBoxId").find(".data").append("<div id=\"tourDayId_"+_x+"\"><strong>Day "+data.nodes[_x].order+"</strong>&nbsp;:&nbsp;"+data.nodes[_x].title+"</div>");
				$("#tourDayId_"+_x+"").click(function() {
				
				});
			}
		}
	};
})(jQuery);
