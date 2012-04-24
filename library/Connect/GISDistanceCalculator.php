<?php

class Connect_GISDistanceCalculator
{
    /**
     * pilfered from http://www.zipcodeworld.com/samples/distance.php.html
     * 
     * @param type $lat1
     * @param type $lon1
     * @param type $lat2
     * @param type $lon2
     * @param type $unit
     * @return type 
     */
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2; 
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
        $dist = acos($dist); 
        $dist = rad2deg($dist); 
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ( strtoupper($unit) == "K") {
            return ($miles * 1.609344); 
        } else if ( strtoupper($unit) == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

}

