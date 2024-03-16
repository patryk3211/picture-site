<?php

class Response {
  public int $code = 200;
  public array $headers = [];
  public ?string $body = null;

  public function code(int $code): Response {
    $this->code = $code;
    return $this;
  }

  public function header(string $key, string $value): Response {
    $this->headers[$key] = $value;
    return $this;
  }

  public function body(string $body): Response {
    $this->body = $body;
    return $this;
  }

  public function content_type(string $type): Response {
    $this->headers['Content-Type'] = $type;
    return $this;
  }

  public function send(): void {
    http_response_code($this->code);
    foreach($this->headers as $key => $value)
      header("$key: $value");
    if($this->body != null)
      echo $this->body;
  }
}

function response_code(int $code): Response {
  $response = new Response();
  $response->code = $code;
  return $response;
}

function response_text(string $body): Response {
  $response = new Response();
  $response->body = $body;
  return $response;
}

function response_json(mixed $object): Response {
  $response = new Response();
  $response->body = json_encode($object);
  $response->content_type('application/json');
  return $response;
}

