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
   
   protected function _getRequestData() : array {
      if ($_SERVER['REQUEST_METHOD']=='GET') {
         return $_GET;
      } else {
         if ($_SERVER["CONTENT_TYPE"]=='application/json') {
            if ($arr = json_decode(file_get_contents('php://input'),true)) {
               return $arr;
            }
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