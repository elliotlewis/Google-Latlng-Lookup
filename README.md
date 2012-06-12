Google Maps Lat/Lng Lookup EE Fieldtype
==================================

* Author: [Elliot Lewis](http://notwothesame.com)
* Product Page:
* Download: 


Version History
---------------

- 1.0, 12/06/12
  1st release. Global and Channel Field default settings.


Requirements
------------

 * PHP5
 * ExpressionEngine 2.4 or later. (It should work back to 2.0 but I've not tested)


Description
-----------

[Google Maps V3] is a great ([mostly free]) Maps service to use on your clients websites.
This fieldtype was developed to overcome the requirements for Google Map's markers.
Google Maps requires the Latitude and Longitude to place a marker. Generally people don't know their position on the earth by this data!
Handily Google also has [geocoding] to look up written address details and return the the Lat/Lng co-ordinates.
It is possible to add a standard text area fieldtype to EE for the user to enter an address and then in the template use geocoding to find the Lat/Lng and then position a marker on the Map.

The problem with this approach is, if you had 20 markers to place on the map every page refresh will hit Geocode 20 times for the same data. This isn't very efficient and could use up your usage limit ([mostly free]!). These co-ordinates should be stored for later usage. This fieldtype does the Geocode lookup in the CP so the Lat/Lng is stored in EEs database.

The fieldtype has 3 fields, 1 is editable for the address and the other 2 show the returned co-ordinates. All 3 fields or the combined co-ordinate is [available](#templatetags).
There's also a little map preview of the returned co-ordinates so the user can check if [Googles found the right place!](publish_screen)
There's [Global Settings](global_settings) for default values every time the fieldtype is added to a field group, and default settings at a [field group](channel_field) level.

**Note**

This fieldtype only uses Google Maps CP side so once you have the Lng/Lat you can use any mapping service front-end you like.

If you use this Fieldtype let me know! Twitter: [@ElliotLewis](http://www.twitter.com/elliotlewis)


Usage
-----

### Installation ###
1. Add-ons -> Fieldtypes -> Google Maps Lat Lng Lookup -> [Install](#Installing)
2. The title (Google Maps Lat Lng Lookup) becomes a link, click to change the Global default.
3. Admin -> Channel Fields -> Add New Field.
4. Change the default settings for this Channel Field.

### Template Tags ###
Eg. Assuming you've named your fieldtype 'geo_address' in step 4 above.

**{geo_address}**  
Outputs Latitude and Longitude comma separated for direct usage with Google Maps.  
Eg. 51.5077227,-0.1279623000000356

**{geo_address:address}**  
The address the user entered. In case you need to display that as well.  
Eg. Trafalgar Square, City of Westminster, WC2N 5DN

**{geo_address:latitude}**  
Just the Latitude.  
Eg. 51.5077227

**{geo_address:longitude}**  
Just the Longitude.  
Eg. -0.1279623000000356


Screen Shots
------------

<a id="installation">**Installing**</a>

![Installing] (Google-Latlng-Lookup/raw/master/screen_shots/google_latlng_lookup_installing.png)

<a id="global_settings">**Global Settings**</a>

![Global Settings] (Google-Latlng-Lookup/raw/master/screen_shots/google_latlng_lookup_global_settings.png)

<a id="channel_field">**Channel Field**</a>

![Channel Field] (Google-Latlng-Lookup/raw/master/screen_shots/google_latlng_lookup_channel_field.png)

<a id="publish_screen">**Publish Screen**</a>

![Publish Screen] (Google-Latlng-Lookup/raw/master/screen_shots/google_latlng_lookup_publish_screen.png)


[Google Maps V3]: (https://developers.google.com/maps/documentation/javascript/)
[mostly free]: (https://developers.google.com/maps/documentation/javascript/usage#usage_limits)
[geocoding]: https://developers.google.com/maps/documentation/javascript/geocoding