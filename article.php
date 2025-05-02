<?php include 'db.php'; ?>
<?php
$id = $_GET['id'];
$sql = "SELECT * FROM articles WHERE id = $id";
$row = $conn->query($sql)->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $row['title']; ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?php echo $row['title']; ?></h1>
  <img src="<?php echo $row['thumbnail']; ?>" alt="">
  <p><?php echo $row['content']; ?></p>

  <h3>Related News</h3>
  <?php
  $cat_id = $row['category_id'];
  $related = "SELECT * FROM articles WHERE category_id = $cat_id AND id != $id LIMIT 3";
  $related_result = $conn->query($related);
  while ($rel = $related_result->fetch_assoc()) {
      echo "<a href='article.php?id={$rel['id']}'><p>{$rel['title']}</p></a>";
  }
  ?>
</body>
</html>
