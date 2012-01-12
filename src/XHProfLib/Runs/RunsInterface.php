<?php

namespace XHProfLib\Runs;

interface RunsInterface {
  public function getRuns();
  public function getRun($run_id, $namespace);
}
