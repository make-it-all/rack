<?php

require __DIR__.'/../vendor/autoload.php';

class Application {

  public function __construct($app) {
    $this->app = $app;
  }

  public function call(Rack\Environment $env) {
    return [200, ['Http-Content' => 'html/text'], ['Hello World!']];
  }

}

class NoForms {

  public function __construct($app) {
    $this->app = $app;
  }

  public function call(Rack\Environment $env) {
    if ($env->method == 'POST') {
      return [500, [], ['<h1>NO FORMS ALLOWED</h1>']];
    } else {
      return $this->app->call($env);
    }
  }

}


class Timer {

  public function __construct($app) {
    $this->app = $app;
  }

  public function call(Rack\Environment $env) {
    $start_time = microtime(true);
    $response = $this->app->call($env);
    $time = microtime(true) - $start_time;

    $response[2][] = "THAT TOOK: $time";
    return $response;
  }

}

Rack::add('Timer');
Rack::add('NoForms');
Rack::add('Application');

Rack::run();
