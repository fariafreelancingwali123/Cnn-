<?php
include 'db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    // Delete article thumbnail from disk (optional)
    $article = $conn->query("SELECT thumbnail FROM articles WHERE id = $id")->fetch_assoc();
    if ($article && file_exists($article['thumbnail'])) {
        unlink($article['thumbnail']);
    }

    // Delete article from database
    $conn->query("DELETE FROM articles WHERE id = $id");

    echo "🗑️ Article deleted successfully!";
} else {
    echo "⚠️ No article ID provided.";
}
?>
