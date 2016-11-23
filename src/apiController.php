<?php
namespace clientcal;

abstract class apiController {

   /*
    * @var \clientcal\apiHandler
    */
   //private $_apiHandler;
   
//    final protected function getApiHandler() : apiHandler {
//       return $this->_apiHandler;
//    }
   
   
   
   public function __construct(apiHandler $apiHandler) {

      $this->_apiHandler = $apiHandler;
      $this->_setResponse($apiHandler->getResponse($this->_getRequestMethod(), $this->_getRequestData()));
   }
   
   abstract protected function _setResponse(apiResponse $response);
   
   abstract protected function _getRequestData() :array;
   
   abstract protected function _getRequestBody() :string;
   
   abstract protected function _getRequestMethod() :string;
   
}