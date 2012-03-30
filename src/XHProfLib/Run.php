<?php

namespace XHProfLib;

use XHProfLib\Parser\Parser;

class Run {
  public $run_id;
  public $namespace;
  public $data = array();
  public $parser;

  public function __construct($run_id, $namespace, $data) {
    $this->run_id = $run_id;
    $this->$namespace = $namespace;
    $this->data = $data;
  }
}
