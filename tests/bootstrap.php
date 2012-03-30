<?php

spl_autoload_register(function($class) {
  $file = __DIR__.'/../src/'.strtr($class, '\\', '/').'.php';
  if (file_exists($file)) {
    require $file;
    return true;
  }
});


class XHProfLibTestCase extends \PHPUnit_Framework_TestCase {
  public $tmpdir;

  public function xhprofData() {
    return array(
      "function_two()==>function_three" => array ( 
        "ct" => 1,
        "wt" => 137,
        "cpu" => 221,
        "mu" => 952,
        "pmu" => 0,
      ),
      "function_one()==>function_two" => array (
        "ct" => 2,
        "wt" => 6,
        "cpu" => 9,
        "mu" => 1480,
        "pmu" => 0,
      ),
      "main()==>function_one" => array (
        "ct" => 2,
        "wt" => 6,
        "cpu" => 9,
        "mu" => 1480,
        "pmu" => 0,
      ),
      "main()" => array (
        "ct" => 1,
        "wt" => 1546311,
        "cpu" => 642577,
        "mu" => 52600504,
        "pmu" => 57486368,
      )
    );
  }

  static function deleteRecursive($dir) {
    if (!file_exists($dir)) {
      return TRUE;
    }
    @chmod($dir, 0777);
    if (!is_dir($dir)) {
      return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
      if ($item == '.' || $item == '..') {
        continue;
      }
      if (!self::deleteRecursive($dir.'/'.$item)) {
        return FALSE;
      }
    }
    return rmdir($dir);
  }
}

