<?php

namespace XHProfLib\Runs;

use XHProfLib\Run;

class FileRuns implements RunsInterface {
  private $dir;
  private $suffix;

  public function __construct($dir = NULL) {
    if ($dir) {
      $this->dir = $dir;
    }
    else {
      $this->dir = ini_get("xhprof.output_dir") ?: sys_get_temp_dir();
    }
    $this->suffix = 'xhprof';
  }

  private function runId($type) {
    return uniqid();
  }

  private function fileName($run_id, $namespace) {
    $file = implode('.', array($run_id, $namespace, $this->suffix));

    if (!empty($this->dir)) {
      $file = $this->dir . "/" . $file;
    }
    return $file;
  }

  public function getRun($run_id, $namespace) {
    $file_name = $this->fileName($run_id, $namespace);

    if (!file_exists($file_name)) {
      throw new \Exception("Could not find file $file_name");
    }

    $contents = file_get_contents($file_name);
    $run = new Run($run_id, $namespace, unserialize($contents));
    return $run;
  }

  public function getRuns($namespace = NULL) {
    $files = $this->scanXHProfDir($this->dir, $namespace);
    $files = array_map(function($f) {
        $f['date'] = strtotime($f['date']);
        return $f;
      }, $files);
    return $files;
  }

  /**
   * Mostly borrowed from the original XHProfRuns_Default class.
   */
  public function saveRun($data, $namespace, $run_id = NULL) {
    // Use PHP serialize function to store the XHProf's
    // raw profiler data.
    $data = serialize($data);

    if ($run_id === NULL) {
      $run_id = $this->runId($namespace);
    }

    $file_name = $this->fileName($run_id, $namespace);
    $file = fopen($file_name, 'w');

    if ($file) {
      fwrite($file, $data);
      fclose($file);
    }
    else {
      throw new \Exception("Could not open $file_name\n");
    }

    return $run_id;
  }

  public function scanXHProfDir($dir, $namespace = NULL) {
    if (is_dir($dir)) {
      $runs = array();
      foreach (glob("{$this->dir}/*.{$this->suffix}") as $file) {
        preg_match("/(?:(?<run>\w+)\.)(?:(?<namespace>.+)\.)(?<ext>\w+)/", basename($file), $matches);
        $runs[] = array(
          'run_id' => $matches['run'],
          'namespace' => $matches['namespace'],
          'basename' => htmlentities(basename($file)),
          'date' => date("Y-m-d H:i:s", filemtime($file)),
        );
      }
    }
    return array_reverse($runs);
  }
}

