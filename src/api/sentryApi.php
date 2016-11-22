<?php
namespace clientcal\api;

use clientcal\apiMethod;
use clientcal\apiResponse;
use clientcal\apiHandler;

use clientcal\pdo;

class sentryApi extends apiHandler implements apiMethod\PUT {
   
   public function responsePUT(array $requestData): string {
      
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
      foreach(['heading','notes','startdate','starttime',] as $f) {
         if (!empty($requestData[$f])) {
            $fields[]=$f;
            $sentrySetClause[]="$f=:$f";
            $sentryBindVal[":$f"]=$requestData[$f];
         }
      }
      unset($f);
      
      $siteSetClause = [];
      $siteBindVal = [];
      foreach(['streetaddr','city','state','zip','sdirections'=>'directions'] as $a=>$f) {
         if (!empty($requestData[$f])) {
            $fields[]=$f;
            
            if (!empty($a) && !is_int($a)) {
               $p=$a;
            } else {
               $p=$f;
            }
            
            $siteSetClause[]="$p=:$p";
            $siteBindVal[":$p"]=$requestData[$p];
         }
      }
      
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
      }
      
      if (count($siteSetClause)) {
         $stmt = $pdo->prepare("
               UPDATE
                  site
               SET
                  ".implode(",", $siteSetClause)."
               WHERE
                  id=:site_id
         ");
         $stmt->bindValue(":site_id", $siteId,pdo::PARAM_INT);
         foreach($siteBindVal as $param=>$val) {
            $stmt->bindValue($param, $val);
         }
         $stmt->execute();
         if ($stmt->rowCount()) $updated=true;
      }
      
      $pdo->commit();
      
      if ($updated) $updated = $fields;
      return new apiResponse(json_encode(['updated'=>$updated]),"application/json",200);
      
   }
   
}



















