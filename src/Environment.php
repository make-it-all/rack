<?php namespace Rack;

// TODO inflate everything....


class Environment {

  public $get_vars;
  public $post_vars;
  public $request_vars;
  private $vars;

  function __construct() {
    $this->inflate_server();
    $this->get_vars = $_GET;
    $this->post_vars = $_POST;
    $this->request_vars = $_REQUEST;
  }

  private function inflate_server() {
    $this->_server = $_SERVER;

    $this->protocol = $_SERVER['SERVER_PROTOCOL'];
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->request_time = $_SERVER['REQUEST_TIME_FLOAT'];
    $this->server_ip = $_SERVER['SERVER_ADDR'];
    $this->accepts = $_SERVER['HTTP_ACCEPT'];
    $this->server_name = $_SERVER['SERVER_NAME'];
    $this->https = $_SERVER['HTTPS'];
    $this->remote_ip = $_SERVER['REMOTE_ADDR'];

    $this->base_url = $_SERVER['HTTP_HOST'];
    $this->port = $_SERVER['SERVER_PORT'];
    $this->path = $_SERVER['REQUEST_URI'];
    $this->query_string = $_SERVER['QUERY_STRING'];
  }

  public function is_https() {
    return $this->https ?? false;
  }

  public function set($key, $value) {
    return $this->vars[$key] = $value;
  }

  public function get($key) {
    return $this->vars[$key];
  }

}
