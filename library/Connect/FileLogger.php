<?php

class Connect_FileLogger {
    /**
     *
     * @var Zend_Log
     */
    protected $logger;

    /**
     * @var ZC_FileLogger
     */
    static $fileLogger = null;

    public static function getInstance()
    {
        if (self::$fileLogger === null)
        {
            self::$fileLogger = new self();
        }
        return self::$fileLogger;
    }
    /**
     *
     * @return Zend_Log
     */
    public function getLog()
    {
        return $this->logger;
    }

    
    protected function __construct()
    {
        $this->logger = Zend_Registry::get('Log');
    }
	
    /**
     * log a message
     * @param string $message
     */
    public static function debug($message)
    {
        self::getInstance()->getLog()->debug($message);
    }
	
    /**
     * log a message
     * @param string $message
     */
    public static function info($message)
    {
        self::getInstance()->getLog()->info($message);
    }

    /**
     * log a message
     * @param string $message
     */
    public static function warn($message)
    {
        self::getInstance()->getLog()->warn($message);
    }
    
    /**
     * logs to err the message
     *
     * @param string $message 
     */
    public static function error($message)
    {
        self::getInstance()->getLog()->err($message);
    }
    
    /**
     * logs to err the message
     *
     * @param string $message 
     */
    public static function err($message)
    {
        self::getInstance()->getLog()->err($message);
    }
    
    /**
     * log a message to critical
     * @param string $message
     */
    public static function crit($message)
    {
        self::getInstance()->getLog()->crit($message);
    }
}
?>