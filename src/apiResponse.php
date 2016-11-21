<?php
namespace clientcal;

class apiResponse {
   
   public function getBody() :string {
      return $this->_body;
   }
   
   public function getContentType() : string {
      return $this->_contentType;
   }
   
   public function getStatusCode() : int {
      return $this->_statusCode;
   }
   
   /**
    * @var string
    */
   private $_body;
   
   /**
    * @var string
    */
   private $_contentType;
   
   /**
    * @var int
    */
   private $_statusCode;
   public function __construct( string $body, string $contentType, int $statusCode) {
      $this->_body=$body;
      $this->_contentType=$contentType;
      $this->_statusCode=$statusCode;
   }
   
}



































