<?php
namespace clientcal;

use RuntimeException;

class error extends RuntimeException  {
   /**
    * @return int
    */
   public function getErrno() :int {
      return (int) $this->_errno;
   }
   /**
    * @return string
    */
   public function getErrmsg() :string {
      return $this->_errmsg;
   }
   /**
    * @var int
    */
   private $_errno;
   /**
    * @var string
    */
   private $_errmsg;
   /**
    * @param int $errno Error Number
    * @param string $errmsg Error Message
    */
   public function __construct(int $errno,string $errmsg) {
      $this->_errno = $errno;
      $this->_errmsg = $errmsg;
      parent::__construct($errmsg,abs($errno));
   }
   
}

































