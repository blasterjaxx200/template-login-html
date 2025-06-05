<?php
require 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title && $content) {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO topics (title, user_id) VALUES (?, ?)');
        $stmt->execute([$title, $_SESSION['user_id']]);
        $topicId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('INSERT INTO posts (topic_id, user_id, content) VALUES (?, ?, ?)');
        $stmt->execute([$topicId, $_SESSION['user_id'], $content]);
        $pdo->commit();
        header('Location: view_topic.php?id=' . $topicId);
        exit;
    } else {
        $error = 'Title and content required';
    }
}
?>
<h2>New Topic</h2>
<?php if (!empty($error)) echo '<p>'.h($error).'</p>'; ?>
<form method="post">
    <input type="text" name="title" placeholder="Topic title" required><br>
    <textarea name="content" placeholder="Content" required></textarea><br>
    <button type="submit">Create</button>
</form>
<?php require 'footer.php'; ?>
