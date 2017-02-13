<?php

class Rack {

  private static $middlewares = [];
  private static $env;

  public static function add($middleware, ...$args) {
    self::$middlewares[] = [$middleware, $args];
  }

  public static function run() {
    $init = self::create_chaining();
    self::$env = $env = new Rack\Environment();
    ob_start();
    $resonse = $init->call($env);
    self::parse_response($resonse);
  }

  private static function create_chaining() {
    $i = count(self::$middlewares);
    $next = null;
    while($i) {
      list($middleware, $args) = self::$middlewares[--$i];
      $next = new $middleware($next, ...$args);
    }
    return $next;
  }

  private static function parse_response($response) {
    list($status, $headers, $body) = $response;

    //headers
    $protocol = self::$env->protocol;
    header("$protocol $status");
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
