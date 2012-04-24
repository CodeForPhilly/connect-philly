<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchTerms
 *
 * @author JimS
 */
class Application_Model_DbTable_SearchTerms {
 
    protected static $_terms = array( 
        'wifi'      => "'Has Wifi Access' contains ignoring case 'yes'",
        'disabled'  => "'Has Disabled Access' contains ignoring case 'yes'",
        'public'    => "'Type' contains ignoring case 'public'",
        'retail'    => "'Type' contains ignoring case 'retail'",
        //'training'  => "'Ancillary Programming Description' not equal to '' AND 'Ancillary Programming Description' not equal to 'None'",
        );

    public static function getFtSql( $searchTerm ) {
        return self::$_terms[$searchTerm];
    }
    
    public static function getSearchTerms() {
        return array_keys( self::$_terms );
    }
}

?>
