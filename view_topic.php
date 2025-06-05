<?php
require 'header.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT topics.title, users.email FROM topics JOIN users ON topics.user_id = users.id WHERE topics.id = ?');
$stmt->execute([$id]);
$topic = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$topic) {
    echo '<p>Topic not found</p>';
    require 'footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT posts.*, users.email FROM posts JOIN users ON posts.user_id = users.id WHERE posts.topic_id = ? ORDER BY posts.created_at ASC');
$stmt->execute([$id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $content = trim($_POST['content'] ?? '');
    if ($content) {
        $stmt = $pdo->prepare('INSERT INTO posts (topic_id, user_id, content) VALUES (?, ?, ?)');
        $stmt->execute([$id, $_SESSION['user_id'], $content]);
        header('Location: view_topic.php?id=' . $id);
        exit;
    }
}
?>
<h2><?php echo h($topic['title']); ?></h2>
<p>Started by <?php echo h($topic['email']); ?></p>
<hr>
<?php foreach ($posts as $post): ?>
<div>
    <p><?php echo nl2br(h($post['content'])); ?></p>
    <small>By <?php echo h($post['email']); ?> on <?php echo $post['created_at']; ?></small>
</div>
<hr>
<?php endforeach; ?>
<?php if (isset($_SESSION['user_id'])): ?>
<form method="post">
    <textarea name="content" required></textarea><br>
    <button type="submit">Reply</button>
</form>
<?php endif; ?>
<?php require 'footer.php'; ?>
