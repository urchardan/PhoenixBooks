<!--Description: Shows the selected book post on its own. -->
<?php
  require 'connect.php';
  include 'login_functions.php';
  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  $id_valid = is_numeric($id);

  // If id is valid, sql to grab the book post with the id, else redirected back to main page.
  if ($id_valid)
  {
    $query = "SELECT books.id, books.title, books.author, books.price, books.image, books.description ,categories.category
    FROM books
    LEFT JOIN categories
    ON books.category_id = categories.id
    WHERE books.id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
  }
  else
  {
    header("Location:index.php");
    exit();
  }

$book_post = $statement->fetch();

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?=$book_post['title']?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
  <div class="container">
    <!-- notification message -->
    <?php if (isset($_SESSION['success'])) : ?>
      <div class="alert alert-success alert-dismissible fade show" >
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success!</strong> <?php echo $_SESSION['success'];  ?>
        <?php  unset($_SESSION['success']); ?>
      </div>
    <?php endif ?>
      <div class="container">
        <?php  if (isset($_SESSION['user'])) : ?>
          <strong><?php echo $_SESSION['user']['username']; ?></strong>
          <small>
            <i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
            <br>
            <a href="index.php?logout='1'" style="color: red;">logout</a>
          </small>
        <?php endif ?>
      </div>
        <div class="jumbotron text-center" style="margin-bottom:0">
            <h1><a href="index.php">Phoenix Books - <?= $book_post['title'] ?></a></h1>
        </div> <!-- END div id="header" -->
<nav class="navbar navbar-expand-sm bg-light justify-content-center">          
  <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
      <?php if (isset($_SESSION['user'])) :?>
        <?php if ($_SESSION['user']['user_type'] == 'admin') :?>
          <li class="nav-item"><a class="nav-link" href="create_book.php" >New Book</a></li>
          <li class="nav-item"><a class="nav-link" href="create_category.php" >New Category</a></li>
          <li class="nav-item"><a class="nav-link" href="moderate_users.php" >Create/Delete Users</a></li>
        <?php endif ?>
      <?php endif ?>
  </ul>
</nav> 
    <div class="container p-2 my-2 bg-light text-grey">
      <h2><a><?=$book_post['title']?></a>
        <?php if (isset($_SESSION['user'])) :?>
          <?php if ($_SESSION['user']['user_type'] == 'admin') :?>
            <small>
              <a href="edit.php?id=<?= $book_post['id']?>">edit</a>
            </small>
          <?php endif ?>
        <?php endif ?>
      </h2>
      <?php if (isset($book_post['image']) && !empty($book_post['image'])): ?>
          <div class="form-group">
            <img src="uploads/<?=$book_post['image']?>"/>
          </div>
      <?php endif ?>
      <p>
        <small>Price: $<?=$book_post['price']?></small>
      </p>
      <p>
        <small>Author: <?=$book_post['author']?></small>
      </p>
      <p>
        <small>Category: <?=$book_post['category']?></small>
      </p>
        <?= $book_post['description']?>
    </div>
        <div class="container">
            PhoenixBooks 2020 - No Rights Reserved
        </div>
</body>
</html>