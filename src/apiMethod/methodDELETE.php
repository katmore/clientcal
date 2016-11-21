<?php
namespace clientcal\apiMethod;

use clientcal\apiMethod;

interface methodDELETE extends apiMethod{
   /**
    * provides response data for a DELETE request
    */
   public function responseDELETE(array $requestData): array;
}