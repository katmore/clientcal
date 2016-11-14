<?php
namespace clientcal;

use \LogicException ;

class invalidConfigKey extends LogicException  {
   /**
    * @return string
    */
   public function getReason():string {
      return $this->_reason;
   }
   /**
    * @var string
    */
   private $_reason;
   
   /**
    * @return string
    */
   public function getConfigName():string {
      return $this->_configName;
   }
   /**
    * @var string
    */
   private $_configName;
   
   /**
    * @return string
    */
   public function getConfigFile():string {
      return $this->_configFile;
   }
   /**
    * @var string
    */
   private $_configFile;
   
   /**
    * @return string
    */
   public function getConfigKey():string {
      return $this->_configKey;
   }
   /**
    * @var string
    */
   private $_configKey;
   
   public function __construct(string $reason,string $configName,string $configFile,string $configKey) {
      $this->_reason = $reason;
      $this->_configName = $configName;
      $this->_configFile = $configFile;
      $this->_configKey = $configKey;
      parent::__construct("invalid config key: ".$reason);
   }
}