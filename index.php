<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>News Home</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Featured News</h1>
  <div class="featured">
    <?php
    $sql = "SELECT * FROM articles WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 3";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<div><a href='article.php?id={$row['id']}'><img src='{$row['thumbnail']}'><h2>{$row['title']}</h2></a></div>";
    }
    ?>
  </div>

  <h2>Categories</h2>
  <div class="categories">
    <?php
    $cat_sql = "SELECT * FROM categories";
    $cat_result = $conn->query($cat_sql);
    while ($cat = $cat_result->fetch_assoc()) {
        echo "<a href='category.php?id={$cat['id']}'>{$cat['name']}</a> ";
    }
    ?>
  </div>
</body>
</html>
