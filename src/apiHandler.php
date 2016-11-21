<?php
namespace clientcal;

abstract class apiHandler {
   
   public function getResponse(string $requestMethod,array $requestData) : apiResponse {
   
      if ($requestMethod=='GET' && ($this instanceof apiMethod\GET)) {
         return $this->responseGET($requestData);
      }
   
      if ($requestMethod=='DELETE' && ($this instanceof apiMethod\DELETE)) {
         return $this->responseDELETE($requestData);
      }
   
      if ($requestMethod=='POST' && ($this instanceof apiMethod\POST)) {
         return $this->responsePOST($requestData);
      }
   
      if ($requestMethod=='PUT' && ($this instanceof apiMethod\PUT)) {
         return $this->responsePUT($requestData);
      }
   
      throw new apiResponse(405,['message'=>'request method "'.$requestMethod. '" is not allowed by apiHandler "'. get_class($apiHandler). '"']);
   }
   
}