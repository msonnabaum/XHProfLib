<?php

use XHProfLib\Runs;
use XHProfLib\Runs\FileRuns;

class FileRunsTest extends \XHProfLibTestCase {
  public $tmpdir;

  public function setUp() {
    $this->tmpdir = sys_get_temp_dir() . '/xhproflib_test';
    $ret = mkdir($this->tmpdir, 0777, TRUE);
    ini_set("xhprof.output_dir", $this->tmpdir);
  }

  public function tearDown() {
   return self::deleteRecursive($this->tmpdir);
  }

  /**
   * @test
   */
  public function saveRun() {
    $data = $this->xhprofData();
    $runs = new FileRuns($data, 'namespacetest');
    $save = $runs->saveRun($data, 'namespacetest', 1234);
    $file = $this->tmpdir . '/1234.namespacetest.xhprof';

    if (file_exists($file)) {
      $saved_data = unserialize(file_get_contents($file));
    }

    $this->assertEquals($data, $saved_data);

  }
}

