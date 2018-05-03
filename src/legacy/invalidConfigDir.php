<?php
namespace clientcal;

use \LogicException;

class invalidConfigDir extends LogicException {
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
   public function getConfigDir():string {
      return $this->_configDir;
   }
   /**
    * @var string
    */
   private $_configDir;
   
   public function __construct(string $reason,string $configDir) {
      $this->_reason = $reason;
      $this->_configDir = $configDir;
      parent::__construct("invalid config dir: ".$reason);
   }
}