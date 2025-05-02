<?php
include 'db.php';

$id = $_GET['id'] ?? 0;

// Fetch existing article data
$article = $conn->query("SELECT * FROM articles WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Thumbnail (optional)
    $thumbnail = $article['thumbnail'];
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $targetDir = "uploads/";
        $filename = time() . "_" . basename($_FILES["thumbnail"]["name"]);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFile)) {
            $thumbnail = $targetFile;
        }
    }

    // Update query
    $stmt = $conn->prepare("UPDATE articles SET title=?, content=?, thumbnail=?, category_id=?, is_featured=? WHERE id=?");
    $stmt->bind_param("sssiii", $title, $content, $thumbnail, $category_id, $is_featured, $id);
    $stmt->execute();

    echo "✅ Article updated successfully!";
    $article = $conn->query("SELECT * FROM articles WHERE id = $id")->fetch_assoc(); // refresh data
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Article</title>
</head>
<body>
    <h1>Edit Article</h1>
    <form action="edit_article.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo $article['title']; ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="10" cols="50" required><?php echo $article['content']; ?></textarea><br><br>

        <label>Category:</label><br>
        <select name="category_id" required>
            <?php
            $result = $conn->query("SELECT * FROM categories");
            while ($cat = $result->fetch_assoc()) {
                $selected = $cat['id'] == $article['category_id'] ? "selected" : "";
                echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
            }
            ?>
        </select><br><br>

        <label>Thumbnail (leave blank to keep current):</label><br>
        <input type="file" name="thumbnail"><br>
        <?php if ($article['thumbnail']): ?>
            <img src="<?php echo $article['thumbnail']; ?>" width="150"><br>
        <?php endif; ?><br>

        <label><input type="checkbox" name="is_featured" <?php echo $article['is_featured'] ? "checked" : ""; ?>> Mark as Featured</label><br><br>

        <input type="submit" value="Update Article">
    </form>
</body>
</html>
