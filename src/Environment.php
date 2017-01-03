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

    $this->protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0';
    $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $this->request_time = $_SERVER['REQUEST_TIME_FLOAT'] ?? null;
    $this->server_ip = $_SERVER['SERVER_ADDR'] ?? null;
    $this->accepts = $_SERVER['HTTP_ACCEPT'] ? '*/*';
    $this->server_name = $_SERVER['SERVER_NAME'] ? null;
    $this->https = isset($_SERVER['HTTPS']);
    $this->remote_ip = $_SERVER['REMOTE_ADDR'] ? null;

    $this->base_url = $_SERVER['HTTP_HOST'] ?? null;
    $this->port = $_SERVER['SERVER_PORT'] ?? null;
    $this->path = $_SERVER['REQUEST_URI'] ?? null;
    $this->query_string = $_SERVER['QUERY_STRING'] ? null;
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
