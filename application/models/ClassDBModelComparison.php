<?php
/**
 * A set of functions that compare a class against the column names of a table
 * 
 */
class Application_Model_ClassDBModelComparison
{
    protected $columnNames;
    protected $className;
    
    protected $classVariables;
    protected $classFunctions;
    
    function __construct( $className, $columnNames ) {
        $this->logger = Zend_Registry::get('log');
        
        $this->columnNames = $columNames;
        $this->className = $className;
        

    }
   
    /**
     * check that for each column in the table, that there is a get and set method in <i>class</i> for it
     */
    public function checkFunctionsForColumns() {
        
        $this->logger->debug( __Function__ . " started" );
        
        $methods = get_class_methods( $this->className );

        foreach( $this->columns as $column ) {

            $columnName = $column['name'];
            $columnName = str_replace( " ", "", $columnName );
            
            $testMethod = 'get'.$columnName;
            if( method_exists( $this->className, $testMethod ) ) {
                //print "method $testMethod exists in class\n";
            }
            else {
                $this->logger->info( "method $testMethod does not exist" );
            }
            
            $testMethod = 'set'.$columnName;
            if( method_exists( $this->className, $testMethod ) ) {
                //print "method $testMethod exists in class\n";
            }
            else {
                $this->logger->info( "method $testMethod does not exist" );
            }
        }
        
        $this->logger->debug( __Function__ . " ended" );
    }
    
    /**
     * checks all of the variables against the column names in the table
     */
    function checkVariables() {
        $this->logger->debug( __Function__ . " started" );
        $obj = new $this->className;
        
        $variables = array_keys( $obj->getOptions() );
        
        $columnNames = $this->getColumnNames( $this->columns );
        //print_r( $columnNames );
        
        $atLeastOneFound = false;
        foreach( $variables as $variable ) {
            
            // place a space in front of capital letters
            $variable = preg_replace( '/([A-Z])/', " $1", $variable );
            
            $variable = ucfirst( $variable );
            
            if( !in_array( $variable, $columnNames ) ) {
                $this->logger->info( "variable '$variable' not defined in table" );
                $atLeastOneFound = true;
            }
        }
        
        if( !$atLeastOneFound ) {
            $this->logger->info( "all variables exist as table columns" );
        }
        
        $this->logger->debug( __Function__ . " ended" );
    }
    

}

