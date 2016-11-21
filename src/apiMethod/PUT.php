<?php
namespace clientcal\apiMethod;

use clientcal\apiMethod;
use clientcal\apiResponse;

interface PUT extends apiMethod {
   /**
    * provides response data for a PUT request
    */
   public function responsePUT(array $requestData): apiResponse;
}