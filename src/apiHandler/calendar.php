<?php
namespace clientcal\apiHandler;

use clientcal\apiMethod;
use clientcal\apiResponse;

use clientcal\pdo;
use clientcal\apiHandler;
use clientcal\sentryData;

class calendar extends apiHandler implements apiMethod\GET {

   /**
    * provides response data for a GET request
    */
   public function responseGET(array $requestData): apiResponse {
      
      if (empty($requestData['month'])) {
         return new apiResponse(json_encode("missing 'month'"),"application/json",500);
      }
      
      $year = substr($requestData['month'],0,4);
      $monthNo = substr($requestData['month'],4);
      $isOk=true;
      if (strlen($year)!=4) {
         $isOk=false;
      } else
      if (sprintf("%d", $year)!=$year) {
         $isOk=false;
      } else
      if (strlen($monthNo)<1) {
         $isOk=false;
      } else
      if (sprintf("%d", $monthNo)!=$monthNo) {
         $isOk=false;
      }
      
      
      if (!$isOk) {
         return new apiResponse(json_encode("badly formatted 'month' field"),"application/json",500);
      }
      $pdo = new pdo;
      
      $stmt = $pdo->prepare("
         SELECT
            e.id,
            e.heading,
            e.startdate,
            e.starttime,
            e.sentrytype,
            e.last_updated,
            c.name customer_name,
            c.id customer_id
         FROM
            sentry e
         LEFT JOIN
            customer c
         ON
            c.id=e.customer
         WHERE
            e.listlevel=1
         AND
            MONTH(e.startdate)=:month
         AND
            YEAR(e.startdate)=:year
         ORDER BY
            e.startdate DESC,
            e.starttime DESC,
            e.sentrytype ASC
      ");
      $stmt->bindValue(":month", $monthNo,pdo::PARAM_INT);
      $stmt->bindValue(":year", $year,pdo::PARAM_INT);
      
      $stmt->execute();
      
      $sentry = [];
      while($row = $stmt->fetch(pdo::FETCH_ASSOC)) {
         $sentry []= new sentryData($row);
      }
      
      return new apiResponse(json_encode($sentry),"application/json",200);
   }
    
}











