<?php

class Database {
  private mysqli $connection;

  function __construct() {
    $this->connection = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);
  }

  function prepare(string $query): mysqli_stmt {
    return $this->connection->prepare($query);
  }

  function execute(string $query): bool {
    return $this->connection->execute_query($query);
  }
}

function database(): Database {
  if(!isset($GLOBALS['__database'])) {
    $GLOBALS['__database'] = new Database();
  }
  return $GLOBALS['__database'];
}

