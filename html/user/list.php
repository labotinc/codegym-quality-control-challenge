<?php
require_once('../db.php');
require_once('../functions.php');
session_start();

if (! isset($_SESSION['user_id'])) {
    header('Location: /login.php');
}

if (isset($_GET['user_id']) && $_GET['user_id'] > 0){
    $user_id = intval($_GET['user_id']);
    $users[0] = getUserById($user_id);
} else {
    $user_id = '';
    $users = getUsers();
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php require_once('../head.php'); ?>

<body>
  <div class="container">
    <h1 class="my-5">ユーザー一覧</h1>
    
    <p><a href="../index.php">&lt;&lt; 掲示板に戻る</a></p>
    
    <div class="card mb-3">
      <div class="card-body">
        <!-- ユーザー一覧 -->
        <form>
            ユーザーID検索：<input name='user_id' value=<?= "{$user_id}" ?>>
            <input class="btn btn-primary" type=submit value='検索'>
        </form>
        <hr>
        <table>
            <tr>
                <th>id</th>
                <th>name</th>
            </tr>
            <?php foreach ($users as $u) { ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= $u['name'] ?></td>
            </tr>
            <?php } ?>
        </table>
      </div>
    </div>
    <br>
  </div>
</body>

</html>
