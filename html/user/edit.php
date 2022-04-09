<?php
require_once('../db.php');
require_once('../functions.php');
session_start();

if ($_GET) {
    if (isset($_GET['user_id'])) {
        $user = getUserById($_GET['user_id']);
    }
    
    $user_id = $user['id'];
    $user_name = $user['name'];
    $user_profile = $user['profile'];
}

if ($_POST){
    $user_id = $_POST['user_id'];
    $user_name = $_POST['name'];
    $user_profile = $_POST['profile'];
    
    updateUserById($user_id, $_POST['name'], $_POST['profile']);
    header("Location: index.php?user_id=" . $_POST['user_id']);
    exit;
}

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
        <form action="edit.php?user_id=<?= "{$user_id}" ?>" method="POST">
          <!-- プロフィール -->
          <input type="hidden" name='user_id' value=<?= "{$user_id}" ?>>
          <p>名前：<input type='text' name='name' value=<?= "{$user_name}" ?>></p>
          <p>プロフィール：<input name='profile' type='text' value=<?= "{$user_profile}" ?>></p>
          <p>作成日：</p>
          <p>更新日：</p>
          <input class="btn btn-primary" type=submit value="更新">
        </form>
      </div>
    </div>
    <br>
  </div>
</body>

</html>
