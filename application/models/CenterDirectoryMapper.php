<?php
/**
 * maps a computer center object to an entry in the Google Fusion Table 
 */
class Application_Model_CenterDirectoryMapper
{
    protected $_dbTableClassName = 'Application_Model_DbTable_GoogleFusionTable';
    
    protected $_dbTable;
    protected $logger;
    
    public function __construct() {
        $this->logger = Zend_Registry::get('Log');
    }
    
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $config = Zend_Registry::get( 'configuration' );
            
            $dbTable = new $dbTable( $config->gmap->FusionTableId,
                                     $config->google->user,
                                     $config->google->pass );
        }
        
        $this->_dbTable = $dbTable;
        return $this;
    }
    
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable($this->_dbTableClassName);
        }
        return $this->_dbTable;
    }
 
    public function save(Connect_ComputerCenter $computerCenter)
    {
        $data = array();
        
        foreach ($computerCenter->getOptions() as $key => $value ) {
                
            if( isset( $value ) && !preg_match( "/^\s*$/", $value ) ) {

                $method = 'get'.$key;
                $key = preg_replace('/(?<!\ )[A-Z]/', ' $0', $key);
                $key = ucfirst( $key );

                $data[$key] = addslashes( $computerCenter->$method() );
            }
        }
        
        if (null === ($id = $computerCenter->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
    
    public function getCentersByDistance( $searchLocation, $startRow, $count ) {

        $latitude = $searchLocation->getLatitude();
        $longitude = $searchLocation->getLongitude();

        $sql = "SELECT ((ACOS(SIN($latitude * PI() / 180) * SIN(latitude * PI() / 180) + COS($latitude * PI() / 180) * COS(latitude * PI() / 180) * COS(($longitude - center_directory.longitude) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)"
            . ' AS distance, center_directory.*'
            . ' FROM center_directory'
            . ' ORDER BY distance ASC'
            . " LIMIT $startRow, $count";
		
        $resultSet = $this->getDbTable()->getAdapter()->fetchAll( $sql );
        
        $searchResults = array();
		
        foreach( $resultSet as $row ) {

            $searchResult = new Application_Model_DistanceSearchResult();
            $computerCenter = new Application_Model_ComputerCenter( $row );

            $searchResult->setComputercenter( $computerCenter );
            $searchResult->setDistance( $row['distance'] );

            $url = Application_Model_GoogleDirectionsURL::getUrl( 
                                    $searchResult->getComputerCenter()->getAddress1() . ' Philadelphia, PA', 
                                    $searchLocation->getAddress1() . ' Philadelphia, PA'
                            );

            $searchResult->setDirectionsUrl( $url );
            array_push( $searchResults, $searchResult );
        }


        return $searchResults;
    }
	
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
           
        $computerCenters = array();
        
        $businessDayMapper = new Application_Model_BusinessDayMapper();
        
        foreach ($resultSet as $row) {
            
            $computerCenter = new Application_Model_ComputerCenter();
            $computerCenter->setOptions( $row->toArray() );
            
            $businessDays = $businessDayMapper->fetch( $computerCenter->getId() );
            
            $computerCenter->setBusinessDays( $businessDays );
            array_push( $computerCenters, $computerCenter );
        }
        return $computerCenters;
    }
}

