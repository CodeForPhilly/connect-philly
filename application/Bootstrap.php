<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initPlaceholders()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');
        $view->doctype('XHTML1_STRICT');
        
        // Set the initial title and separator:
        $view->headTitle( 'Connect Philly' )
             ->setSeparator(' :: ');
    }
	
    /**
     *load the variables in connect.ini 
     */
    protected function _initConnectIni() {
        
        $configuration = new Zend_Config_Ini( 
                APPLICATION_PATH . '/configs/connect.ini', 
                APPLICATION_ENV );
        
        Zend_Registry::set('configuration',$configuration );
    }
    
    public function _initTimezone() {
        date_default_timezone_set("America/New_York");
    }
    
    protected function _initLog()
    {
        if ($this->hasPluginResource("Log"))
        {
            $r = $this->getPluginResource("Log");
            $log = $r->getLog();
            
            if( isset($_SERVER['SERVER_NAME'])
                    && $_SERVER['SERVER_NAME'] == 'connect.technicallyphilly.com' ) {
                $filter = new Zend_Log_Filter_Priority(Zend_Log::INFO);
                $log->addFilter($filter);    
            }
            
            Zend_Registry::set('Log',$log);
        }
    }
    
    protected function getLogger( $filename ) {
        $logger = new Zend_Log();
        $logger->setTimestampFormat("ymd H:i");
        
        $fileWriter = new Zend_Log_Writer_Stream( $filename );
        $logger->addWriter($fileWriter);
        
        return $logger;
    }
    
    public function setSubsiteLayout() {
        Zend_Layout::getMvcInstance()->setLayout('subsite'); 
    }
    
    public function setForwardLayout() {
        Zend_Layout::getMvcInstance()->setLayout('forward'); 
    }
}