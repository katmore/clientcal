<?php
namespace clientcal;

class config implements \ArrayAccess {
   
   const DEFAULT_BASE_DIR = __DIR__.'/../app/config/clientcal';
   
   const ASSIGN_GLOBAL=0x1;
   
   /**
    * @var string
    */
   private static $_baseDir;
   
   
   public function offsetExists (  $offset ) {
      try {
         $this->getValue($offset);
      } catch (invalidConfigKey $e) {
         return false;
      }
      return true;
   }
   public function offsetGet (  $offset ) {
      $value = null;
      try {
         $value = $this->getValue($offset);
      } catch (invalidConfigKey $e) {
         
      }
      return $value;
   }
   public function offsetSet (  $offset ,  $value ) {
      
   }
   public function offsetUnset (  $offset ) {
      
   }
   
   /**
    * @return void
    */
   public static function SetBaseDir(string $configDir) {
      if (!file_exists($configDir)) {
         throw new invalidConfigDir("the path specified for the configDir does not exist", $configDir);
      }
      if (!is_dir($configDir)) {
         throw new invalidConfigDir("the path specified for the configDir is not a directory", $configDir);
      }
      if (!is_readable($configDir)) {
         throw new invalidConfigDir("the path specified for the configDir is not readable", $configDir);
      }
      
      self::_loadDir($configDir);
      
      self::$_baseDir = $configDir;
      
   }
   
   public static function EnumConfigNames() {
      return self::$_configNameList;
   }
   
   /**
    * @var string[]
    */
   private static $_configNameList=[];
   /**
    * @return void
    */
   private static function _loadDir(string $dirPath,$namePrefix="") {
      foreach(array_diff(scandir($dirPath), ['..', '.']) as $f) {
         $path = $dirPath."/$f";
          if (is_dir($path) && is_readable($path)) {
             self::_loadDir($path,"$f/");
          } else {
             if (is_file($path) && is_readable($path) && (pathinfo($f,PATHINFO_EXTENSION )=='php') && (substr(pathinfo($f,PATHINFO_FILENAME ),strlen('-sample')*-1)!=='-sample')) {
                $config = require $path;
                if (!is_array($config)) {
                   throw new invalidConfig("the config file for the specified configName did not return an array", $configName, $configFile);
                }
                self::$_configNameList[]=$namePrefix.pathinfo($f,PATHINFO_FILENAME );
             }
          }
      }
   }
   
   
   
   /**
    * @return mixed
    */
   public function getValue(string $configKey) {
      if (!isset($this->_assoc[$configKey])) {
         throw new invalidConfigKey("the specified config key does not exist", $this->_name, $this->_file, $configKey);
      }  
      return $this->_assoc[$configKey];
   }
   /**
    * @return array
    */
   public function getAssoc() :array {
      return $this->_assoc;
   }
   
   /**
    * @var array
    *    config values
    */
   private $_assoc;
   
   /**
    * @var string
    *    config 'name'
    */
   private $_name;
   
   /**
    * @var string
    *    config file
    */
   private $_file;
   /**
    * @param string $configName config 'name'
    * @param int $flags (optional) 
    *    \clientcal\config::ASSIGN_GLOBAL_VALUES will assign 
    *    global variables to config values using corresponding 
    *    config keys as variable name.
    */
   public function __construct(string $configName,int $flags=0) {
      if (!static::$_baseDir) static::SetConfigDir(static::DEFAULT_BASE_DIR);
      $this->_file = $configFile = static::$_baseDir."/$configName.php";
      $this->_assoc = static::LoadAssoc($this->_name = $configName);
      if ($flags & static::ASSIGN_GLOBAL) {
         static::AssignGlobal($configName);
      }
   }
   /**
    * @return void
    */
   public static function AssignGlobal(string $configName) {
      foreach(require(self::$_baseDir."/$configName.php") as $k=>$v) {
         global $$k;
         $$k=$v;
      }
   }
   
   /**
    * @param string $configName config 'name'
    * @return array assoc array of config values
    */
   public static function LoadAssoc(string $configName) :array {
      if (!in_array($configName,static::$_configNameList,true)) {
         throw new invalidConfig("the specified configName does not exist", $configName, "$configName.php");
      }
      $config=[];
      foreach(require(self::$_baseDir."/$configName.php") as $k=>$v) {
         if (!is_string($k)) {
            throw new invalidConfigKey("each key of the array returned by a config file definition must be a string type", $configName, $configFile, $k);
         }
         $config[$k]=$v;
      }
      unset($k);
      unset($v);
      
      return $config;
   }
   
}