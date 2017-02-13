<?php

require __DIR__.'/../vendor/autoload.php';

class Application {

  public function __construct($app) {
    $this->app = $app;
  }

  public function call(Rack\Environment $env) {
    echo 'STARTING APP'.PHP_EOL;
    $response = [200, ['Http-Content' => 'html/text'], [$env->protocol]];
    echo 'ENDING APP'.PHP_EOL;
    return $response;
  }

}
//
class NoForms {

  public function __construct($app) {
    $this->app = $app;
  }

  public function call(Rack\Environment $env) {
    echo 'no_forms'.PHP_EOL;
    $response = $this->app->call($env);
    echo '/no_forms'.PHP_EOL;
    return $response;
  }

}

class Timer {

  public function __construct($app, $count, $bool, $text) {
    $this->app = $app;
    $this->count = $count;
  }

  public function call(Rack\Environment $env) {
    echo "timer_$this->count".PHP_EOL;
    $response = $this->app->call($env);
    echo '/timer'.PHP_EOL;
    return $response;
  }

}

Rack::add('NoForms');
Rack::add('Timer', 10, false, "HI");
Rack::add('Application');

Rack::run();
