<?php

class Connect_GISGeocoder
{	
    public static function geocode($address)
    {
        //Set up our variables
        $longitude = "";
        $latitude = "";
        $precision = "";
		
        //Three parts to the querystring: q is address, output is the format (
        $key = "ABQIAAAAb8xaoKYTLOk4GQ3u9DdeQRS-GygNfiuSEYbydJLdL-KftB6mLBT9JBHg5oBQXWwqeiODOeCLfL3fow";
        $address = urlencode( $address );
        $url = "http://maps.google.com/maps/geo?q=".$address."&output=json&key=".$key;
		//print "<a href='$url'>$url</a>\n";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        $geo_json = json_decode($data, true);

        //print_r($geo_json);

        if ($geo_json['Status']['code'] == '200') {

            $precision = $geo_json['Placemark'][0]['AddressDetails']['Accuracy'];
            $longitude = $geo_json['Placemark'][0]['Point']['coordinates'][0];
            $latitude = $geo_json['Placemark'][0]['Point']['coordinates'][1];

        } else {
            //echo "Error in geocoding! Http error ".substr($data,0,3);
            return null;
        }

        return array( 'lat' => $latitude, 'lng' => $longitude );
    } // end geocode
}

