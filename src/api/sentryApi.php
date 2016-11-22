<?php
namespace clientcal\api;

use clientcal\apiMethod;

class sentryApi implements apiMethod\GET {
   
   public function responseGET(array $requestData): string {
      
   }
   
   public function getContentType() : string {
      return "application/json";
   }
   
}