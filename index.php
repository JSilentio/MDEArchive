<?php
  require_once('db.php');
  $db = new Db;
  $page = !empty($_POST['page']) ? $_POST['page'] : 1;
  $sortDirection = !empty($_POST['sortDirection']) ? $_POST['sortDirection'] : 'desc';
  $sortField = !empty($_POST['sortField']) ? $_POST['sortField'] : 'date';
  if (!empty($_POST['username'])) {
    @$data = $db->searchPosts('author', $_POST['username']);
  } else if (!empty($_POST['title'])) {
    @$data = $db->searchPosts('title', $_POST['title']);
  } else if (!empty($_POST['sortField'])) {
    @$data = $db->sortPosts($sortField, $sortDirection, $page);
  } else {
    @$data = $db->listPosts($page);
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
    overflow-x: hidden;
    white-space: nowrap;
  }
  .sortable{
    cursor: pointer;
    min-width: 95px;
  }
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<script>

$( document ).ready(function() {

  var page = '<?php echo $page; ?>';
  var sortField = '<?php echo $sortField; ?>';
  var sortDirection = '<?php echo $sortDirection; ?>';

  // if(sortDirection == 'asc') {
  //   sortDirection = 'desc';
  // } else {
  //   sortDirection = 'asc';
  // }

  var sortForm = $('<form action="" method="post">' +
    '<input type="hidden" id="sortField" name="sortField" value="" />' +
    '<input type="hidden" id="sortDirection" name="sortDirection" value="" />' +
    '<input type="hidden" id="page" name="page" value="" />' +
    '</form>');
  $('body').append(sortForm);

  $('#score').on('click', function(){
    $('#sortField').val('score');
    $('#sortDirection').val(sortDirection);
    sortForm.submit();
  });

  $('#date').on('click', function(){
    $('#sortField').val('date');
    $('#sortDirection').val(sortDirection);
    sortForm.submit();
  });

  $('.page-item').on('click', function(){
    $('#sortField').val('<?php echo $sortField; ?>');
    $('#sortDirection').val('<?php echo $sortDirection; ?>');
    $('#page').val($(this).attr('page'));
    sortForm.submit();
  });

});

</script>
</head>
<body>

  <form id="search" class="form-inline pull-right" action="" method="POST" style="margin-right: 25px;">
      <input name="username" class="form-control form-control-md" type="text" placeholder="OP User Name" aria-label="OP User Name">
      <input name="title" class="form-control form-control-md" type="text" placeholder="Post Title" aria-label="Post Title">
      <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
  </form>

  <?php
    if(count($data) == 100) {
  ?>
    <ul class="pagination justify-content-center">
      <li class="page-item">
      <a class="page-link" aria-label="First">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <?php
        if($page <= 5) {
          for ($i=1; $i<=10+$page; $i++) {
            if ($i == $page) {
              echo "<li class='page-item active' page=".$i."><a>".$i."</a></li>";
            } else {
              echo "<li class='page-item' page=".$i."><a>".$i."</a></li>";
            }
          }
        } else {
          for ($i=$page-5; $i<=5+$page; $i++) {
            if ($i == $page) {
              echo "<li class='page-item active' page=".$i."><a>".$i."</a></li>";
            } else {
              echo "<li class='page-item' page=".$i."><a>".$i."</a></li>";
            }
          }
        }
      ?>
      <li class="page-item">
        <a class="page-link" aria-label="Last">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </a>
      </li>
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
    <div class="col col-md-12" style="width: 100%;">
      <table class="table table-striped" style="width:99%;">
        <tr>
          <th id="score" class="sortable">Upvotes <i class="fa fa-sort" aria-hidden="true"></i> </th>
          <th id="title">Title</th>
          <th id="author">OP</th>
          <th id="date" class="sortable">Date <i class="fa fa-sort" aria-hidden="true"></i> </th>
        </tr>
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
            <td class="listing">
              <a href="view.php?file=<?php echo $post['filename']; ?>">
                <?php echo $post['title']; ?>
              </a>
            </td>
            <td class="listing">
              <a href="https://www.reddit.com/user/<?php echo $post['author']; ?>">
                <?php echo $post['author']; ?>
              </a>
            </td>
            <td class="listing"><?php echo $displayDate; ?></td>
          </tr>
      <?php
        }
      ?>
      </table>
    </div>
  </row>
</body>
</html>
