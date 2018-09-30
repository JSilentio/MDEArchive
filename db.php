<?php

class Db {

  private $conn;
  private $server = 'localhost';
  private $user = 'root';
  private $pass = 'rkma89yd!';
  private $db = 'mde_archive';

  function __construct() {
    $this->conn = mysqli_connect($this->server, $this->user, $this->pass, $this->db);
  }

  function listPosts($offset = 100, $limit = 100){
    $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT ". $limit ." OFFSET ". $offset .";";
    $query = mysqli_query($this->conn, $sql);
    if($query) {
      $result = [];
      while ($row = $query->fetch_assoc()) {
        $result[] = $row;
      }
      return $result;
    } else {
      return [];
    }
  }

  function searchPosts($field, $searchString) {
    $sql = "SELECT * FROM posts WHERE ". $field ." LIKE '%". $searchString ."%' LIMIT 100;";
    $query = mysqli_query($this->conn, $sql);
    if($query) {
      $result = [];
      while ($row = $query->fetch_assoc()) {
        $result[] = $row;
      }
      return $result;
    } else {
      return [];
    }
  }

}

?>
