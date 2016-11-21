<?php
namespace clientcal;

use RuntimeException;

class apiResponse extends RuntimeException {
   /**
    * @return int
    */
   public function getHttpResponseCode() :int {
      return $this->_httpResponseCode;
   }
   /**
    * @var int
    */
   private $_httpResponseCode;
   
   /**
    * @return array
    */
   public function getData()  {
      return $this->_data;
   }
   /**
    * @array
    */
   private $_data;
   
   public function __construct(int $httpResponseCode, array $data=null) {
      $this->_httpResponseCode = $httpResponseCode;
      $this->_data = $data;
   }
}









