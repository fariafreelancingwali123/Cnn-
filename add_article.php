<?php
include 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Handle file upload
    $thumbnail = '';
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename = time() . "_" . basename($_FILES["thumbnail"]["name"]);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFile)) {
            $thumbnail = $targetFile;
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO articles (title, content, thumbnail, category_id, is_featured) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $title, $content, $thumbnail, $category_id, $is_featured);
    $stmt->execute();

    echo "✅ Article added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Article</title>
</head>
<body>
    <h1>Add New Article</h1>
    <form action="add_article.php" method="post" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="10" cols="50" required></textarea><br><br>

        <label>Category:</label><br>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php
            $result = $conn->query("SELECT * FROM categories");
            while ($cat = $result->fetch_assoc()) {
                echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
            }
            ?>
        </select><br><br>

        <label>Thumbnail:</label><br>
        <input type="file" name="thumbnail"><br><br>

        <label><input type="checkbox" name="is_featured"> Mark as Featured</label><br><br>

        <input type="submit" value="Add Article">
    </form>
</body>
</html>
