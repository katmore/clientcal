<?php
namespace clientcal\data;

class sentryData {
   
   public $id;
   
   public $label;
   
   public $updated;
   
   public $customer_id;
   
   public $customer_full_name;
   
   public $customer_name_data;
   
   public $customer_phone;
   public $customer_phone_type;
   
   public $type;
   
   public $time;
   
   public $streetaddr;
   public $city;
   public $state;
   public $zip;
   public $directions;
   public $lat;
   public $lon;
   
   protected $customer_name;
   protected $last_updated;
   protected $heading;
   protected $sentrytype;
   protected $starttime;
   protected $startdate;
   
   protected $sdirections;
   
   public function __construct(array $input=[],array $customer_config=[]) {

      foreach(get_object_vars ($this) as $p=>$v) if (isset($input[$p])) $this->$p=$input[$p];
      unset($p);
      unset($v);
      
      foreach(['id','customer_id'] as $prop) {
         if (!empty($this->$prop)) {
            if (sprintf("%d",$this->$prop)==$this->$prop) {
               $this->$prop = (int) $this->$prop;
            }
         }
      }
      unset($prop);
      
      if (empty($updated) && !empty($this->last_updated)) {
         $this->updated = date("c",strtotime($this->last_updated));
      }
      
      if (empty($this->customer_name_data) && !empty($this->customer_name)) {
         $this->customer_name_data = explode(",", $this->customer_name);
         foreach($this->customer_name_data as &$v) $v=trim($v);
         unset($v);
      }
      
      if (empty($this->customer_full_name) && !empty($this->customer_name_data)) {
         $this->customer_full_name = implode(" ", array_reverse($this->customer_name_data));
      }
      
      if (empty($this->label) && !empty($this->customer_full_name)) {
         $this->label = $this->customer_full_name;
      }
      
      if (!empty($this->heading)) {
         $label = [$this->label];
         $label[]=$this->heading;
         $this->label = implode(" - ",$label);
      }
      
      if (empty($this->type) && !empty($this->sentrytype)) {
         $this->type = $this->sentrytype;
      }
      
      if (empty($this->time) && !empty($this->startdate)) {
         $time = $this->startdate;
         if (!empty($this->starttime)) {
            $time .= ' '.$this->starttime;
         } else {
            $time .= " 00:00:00";
         }
         $this->time = date('c',strtotime($time));
      }
      
      if (empty($this->directions) && !empty($this->sdirections)) {
         $this->directions = $this->sdirections;
      }
      
      
      if (!empty($this->customer_phone)) {
         $origCustomerPhone = $this->customer_phone;
         $this->customer_phone = preg_replace('/[^0-9]/', '', $this->customer_phone);
         if ((strlen($this->customer_phone)==7) && !empty($customer_config['default_customerareacode'])) {
            $this->customer_phone = $customer_config['default_customerareacode'].$this->customer_phone;
         }
         if (strlen($this->customer_phone)==10) {
            $this->customer_phone = vsprintf("%s%s%s-%s%s%s-%s%s%s%s", str_split($this->customer_phone));
         }
      }
      
   }
}













