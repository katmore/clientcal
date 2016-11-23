<?php
namespace clientcal\api;

use clientcal\apiMethod;
use clientcal\apiResponse;
use clientcal\apiHandler;

use clientcal\pdo;
use clientcal\data\sentryData;
use clientcal\config;
class sentryApi extends apiHandler implements apiMethod\PUT {
   
   public function responsePUT(array $requestData): apiResponse {

      if (empty($requestData['id'])) {
         return new apiResponse(json_encode("missing 'id'"),"application/json",400);
      }
      
      if (sprintf("%d",$requestData['id'])==$requestData['id']) {
         $sentryId = (int) $requestData['id'];
      } else {
         return new apiResponse(json_encode("malformed 'id'"),"application/json",400);
      }
      
      
      
      $fields = [];
      
      $sentrySetClause = [];
      $sentryBindVal = [];
      foreach(['heading','notes','startdate'=>'date','starttime'=>'time',] as $a=>$f) {
         if (!empty($requestData[$f])) {
            if (!empty($a) && !is_int($a)) {
               $p=$a;
            } else {
               $p=$f;
            }
            $fields[]=$f;
            $sentrySetClause[]="$p=:$p";
            $sentryBindVal[":$p"]=$requestData[$f];
         }
      }
      unset($f);
      unset($a);
      unset($p);
      
      $siteSetClause = [];
      $siteBindVal = [];
      foreach(['streetaddr','city','state','zip','sdirections'=>'directions'] as $a=>$f) {
         if (!empty($requestData[$f])) {
            if (!empty($a) && !is_int($a)) {
               $p=$a;
            } else {
               $p=$f;
            }
            
            $fields[]=$p;
            $siteSetClause[]="$p=:$p";
            $siteBindVal[":$p"]=$requestData[$f];
         }
      }
      unset($f);
      unset($a);
      unset($p);
      
      
      if (!count($fields)) {
         return new apiResponse(json_encode("must include one or more fields to edit"),"application/json",400);
      }
      
      $pdo = new pdo;
      $pdo->beginTransaction();
      register_shutdown_function(function() use(&$pdo) {
         if ($pdo->inTransaction()) {
            $pdo->rollBack();
         }
      });
      
      $updated=null;
      if (count($sentrySetClause)) {
         $stmt = $pdo->prepare("
               UPDATE
                  sentry
               SET
                  ".implode(",", $sentrySetClause)."
               WHERE
                  id=:sentry_id
         ");
         $stmt->bindValue(":sentry_id", $sentryId,pdo::PARAM_INT);
         foreach($sentryBindVal as $param=>$val) {
            $stmt->bindValue($param, $val);
         }
         $stmt->execute();
         if ($stmt->rowCount()) $updated=true;
      }
      
      if (count($siteSetClause)) {
         $stmt = $pdo->prepare("
               UPDATE
                  site
               SET
                  ".implode(",", $siteSetClause)."
               WHERE
                  sentry=:sentry_id
         ");
         $stmt->bindValue(":sentry_id", $sentryId,pdo::PARAM_INT);
         foreach($siteBindVal as $param=>$val) {
            $stmt->bindValue($param, $val);
         }
         $stmt->execute();
         if ($stmt->rowCount()) $updated=true;
      }
      
      $pdo->commit();
      
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
            e.id=:sentry_id
         ORDER BY
            e.startdate ASC,
            e.starttime ASC,
            e.sentrytype ASC
      ");
      $stmt->bindValue(":sentry_id", $sentryId,pdo::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(pdo::FETCH_ASSOC);
      
      if ($updated) $updated = $fields;
      return new apiResponse(json_encode(['updated'=>$updated,'sentry'=>new sentryData($row,(new config('customer'))->getAssoc())]),"application/json",200);
      
   }
   
}



















