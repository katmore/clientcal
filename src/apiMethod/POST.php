<?php
namespace clientcal\apiMethod;

use clientcal\apiMethod;
use clientcal\apiResponse;

interface POST extends apiMethod {
   /**
    * provides response data for a POST request
    */
   public function responsePOST(array $requestData): apiResponse;
}