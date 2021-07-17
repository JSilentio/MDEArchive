<?php
require_once('db.php');
$db = new Db;
$page = !empty($_POST['page']) ? $_POST['page'] : 1;
$sortDirection = !empty($_POST['sortDirection']) ? $_POST['sortDirection'] : 'desc';
$sortField = !empty($_POST['sortField']) ? $_POST['sortField'] : 'date';
if (!empty($_POST['username'])) {
	@$data = $db->searchPosts('author', $_POST['username'], $page);
} else if (!empty($_POST['title'])) {
	@$data = $db->searchPosts('title', $_POST['title'], $page);
} else if (!empty($_POST['reply-username']) || !empty($_POST['reply-text'])) {
	@$data = $db->searchComments( $_POST['reply-username'], $_POST['reply-text'], $page );
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
        table {
            max-width: none;
            word-break: break-word;
        }
        .title {
            display: inline-block;
            float: right;
            height: 80px;
            line-height: 80px;
            font-size: 35;
            font-family: cursive;
        }
        .username {
            white-space: nowrap;
            overflow-x: hidden;
            max-width: 300px;
        }
        .comment {
            max-width: 800px;
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
            var sortDirection = '<?php echo $sortDirection; ?>' == 'desc' ? 'asc' : 'desc';

            var sortForm = $('<form action="" method="post">' +
                '<input type="hidden" id="sortField" name="sortField" value="" />' +
                '<input type="hidden" id="sortDirection" name="sortDirection" value="" />' +
                '<input type="hidden" id="page" name="page" value="" />' +
                '<input type="hidden" id="sort-username" name="username" value="" />' +
                '<input type="hidden" id="sort-title" name="title" value="" />' +
                '<input type="hidden" id="sort-reply-username" name="reply-username" value="" />' +
                '<input type="hidden" id="sort-reply-text" name="reply-text" value="" />' +

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
                $('#sort-username').val($('#username').val());
                $('#sort-title').val($('#title').val());
                $('#sort-reply-username').val($('#reply-username').val());
                $('#sort-reply-text').val($('#reply-text').val());
                sortForm.submit();
            });

        });

    </script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col col-md-5">
			<?php
			if(count($data) > 0 && (!empty($_POST['page'] || $page == "1"))) {
				?>
                <ul class="pagination justify-content-center">
                    <li class="page-item" page="1">
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
					} else if($page >= 282) {
						for ($i=$page-5; $i<=287; $i++) {
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
                    <li class="page-item" page="287">
                        <a class="page-link" aria-label="Last">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
				<?php
			}
			?>
        </div>
        <div class="col col-md-2">
            <div class="title">MDE Never Dies</div>
        </div>
        <div class="col col-md-5">
            <form id="search" class="form-inline text-center" action="" method="POST" style="margin-top: 20px;">
                <input id="username" name="username" class="form-control form-control-md" type="text" placeholder="OP User Name" aria-label="OP User Name" value="<?php echo $_POST['username']; ?>">
                <input id="title" name="title" class="form-control form-control-md" type="text" placeholder="Post Title" aria-label="Post Title"  value="<?php echo $_POST['title']; ?>">
                <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> Search Posts</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col col-md-offset-7 col-md-5">
            <form id="reply-search" class="form-inline text-center" action="" method="POST" style="margin-right: 14px;">
                <input id="reply-username" name="reply-username" class="form-control form-control-md" type="text" placeholder="Commenter User Name" aria-label="Commenter Name" value="<?php echo $_POST['reply-username']; ?>">
                <input id="reply-text" name="reply-text" class="form-control form-control-md" type="text" placeholder="Reply Text" aria-label="Reply Text" value="<?php echo $_POST['reply-text']; ?>">
                <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> Search Replies</button>
            </form>
        </div>
    </div>
	<?php
	if(count($data) < 1) {
		?>
        <div class="row alert alert-info">
            Search returned no results.
        </div>
		<?php
		die;
	}
	?>

    <?php if (empty($_POST['reply-username']) && empty($_POST['reply-text']) && count($data) > 0 ) { ?>
        <div class="row-fluid" style="padding: 30px;">
            <div class="col col-md-12">
                <table class="table table-striped">
                    <tr>
                        <th id="score" class="sortable">Upvotes <i class="fa fa-sort" aria-hidden="true"></i> </th>
                        <th id="title"></th>
                        <th id="replies">Title</th>
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
                            <td class="listing" style="font-size: 8pt;">
                                <?php echo $post['reply_count']; ?> comments
                            </td>
                            <td class="listing" style="max-width:800px; word-wrap:break-all; overflow:hidden;">
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
        </div>
    <?php } else { ?>
        <div class="row-fluid" style="padding: 30px;">
            <div class="col col-md-12">
                <table class="table table-striped">
                    <tr>
                        <th id="username">User</th>
                        <th id="comment">Comment</th>
                        <th id="title">Thread Title</th>
                        <th id="date" class="sortable">Date <i class="fa fa-sort" aria-hidden="true"></i> </th>
                    </tr>
				    <?php
				    foreach($data as $post) {
					    $date = strtotime($post['date']);
					    $displayDate = date('jS F Y', $date);
					    ?>
                        <tr>
                            <td class="username">
                                <b><?php echo $post['username']?></b>
                            </td>
                            <td class="comment" style="font-size: 8pt;">
							    <?php echo $post['comment']; ?>
                            </td>
                            <td class="post_title" style="max-width:800px; word-wrap:break-all; overflow:hidden;">
                                <a href="view.php?file=<?php echo $post['filename']; ?>">
								    <?php echo $post['title']; ?>
                                </a>
                            </td>
                            <td class="listing"><?php echo $displayDate; ?></td>
                        </tr>
					    <?php
				    }
				    ?>
                </table>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col col-md-5">
            <?php
            if(count($data) > 0 && (!empty($_POST['page'] || $page == "1"))) {
                ?>
                <ul class="pagination justify-content-center">
                    <li class="page-item" page="1">
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
                    } else if($page >= 282) {
                        for ($i=$page-5; $i<=287; $i++) {
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
                    <li class="page-item" page="287">
                        <a class="page-link" aria-label="Last">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
                <?php
            }
            ?>
        </div>
    </div>

</div>
</body>
</html>
