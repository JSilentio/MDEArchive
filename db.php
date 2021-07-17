<?php

class Db {

  private $conn;
  private $server = 'localhost';
  private $user = 'mde';
  private $pass = 'YUdoDis???';
  private $db = 'mde_archive';

  function __construct() {
    $this->conn = mysqli_connect($this->server, $this->user, $this->pass, $this->db);
  }

  function listPosts($page = 1){
    $page = ($page -1) * 100;
    $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT 100 OFFSET ". $page .";";
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

  function searchPosts($field, $searchString, $page = 1) {
    $page = ($page -1) * 100;
    $sql = "SELECT * FROM posts WHERE ". $field ." LIKE '%". $searchString ."%' LIMIT 100 OFFSET ".$page.";";
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

  function sortPosts($field = 'date', $direction = 'desc', $page = 1) {
    $page = ($page -1) * 100;
    $sql = "SELECT * FROM posts order by ".$field." ".$direction." LIMIT 100 OFFSET ".$page.";";
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

  function searchComments($username, $comment, $page = 1) {
    $page = ($page -1) * 100;

    $usernameSearch = "";
    $commentSearch = "";

    if(!empty(trim($username))) {
      $usernameSearch = " AND username LIKE '%". $username ."%' ";
    }

    if(!empty(trim($comment))) {
      $commentSearch = " AND comment LIKE '%". $comment ."%' ";
    }


    $sql = "SELECT pr.*, p.title, p.date, p.filename FROM post_replies pr JOIN posts p on pr.post_id = p.id WHERE 1=1 ".$usernameSearch.$commentSearch." LIMIT 100 OFFSET ".$page.";";
    echo $sql;
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

