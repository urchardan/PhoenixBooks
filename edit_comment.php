<?php
require 'connect.php';
include 'login_functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$id_valid = is_numeric($id);

// If id is valid, sql to grab the book with the id, and categories, else redirected back to main page.
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['user_type'] == 'admin') {
        if ($id_valid) {
            $query = "SELECT *
                  FROM comments
                  WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $comment = $statement->fetch();
        }
    } else {
        header("Location:index.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Phoenix Books</title>
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
	integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
	crossorigin="anonymous">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script
	src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
	integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
	crossorigin="anonymous"></script>


<body>
	<div class="container">
		<!-- notification message -->
  <?php if (isset($_SESSION['success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success!</strong> <?php echo $_SESSION['success'];  ?>
      <?php  unset($_SESSION['success']); ?>
    </div>
  <?php endif ?>
  <div class="container">
			<div>
      <?php  if (isset($_SESSION['user'])) : ?>
        <strong><?php echo $_SESSION['user']['username']; ?></strong> <small>
					<i style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
					<br> <a href="index.php?logout='1'" style="color: red;">logout</a>
				</small>
      <?php endif ?>
    </div>
			<div class="jumbotron text-center" style="margin-bottom: 0">
				<h1>
					<a href="index.php">Phoenix Books - Edit Comment</a>
				</h1>
			</div>
			<nav class="navbar navbar-expand-sm bg-light justify-content-center">
				<ul class="navbar-nav">
					<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
					<li class="nav-item"><a class="nav-link" href="create_book.php">New
							Book</a></li>
					<li class="nav-item"><a class="nav-link" href="create_category.php">New
							Category</a></li>
					<li class="nav-item"><a class="nav-link" href="moderate_users.php">Create/Delete
							Users</a></li>
				</ul>
			</nav>
			<div
				class="d-flex justify-content-center align-items-center container p-2 my-2 bg-light text-grey">
				<form action="process_post.php" method="post">
					<fieldset>
						<legend>Edit Comment</legend>
						<p>
							<label for="comment">Comment</label>
							<textarea name="comment" id="comment"><?=$comment['comment']?></textarea>
						</p>
						<p>
							<input type="hidden" name="id" value="<?= $comment['id']?>" /> <input
								type="submit" name="command" value="Update Comment" /> <input
								type="submit" name="command" value="Delete Comment"
								onclick="return confirm('Are you sure you wish to delete this comment?')" />
						</p>
					</fieldset>
				</form>
			</div>
		</div>
		<div class="container">PhoenixBooks 2020 - No Rights Reserved</div>
		<!-- END div id="footer" -->

</body>
</html>
