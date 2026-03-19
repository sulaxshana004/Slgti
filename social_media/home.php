<?php
session_start();
include "db.php";

$user_id = $_SESSION['user_id'];
?>
<link rel="stylesheet" href="style.css">

<div class="container">
<form method="POST" action="create_post.php">
<textarea name="content" placeholder="What's on your mind?" required></textarea>
<button name="post">Post</button>
</form>

<?php
$posts = $conn->query("SELECT posts.*, users.name FROM posts
JOIN users ON posts.user_id = users.id
ORDER BY posts.id DESC");

while($row = $posts->fetch_assoc()){
    $is_own = ($row['user_id'] == $user_id);
?>
<div class="post" id="post-<?= $row['id'] ?>">
    <b><?= $row['name'] ?></b>
    <?php if($is_own){ ?>
    <a href="edit_post.php?id=<?= $row['id'] ?>">Edit</a>
    <a href="delete_post.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this post?')">Delete</a>
    <?php } ?>
    <p><?= $row['content'] ?></p>

    <button onclick="likePost(<?= $row['id'] ?>)">Like</button>
    <span id="like-count-<?= $row['id'] ?>">
        <?php
        $likes = $conn->query("SELECT COUNT(*) as total FROM likes WHERE post_id=".$row['id']);
        echo $likes->fetch_assoc()['total'];
        ?>
    </span>

    <div id="comments-<?= $row['id'] ?>"></div>
    <input type="text" id="comment-input-<?= $row['id'] ?>">
    <button onclick="commentPost(<?= $row['id'] ?>)">Comment</button>
</div>
<?php } ?>
</div>

<a href="logout.php">Logout</a>
<script src="script.js"></script>