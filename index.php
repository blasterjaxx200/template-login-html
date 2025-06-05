<?php
require 'header.php';

$stmt = $pdo->query('SELECT topics.*, users.email FROM topics JOIN users ON topics.user_id = users.id ORDER BY topics.created_at DESC');
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Forum Topics</h1>
<?php if (empty($topics)): ?>
<p>No topics yet.</p>
<?php else: ?>
<ul>
<?php foreach ($topics as $topic): ?>
    <li>
        <a href="view_topic.php?id=<?php echo $topic['id']; ?>"><?php echo h($topic['title']); ?></a>
        by <?php echo h($topic['email']); ?> on <?php echo $topic['created_at']; ?>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php require 'footer.php'; ?>
