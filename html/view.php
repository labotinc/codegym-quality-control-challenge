<?php
require_once('db.php');
require_once('functions.php');

session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
}

/* 返信課題はここからのコードを修正しましょう。 */
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
}
$tweet = getTweet($id)[0];
/* 返信課題はここまでのコードを修正しましょう。 */
?>

<!DOCTYPE html>
<html lang="ja">

<?php require_once('head.php'); ?>

<body>
  <div class="container">
    <h1 class="my-5">投稿表示</h1>
    
    <p><a href="index.php">&lt;&lt; 掲示板に戻る</a></p>
    
    <div class="card mb-3">
      <div class="card-body">
        <!-- 返信課題はここからのコードを修正しましょう。 -->
          <p class="card-title"><b><?= $tweet['id'] ?></b> <small><?= h($tweet['name']) ?> <?= $tweet['created_at'] ?></small></p>
          <p class="card-text"><?= h($tweet['text']) ?></p>
        <!-- 返信課題はここまでのコードを修正しましょう。 -->
      </div>
    </div>
    <br>
  </div>
</body>

</html>
