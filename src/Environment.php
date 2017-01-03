<?php namespace Rack;

class Environment {

  public $method;

  function __construct() {
    $this->method = $_SERVER['REQUEST_METHOD'];
  }

}
