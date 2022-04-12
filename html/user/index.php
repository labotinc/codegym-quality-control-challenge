<?php
require_once('../db.php');
require_once('../functions.php');
session_start();

if (! isset($_SESSION['user_id'])) {
    header('Localtion: /login.php');
}

$user = getUserById($_GET['user_id']);
?>

<!DOCTYPE html>
<html lang="ja">

<?php require_once('../head.php'); ?>

<body>
  <div class="container">
    <h1 class="my-5">プロフィール表示</h1>
    
    <p><a href="../index.php">&lt;&lt; 掲示板に戻る</a></p>
    
    <div class="card mb-3">
      <div class="card-body">
        <!-- プロフィール -->
        <p>名前：<?= "{$user['name']}" ?></p>
        <p>プロフィール：<?= "{$user['profile']}" ?></p>
        <p>作成日：<?= "{$user['created_at']}" ?></p>
        <p>更新日：<?= "{$user['updated_at']}" ?></p>
      </div>
    </div>
    <br>
  </div>
</body>

</html>
