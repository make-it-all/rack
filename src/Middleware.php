<?php namespace Rack;

class Middleware {

  private $middleware;
  private $instance;
  private $args;

  public function __construct($middleware, $args) {
    $this->middleware = $middleware;
    $this->args = $args;
  }

  public function setNext(Middleware $middleware = null) {
    $this->next = $middleware;
  }

  public function call($env) {
    if ($this->instance == null) {
      $this->instance = new $this->middleware($this->next, ...$this->args);
    }
    return $this->instance->call($env);
  }

}
