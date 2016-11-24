<?php
use clientcal\pdo;
use clientcal\data;
use clientcal\config;

return new class() {
   const RUNTIME_LIMIT=30;
   private function _errorReponse(string $message,int $statusCode) {
      http_response_code($statusCode);
      header('Content-Type: text/plain');
      echo $message;
   }
   private function _publishEvent(string $event,array $payload) {
      echo "data: " . json_encode(['event'=>$event,'time'=>date('c'),'month'=>$this->monthNo,'year'=>$this->year,'payload'=>$payload])."\n\n";
      flush();
   }
   private $monthNo;
   private $year;
   public function __construct() {
      if ($_SERVER['REQUEST_METHOD']!='GET') {
         return $this->_errorReponse("method not allowed",405);
      }
      
      if (empty($_GET['month'])) {
         return $this->_errorReponse("missing 'month'",400);
      }
      
      $year = substr($_GET['month'],0,4);
      $monthNo = substr($_GET['month'],4);
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
         return $this->_errorReponse("badly formatted 'month",400);
      }
      
      $this->monthNo=$monthNo;
      $this->year=$year;
      set_time_limit(0);
      
      header('Content-Type: text/event-stream');
      header('Cache-Control: no-cache');
      header( 'Content-Encoding: none; ' );
      header('X-Accel-Buffering: no');
      
      
      $startTime = time();
      $lastHash=null;
      $lastHashSet=[];
      for(;;) {
      
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
         
         $sentry=[];
         $thisHashSet=[];
         $sentryForHash=[];
         while($row = $stmt->fetch(pdo::FETCH_ASSOC)) {
            
            $data= new data\sentryData($row,(new config('customer'))->getAssoc());
            $thisHashSet[$data->id] = md5(json_encode($data));
            $sentry[$data->id]=$data;
            $sentryForHash[]= $thisHashSet[$data->id];
         }
         unset($row);
         unset($data);
         $stmt->closeCursor();
         $stmt=null;
         
         $thisHash=md5(json_encode($sentryForHash));
         if (!$lastHash) {
            $this->_publishEvent('calendarWatchStarted', ['summaryDigest'=>$thisHash,]);
         } else {
            if ($lastHash!=$thisHash) {
               $changed=[];
               foreach($thisHashSet as $id=>$h) {
                  if ($lastHashSet[$id]!=$h) {
                     $changed[$id]=$sentry[$id];
                  }
               }
               $this->_publishEvent('calendarChanged', $changed);
               unset($changed);
            }
         }
         
         $lastHashSet=$thisHashSet;
         $lastHash=$thisHash;
         try {
            $pdo->query("KILL CONNECTION_ID()");
         } catch (\PDOException $e) {
            
         }
         
         $pdo=null;
         unset($thisHashSet);
         unset($thisHash);
         unset($sentry);
         
         if ((time()-$startTime)>=self::RUNTIME_LIMIT) break 1;
         
         sleep(5);
      
      }
      
      
      
      
   }
};


















