<?php
namespace clientcal\apiMethod;

use clientcal\apiMethod;

interface methodPOST extends apiMethod {
   /**
    * provides response data for a POST request
    */
   public function responsePOST(array $requestData): array;
}