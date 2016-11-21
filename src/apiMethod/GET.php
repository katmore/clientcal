<?php
namespace clientcal\apiMethod;

use clientcal\apiMethod;
use clientcal\apiResponse;

interface GET extends apiMethod {
   /**
    * provides response data for a GET request
    */
   public function responseGET(array $requestData): apiResponse;
}