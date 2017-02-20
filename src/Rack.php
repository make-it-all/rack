<?php

/*
Rack - wraps http requests to standardize requests and response.

Rack allows requests to be routed through a series of pre-defined `middleware`.
middleware contains two methods, a `constructor` which is passed configuration data
and the next middleware of which to call, and a `call` method which gets passed
an environment variable describing the request and state of the server. The call
method may acy upon the request however it pleases and should call the next middleware
it was provided. If the middleware sees fit then it may forgo this call and create
its own response.
  eg.
    if the middleware is reponsible for caching requests it may
    choose to return a cache of the last response rather then calling the rest
    of the stack.

The environment passed in with the call method is an instance of Rack\Environment;

The response output by the middleware stack should be an array with 3 elements,
in the form.
    [  status_code  |  headers  |  body  ]
    status_code - the http status to respond with eg '200 OK', '404 Not Found',
                  '500 Internal Error'
    headers - A key value array of the headers to return with the response.
    body - The content of the response to be printed.

@contributers Henry Morgan

*/

class Rack {

  private static $middlewares = [];
  private static $env;


  /*
    Add a middleware to the stack. Middleware are called in the order they are added.
  */
  public static function add($middleware, ...$args) {
    self::$middlewares[] = [$middleware, $args];
  }

  /*
    Run the request through the middleware stack then format and return the request.
  */
  public static function run() {
    $init = self::create_chaining();
    self::$env = $env = new Rack\Environment();
    ob_start();
    $resonse = $init->call($env);
    self::parse_response($resonse);
  }


  /*
    Instantiate each middleware providing the next middleware to call and any
    configuration it desires.
  */
  private static function create_chaining() {
    $i = count(self::$middlewares);
    $next = null;
    while($i) {
      list($middleware, $args) = self::$middlewares[--$i];
      $next = new $middleware($next, ...$args);
    }
    return $next;
  }

  /*
    Format the response from the middleware stack by parsing the headers and
    printing the body.
  */
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

    //print body
    if (is_array($body)) {
      $body = implode($body);
    }
    echo $body;
  }


}
