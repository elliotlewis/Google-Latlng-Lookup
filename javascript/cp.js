var google_lat_lng_lookup_map = (function() {
	
	var geocoder, address_input, lat_input, lng_input, lookup_button, prefix;
	
	function findCoords(address){

		if(typeof address == 'string'){
			if (address === undefined){
				return 'Address required';
			}
		}else{
			// event object in address
			if ($.trim(address_input.val()) == ''){
				address_input.val('Address required');
				lat_input.val('');
				lng_input.val('');
				return;
			}
		}
		
		var e = address;
		var address = address_input.val();

		geocoder.geocode( { 'address': address }, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				console.log(results);
				var lat = results[0].geometry.location.$a;
				var lng = results[0].geometry.location.ab;
				
				lat_input.val(lat).css('display','none').fadeIn('slow');
				lng_input.val(lng).css('display','none').fadeIn('slow');
				
				// update preview
				if(map_preview != false){
					var markers = 'markers=color:blue%7Csize:mid%7Clabel:A%7C'+lat+','+lng;
					$(map_preview).attr('src', 'http://maps.googleapis.com/maps/api/staticmap?&size=170x170&zoom=13&maptype=roadmap&'+markers+'&sensor=false');
				}
			
			} else {
				address_input.val("Geocode error: " + status);
			}
		
		});
	}

	return function(p) {
	
		prefix = p;

		geocoder = new google.maps.Geocoder();
		
		address_input = $("input[name="+prefix+"address]"),
		lat_input = $("input[name="+prefix+"latitude]"),
		lng_input = $("input[name="+prefix+"longitude]"),
		lookup_button = $("button[name="+prefix+"lookup_button]");
		
		map_preview = false;
		if($("."+prefix+"preview_map").length > 0) map_preview = $("."+prefix+"preview_map");
		
		// Bind button
		$(lookup_button).on('click', findCoords);

	}
	
})();