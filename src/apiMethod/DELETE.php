<?php
namespace clientcal\apiMethod;

use clientcal\apiMethod;
use clientcal\apiResponse;

interface DELETE extends apiMethod{
   /**
    * provides response data for a DELETE request
    */
   public function responseDELETE(array $requestData): apiResponse;
}