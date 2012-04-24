var heatMapId 	= '2804782';
var geocoder;
var map;
var layer;
var heatLayer;
var lastQuery = null;
var locationMarker = null;

function initializeFusionMap(mapDivLabel,localTableId,localHeatMapId,longitude,latitude,zoom) {
        tableId = localTableId;
        heatMapId = localHeatMapId;
	geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(latitude,longitude);
	var myOptions = {
		zoom: zoom,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById(mapDivLabel), myOptions);

	layer = new google.maps.FusionTablesLayer( tableId );

	resetForm();
}

function resetForm() {
	showHeatMap();
	document.forms[0].address.value = '';
	document.getElementById('narrowsearch-none').checked = true;
	narrowSearch();
	
	if( locationMarker != null ) {
		locationMarker.setMap( null );
	}
}

function showHeatMap() {
	
	if( heatLayer != null ) {
		heatLayer.setMap( null );
	}
	heatLayer = new google.maps.FusionTablesLayer( heatMapId, { options : {suppressInfoWindows:true} } );
	heatLayer.setMap( map );
	
	// add layer after it cause we want the markers on top
	layer.setMap( map );
	
	$("#heatmaplink").html('<a href="" onclick="hideHeatMap(); return false;"><img src="/images/hide.jpg" alt="Hide Heat Map"/></a>');
}

function hideHeatMap() {
	heatLayer.setMap( null );
	$("#heatmaplink").html('<a href="" onclick="showHeatMap(); return false;"><img src="/images/show.jpg" alt="Show Heat Map"/></a>');
}

function codeAddress(addressInputId) {
	var address = document.getElementById(addressInputId).value;
	
	if( address == '' ) {
		alert( 'please enter an address to search' );
		return;
	}
	
	address += ', Philadelphia PA';
	
	//query = new GoogleFTQueryBuilder( tableId );
	//query.setLimit(5);
	
	geocoder.geocode( { 'address': address}, function(results, status) {
	if (status == google.maps.GeocoderStatus.OK) {
			  
		latlng = results[0].geometry.location;
		
		if( latlng.lat() == '39.952335' && latlng.lng() == '-75.16378900000001' ) {
			alert( 'address was not understood, please modify your request and try again' );
			return;
		}
		
		query.setStDistance( latlng.lat(),latlng.lng() );
		map.setCenter(latlng);
		map.setZoom(14);
		
		if( locationMarker != null ) {
			locationMarker.setMap( null );
		}
		
		locationMarker = new google.maps.Marker({
			map: map, 
			position: results[0].geometry.location
		});
		
		//layer.setMap(null);

		//layer.setQuery( query.getSql() );
		//layer.setMap( map );
		
	  } else {
		alert("Geocode was not successful for the following reason: " + status);
	  }
	});
}

function narrowSearch() {
	chosen = ""
	len = document.forms[0].narrowsearch.length
	
	for (i = 0; i <len; i++) {
		if (document.forms[0].narrowsearch[i].checked) {
			chosen = document.forms[0].narrowsearch[i].value
		}
	}
	
	if (chosen == "") {
		alert("No Search Filter Chosen")
	}
	else {	
		layer.setMap( null );
		query = new GoogleFTQueryBuilder( tableId );
		
		if( chosen == 'none' ) 
		{
			layer.setQuery( query.getSql() );
			layer.setMap( map );
		}
		else if( chosen == 'disabled' ) 
		{
			query.addWhereClause( "'Has Disabled Access' contains ignoring case 'yes'" );
			layer.setQuery( query.getSql() );
			layer.setMap( map );
		}
		else if( chosen == 'wifi' ) 
		{
			query.addWhereClause( "'Has Wifi Access' contains ignoring case 'yes'" );
			layer.setQuery( query.getSql() );
			layer.setMap( map );
		}
                else if( chosen == 'retail' ) 
		{
			query.addWhereClause( "'Type' contains ignoring case 'retail'" );
			layer.setQuery( query.getSql() );
			layer.setMap( map );
		}
                else if( chosen == 'public' ) 
		{
			query.addWhereClause( "'Type' contains ignoring case 'public'" );
			layer.setQuery( query.getSql() );
			layer.setMap( map );
		}
                else if( chosen == 'training' ) 
		{
			query.addWhereClause( "'Ancillary Programming Description' not equal to '' AND 'Ancillary Programming Description' not equal to 'None'" );
			layer.setQuery( query.getSql() );
			layer.setMap( map );
		}
                else if( chosen == 'nonenglish' ) 
		{
			query.addWhereClause( "'Center Languages' not equal to '' AND 'Center Languages' not equal to 'English'" );
			layer.setQuery( query.getSql() );
			layer.setMap( map );
		}
		else if( chosen == 'clear' ) {
			layer.setMap( null );
		}
	}
}

function GoogleFTQueryBuilder( tableId ) {
	var _tableId = tableId;
	var _searchColumn = "Longitude";
        //var _searchColumn = "Longitude, Latitude";
	var _whereClause = null;
	var _stLat = null;
	var _stLng = null;
	
	var _limit = null;
	var _orderBy = null;
	
	this.addWhereClause = function( statement ) {
		_whereClause = statement;
	}
	
	this.setLimit = function( num ) {
		_limit = num;
	}
	
	this.setStDistance = function( lat, lng ) {
		_stLat = lat;
		_stLng = lng;
	}
	
	this.getSql = function() {
		var sql = "SELECT " + _searchColumn 
				+ " FROM " + _tableId 
				+ " WHERE 'Pending Confirmation' NOT EQUAL TO 'true'";
		
		if( _whereClause ) {
			sql += " AND " + _whereClause;
		}
		
		if( _stLat && _stLng ) {
			sql += " ORDER BY ST_DISTANCE( '" + _searchColumn + "', LATLNG(" + _stLat + "," + _stLng + ") )";
		}
		if( _limit ) {
			sql += " LIMIT " + _limit;
		}
		
        //alert( sql );
		
		return sql;
	}	
}