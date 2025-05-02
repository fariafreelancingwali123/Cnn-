<?php include 'db.php'; ?>
<?php
$category_id = $_GET['id'];
$cat_sql = "SELECT name FROM categories WHERE id = $category_id";
$cat_name = $conn->query($cat_sql)->fetch_assoc()['name'];
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $cat_name; ?> News</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?php echo $cat_name; ?></h1>
  <?php
  $sql = "SELECT * FROM articles WHERE category_id = $category_id ORDER BY created_at DESC";
  $result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
      echo "<div><a href='article.php?id={$row['id']}'><h3>{$row['title']}</h3></a><p>" . substr($row['content'], 0, 100) . "...</p></div>";
  }
  ?>
</body>
</html>
