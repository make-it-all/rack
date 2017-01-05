<?php

class Rack {

  static $middlewares = [];
  private static $environment_class = 'Rack\Environment';

  public static function add($middleware, ...$args) {
    self::$middlewares[] = new Rack\Middleware($middleware, $args);
  }

  public static function run() {
    $init = self::create_chaining();
    $env = new self::$environment_class();
    ob_start();
    $resonse = $init->call($env);
    self::parse_response($resonse);
  }

  public static function set_environment_class($environment_class) {
    $this->environment_class = $environment_class;
  }

  private static function create_chaining() {
    $i = count(self::$middlewares);
    $next = null;
    while($i) {
      self::$middlewares[--$i]->setNext($next);
      $next = self::$middlewares[$i];
    }
    return $next;
  }

  private static function parse_response($resonse) {
    list($status, $headers, $body) = $resonse;



    //headers
    header("HTTP/1.1 $status");
    foreach($headers as $key => $value) {
      header("$key: $value");
    }

    //output middleware echos and such
    ob_get_flush();

    //body
    if (is_array($body)) {
      $body = implode($body);
    }
    echo $body;

  }


}
