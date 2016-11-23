<?php
namespace clientcal\apiController;

use clientcal\apiController;
use clientcal\apiResponse;

class httpInterface extends apiController {
   
   protected function _setResponse(apiResponse $response) {
      http_response_code($response->getStatusCode());
      header('Content-Type: '.$response->getContentType());
      echo $response->getBody();
   }
   private $_requestBody;
   protected function _getRequestBody() : string {
      if ($this->_requestBody===null) {
         return $this->_requestBody = file_get_contents('php://input');
      }
      return $this->_requestBody;
   }
   protected function _getRequestData() : array {
      if ($this->_getRequestMethod()=='GET') {
         return $_GET;
      } else {
         if (substr($_SERVER["CONTENT_TYPE"],0,strlen('application/json'))=='application/json') {
            if ($arr = json_decode($this->_getRequestBody(),true)) {
               return $arr;
            }
         } else
         if (substr($_SERVER["CONTENT_TYPE"],0,strlen('application/x-www-form-urlencoded'))=='application/x-www-form-urlencoded') {
            parse_str($this->_getRequestBody(),$arr);
            return $arr;
         } else {
            return $_REQUEST;
         }
         return [];
      }
   }
   
   protected function _getRequestMethod() :string {
      return $_SERVER['REQUEST_METHOD'];
   }
   
}