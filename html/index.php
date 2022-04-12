<?php

session_start();

//ログインしていない場合、login.phpを表示
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once('db.php');
require_once('functions.php');
const PER_PAGE_TWEETS = 10;
const FIRST_PAGE = 1;

$user_id = $_SESSION['user_id'];

/**
 * @param String $tweet_textarea
 * つぶやき投稿を行う。
 */
function newTweet($tweet_textarea) {
    createTweet($tweet_textarea, $_SESSION['user_id']);
}
/**
 * @param String $tweet_textarea
 * @param int $reply_id 返信元の投稿ID
 * 返信を行う。
 */
function newReplyTweet($tweet_textarea, $reply_id) {
    createReplyTweet($tweet_textarea, $_SESSION['user_id'], $reply_id);
}

/**
 * ログアウト処理を行う。
 */
function logout()
{
    $_SESSION = [];
    $msg = 'ログアウトしました。';
}

if ($_POST) { /* POST Requests */
    if (isset($_POST['logout'])) { //ログアウト処理
        logout();
        header("Location: login.php");
    } else if (isset($_POST['tweet_textarea'])) { //投稿処理
        if (isset($_POST['reply_post_id'])) {
            newReplyTweet($_POST['tweet_textarea'], $_POST['reply_post_id']);
            header("Location: index.php");
        } else {
            newTweet($_POST['tweet_textarea']);
            header("Location: index.php");
        }
    }
}

if (isset($_GET)) { //リクエストパラメータ有り
    if (isset($_GET['page'])) { //ページ指定有り
      $current_page = intval($_GET['page']);
      $tweets = getTweetsByPage($current_page, PER_PAGE_TWEETS);
      $tweet_count = getCountAllTweets();
      $page_count = intval(ceil(intval($tweet_count) / PER_PAGE_TWEETS));
      if(! isExistPage($current_page, $page_count)){ //存在しないページの場合は1ページ目を表示する
        $current_page = FIRST_PAGE;
        $tweets = getTweetsByPage($current_page, PER_PAGE_TWEETS);
        $tweet_count = getCountAllTweets();
        $page_count = intval(ceil(intval($tweet_count) / PER_PAGE_TWEETS));
      }
    } else { //ページ指定有り
      $current_page = FIRST_PAGE;
      $tweets = getTweetsByPage($current_page, PER_PAGE_TWEETS);
      $tweet_count = getCountAllTweets();
      $page_count = intval(ceil(intval($tweet_count) / PER_PAGE_TWEETS));
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php require_once('head.php'); ?>

<body>
  <div class="container">
    <h1 class="my-5">新規投稿</h1>
    <div class="card mb-3">
      <div class="card-body">
        <form method="POST">
          <?php if (isset($_GET['reply'])) { ?>
          <textarea class="form-control" type=textarea name="tweet_textarea" ?><?= getUserReplyText($_GET['reply']) ?></textarea>
          <?php } else { ?>
          <textarea class="form-control" type=textarea name="tweet_textarea" ?></textarea>
          <?php } ?>
          <br>
          <?php if (isset($_GET['reply'])) { ?>
          <input type="hidden" name="reply_post_id" value=<?= "{$_GET['reply']}" ?> />
          <?php } ?>
          <input class="btn btn-primary" type=submit value="投稿">
        </form>
      </div>
    </div>
    <h1 class="my-5">コメント一覧</h1>
    <?php foreach ($tweets as $t) { ?>
      <div class="card mb-3">
        <div class="card-body">
          <?php $profile_url = "user/index.php?user_id=" . $t['user_id']; ?>
          <p class="card-title"><b><?= "{$t['id']}" ?></b> <a href=<?= "{$profile_url}" ?>><?= h($t['name']) ?></a> <small><?= "{$t['updated_at']}" ?></small></p>
          <p class="card-text"><?= h($t['text']) ?></p>
          <p>
            <a href="index.php?reply=<?= "{$t['id']}" ?>">[返信する]</a>
            <?php if (isset($t['reply_id'])) {?>
            <a href="view.php?id=<?= "{$t['id']}" ?>">[返信元のメッセージ]</a>
            <?php } ?>
          </p>
        </div>
      </div>
    <?php } ?>
    
    <?php //ページング処理ここから ?>
    <p>
    <?php
      if ($page_count > 0) {
        echo "[ ";
        foreach(range(1, $page_count) as $i){
          if ($i === $current_page) {
            echo "{$i} ";
          } else {
            echo "<a href=index.php?page={$i}>{$i} </a>";
          }
        }
        echo "]";
      }
    ?>
    </p>
    <?php //ページング処理ここまで ?>
    
    <div>
      <form method="GET" action='user/edit.php' style="margin-bottom:1em">
        <button class="btn btn-primary">ユーザー情報変更</button>
      </form>
  
      <form method="GET" action='user/list.php' style="margin-bottom:1em">
        <button class="btn btn-primary">ユーザー一覧</button>
      </form>
  
      <form method="POST">
        <input type="hidden" name="logout" value="dummy">
        <button class="btn btn-primary">ログアウト</button>
      </form>
    </div>
  </div>
</body>
</html>