<?php namespace Rack;


/*
  Environment - standardize requests, whilst adding convient functionallity.
  The environment is a wrapper for all request, environment and server
  variables. It also serves as a key value store for arbitrary data for middleware
  to communicate

  @contributers Henry Morgan
*/
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

  /*
    Extract server variables adding default values where applicable.
  */
  private function inflate_server() {
    $this->_server = $_SERVER;

    $this->protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0';
    $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $this->request_time = $_SERVER['REQUEST_TIME_FLOAT'] ?? null;
    $this->server_ip = $_SERVER['SERVER_ADDR'] ?? null;
    $this->server_name = $_SERVER['SERVER_NAME'] ?? null;
    $this->accepts = $_SERVER['HTTP_ACCEPT'] ?? '*/*';
    $this->https = isset($_SERVER['HTTPS']);
    $this->remote_ip = $_SERVER['REMOTE_ADDR'] ?? null;

    $this->base_url = $_SERVER['HTTP_HOST'] ?? null;
    $this->port = $_SERVER['SERVER_PORT'] ?? null;
    $this->path = explode('?', $_SERVER['REQUEST_URI']??'')[0] ?? null;
    $this->query_string = $_SERVER['QUERY_STRING'] ?? null;
  }

  /*
    whether the request was https.
  */
  public function is_https() {
    return $this->https ?? false;
  }

  /*
    store a key value pair.
  */
  public function set($key, $value) {
    return $this->vars[$key] = $value;
  }

  /*
    retrieve a value given a key.
  */
  public function get($key) {
    return $this->vars[$key];
  }

}
