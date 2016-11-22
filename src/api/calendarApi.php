<?php
namespace clientcal\api;

use clientcal\apiMethod;
use clientcal\apiResponse;

use clientcal\pdo;
use clientcal\apiHandler;
use clientcal\data\sentryData;

use clientcal\config;

class calendarApi extends apiHandler implements apiMethod\GET {

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
      /*
   public $streetaddr;
   public $city;
   public $state;
   public $zip;
   public $directions;
   public $lat;
   public $lon;
       */
      $stmt = $pdo->prepare("
         SELECT
            e.id,
            e.heading,
            e.startdate,
            e.starttime,
            e.sentrytype,
            e.last_updated,
            c.name customer_name,
            c.id customer_id,
            s.streetaddr,
            s.city,
            s.state,
            s.zip,
            s.sdirections,
            s.lat,
            s.lon,
            cph.number customer_phone,
            cph.type customer_phone_type
         FROM
            sentry e
         LEFT JOIN
            customer c
         ON
            c.id=e.customer
         LEFT JOIN
            customer_phone cph
         ON
            cph.customer=c.id
         AND
            cph.type=c.primaryphonetype
         LEFT JOIN
            site s
         ON
            s.sentry=e.id
         WHERE
            e.listlevel=1
         AND
            MONTH(e.startdate)=:month
         AND
            YEAR(e.startdate)=:year
         ORDER BY
            e.startdate ASC,
            e.starttime ASC,
            e.sentrytype ASC
      ");
      $stmt->bindValue(":month", $monthNo,pdo::PARAM_INT);
      $stmt->bindValue(":year", $year,pdo::PARAM_INT);
      
      $stmt->execute();
      
      $sentry = [];
      while($row = $stmt->fetch(pdo::FETCH_ASSOC)) {
         $sentry []= new sentryData($row,(new config('customer'))->getAssoc());
      }
      
      return new apiResponse(json_encode($sentry),"application/json",200);
   }
    
}











