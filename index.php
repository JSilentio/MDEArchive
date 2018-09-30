<?php
  require_once('db.php');
  $db = new Db;
  if (!empty($_POST['username'])) {
    @$data = $db->searchPosts('author', $_POST['username']);
  } else if (!empty($_POST['title'])) {
    @$data = $db->searchPosts('title', $_POST['title']);
  } else {
    @$data = $db->listPosts();
  }
?>

<html lang="en">
<head>
<meta charset="utf-8">
<title>/r/MDE NEVER DIES</title>
<style>
  .updoots{
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-bottom: 5px solid orange;
  }
  .downdoots{
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid blue;
    margin-top: 8px;
  }
  .score{
    font-size: -2;
    margin-left: 15px;
    min-width: 70px;
  }
  .right{
    float: right;
  }
  .listing{
    max-width: 70%;
    overflow: hidden;
    white-space: nowrap;
  }
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<script>
</script>
</head>
<body>

  <form id="search" class="form-inline pull-right" action="" method="POST">
      <input name="username" class="form-control form-control-md" type="text" placeholder="OP User Name" aria-label="OP User Name">
      <input name="title" class="form-control form-control-md" type="text" placeholder="Post Title" aria-label="Post Title">
      <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
  </form>

  <?php
    if(count($data) == 100) {
  ?>
    <ul class="pager">
      <li><a href="">Previous</a></li>
      <li><a href="#">Next</a></li>
    </ul>
  <?php
    }
  ?>


  <?php
    if(count($data) < 1) {
      $data = $db->listPosts();
  ?>
    <div class="alert alert-info">
      Search returned no results.
    </div>
  <?php
    }
  ?>

  <div class="row" style="padding: 30px;">
    <div class="col col-md-12">
      <table class="table table-striped">
      <?php
        foreach($data as $post) {
          $date = strtotime($post['date']);
          $displayDate = date('jS F Y', $date);
      ?>
          <tr>
            <td class="score">
              <div class="updoots"></div>
              <span class="badge badge-secondary right"><?php echo $post['score']?></span>
              <div class="downdoots"></div>
            </td>
            <td class='listing'>
              <a href="view.php?file=<?php echo $post['filename']; ?>">
                <?php echo $post['title']; ?>
              </a>
            </td>
            <td class='listing'>
              <a href="https://www.reddit.com/user/<?php echo $post['author']; ?>">
                <?php echo $post['author']; ?>
              </a>
            </td>
            <td class='listing'><?php echo $displayDate; ?></td>
          </tr>
      <?php
        }
      ?>
      </table>
    </div>
  </row>
</body>
</html>
