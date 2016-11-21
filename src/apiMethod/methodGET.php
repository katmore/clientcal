<?php
namespace clientcal\apiMethod;

use clientcal\apiMethod;

interface methodGET extends apiMethod {
   /**
    * provides response data for a GET request
    */
   public function responseGET(array $requestData): array;
}