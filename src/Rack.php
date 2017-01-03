<?php

class Rack {

  static $middlewares = [];
  private static $environment_class = 'Rack\Environment';

  public static function add($middleware) {
    self::$middlewares[] = $middleware;
  }

  public static function run() {
    $init = self::create_chaining();
    $env = new self::$environment_class();
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
      $next = new self::$middlewares[--$i]($next);
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

    //body
    // ob_end_clean();
    if (is_array($body)) {
      $body = implode($body);
    }
    echo $body;

  }


}
