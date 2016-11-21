<?php
namespace clientcal\apiHandler;

use clientcal\apiMethod;

class sentry implements apiMethod\GET {
   
   public function responseGET(array $requestData): string {
      
   }
   
   public function getContentType() : string {
      return "application/json";
   }
   
}