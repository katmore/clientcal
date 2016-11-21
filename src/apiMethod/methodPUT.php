<?php
namespace clientcal\apiMethod;

use clientcal\apiMethod;

interface methodPUT extends apiMethod {
   /**
    * provides response data for a PUT request
    */
   public function responsePUT(array $requestData): array;
}