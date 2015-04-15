var google_lat_lng_lookup_map = (function() {

	var geocoder, prefix, address_input, lat_input, lng_input, lookup_button, feedback;

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

				var lat = results[0].geometry.location.lat();
				var lng = results[0].geometry.location.lng();

				lat_input.val(lat).css('display','none').fadeIn('slow');
				lng_input.val(lng).css('display','none').fadeIn('slow');

				// update preview
				if(map_preview != false){
					var markers = 'markers=color:blue%7Csize:mid%7Clabel:A%7C'+lat+','+lng;
					$(map_preview).attr('src', '//maps.googleapis.com/maps/api/staticmap?&size=170x170&zoom=13&maptype=roadmap&'+markers+'&sensor=false');
				}

				// Feedback on type of result
				if (results[0].geometry.location_type == google.maps.GeocoderLocationType.ROOFTOP){
					feedback.text('Accurate location').hide().fadeIn();
				} else {
					feedback.text('Approximate location').hide().fadeIn();
				}

			} else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {

				address_input.val("Google found no results. Try to be more sprecific.");

			} else if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {

				address_input.val("You have made too many request on this domain. Over quota.");

			} else {
				address_input.val("Geocode error: " + status);
			}

		});
	}

	function update_map() {

		fs = $(this).closest("fieldset");

		lat = fs.find('input.geocode_lat').val();
		lng = fs.find('input.geocode_lng').val();

		src = '//maps.googleapis.com/maps/api/staticmap?sensor=false&size=170x170&zoom=13&maptype=roadmap&markers=color:blue%7Csize:mid%7Clabel:A%7C'+lat+','+lng;

		fs.find("img").attr("src",src);
	}

	return function(p) {

		prefix = p;

		geocoder = new google.maps.Geocoder();

		address_input	= $("input[name="+prefix+"address]"),
		lat_input		= $("input[name="+prefix+"latitude]"),
		lng_input		= $("input[name="+prefix+"longitude]"),
		lookup_button	= $("button[name="+prefix+"lookup_button]");
		feedback		= $("span."+prefix+"feedback");

		map_preview = false;
		if($("."+prefix+"preview_map").length > 0) map_preview = $("."+prefix+"preview_map");

		// Bind button
		$(lookup_button).on('click', findCoords);

		$("input.geocode_latlng").on('keyup', update_map);

	}

})();