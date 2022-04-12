<?php
/**
 * @param string $name ユーザー名
 * @return PDOStatement ユーザー情報の連想配列を格納したPDOStatement
 * 名前を元にユーザー情報を取得します。
 */
function getUserByName($name)
{
    $sql = 'select * from users where name = :name';
    $stmt = getPdo()->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param string $name ユーザー名
 * @param string $$password_hash ユーザーパスワードハッシュ値
 * @return bool 成功・失敗
 */
function createUser($name, $password_hash)
{
    $sql = 'insert into users (name, password_hash, created_at, updated_at)';
    $sql .= ' values (:name, :password_hash, :created_at, :updated_at)';
    $now = date("Y-m-d H:i:s");
    $stmt = getPdo()->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
    $stmt->bindValue(':created_at', $now, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $now, PDO::PARAM_STR);
    return $stmt->execute();
}

/**
 * @param string $text 投稿内容
 * @param string $user_id ユーザーID
 * @return bool 成功・失敗
 */
function createTweet($text, $user_id)
{
    $sql = 'insert into tweets (text, user_id, created_at, updated_at)';
    $sql .= ' values (:text, :user_id, :created_at, :updated_at)';
    $now = date("Y-m-d H:i:s");
    $stmt = getPdo()->prepare($sql);
    $stmt->bindValue(':text', $text, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':created_at', $now, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $now, PDO::PARAM_STR);
    return $stmt->execute();
}

/**
 * @param string $text 投稿内容
 * @param string $user_id ユーザーID
 * @return bool 成功・失敗
 */
function createReplyTweet($text, $user_id, $reply_id)
{
    $sql = 'insert into tweets (text, user_id, created_at, updated_at, reply_id)';
    $sql .= ' values (:text, :user_id, :created_at, :updated_at, :reply_id)';
    $now = date("Y-m-d H:i:s");
    $stmt = getPdo()->prepare($sql);
    $stmt->bindValue(':text', $text, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':created_at', $now, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $now, PDO::PARAM_STR);
    $stmt->bindValue(':reply_id', $reply_id, PDO::PARAM_INT);
    return $stmt->execute();
}

/**
 * @return PDOStatement ユーザー情報の連想配列を格納したPDOStatement
 * 投稿の一覧を取得します。
 */
function getTweets()
{
    $sql = 'select t.id, t.text, t.user_id, t.created_at, t.updated_at, u.name, t.reply_id ';
    $sql .= ' from tweets t join users u on t.user_id = u.id';
    $sql .= ' order by t.updated_at desc';
    $stmt = getPdo()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @return PDOStatement ユーザー情報の連想配列を格納したPDOStatement
 * 投稿の一覧を取得します。
 */
function getTweet($id)
{
    $sql = '';
    $sql .= ' select t.id, t.text, t.user_id, t.created_at, t.updated_at, u.name, t.reply_id ';
    $sql .= ' from tweets t join users u on t.user_id = u.id ';
    $sql .= ' where t.id = :id ';
    $sql .= ' order by t.updated_at desc ';
    $stmt = getPdo()->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @return PDOStatement ユーザー情報の連想配列を格納したPDOStatement
 * ページ番号を指定した投稿の一覧を取得します。
 */
function getTweetsByPage($page_number, $per_page_tweets)
{
    $page_number = $page_number - 1;
    if ($page_number < 0) {
        $page_number = 0;
    }
    
    $offset = $page_number * $per_page_tweets;
    $offset = $offset - 1; // 1開始を0開始にする
    if ($offset < 0) $offset = 0;
    
    $sql = '';
    $sql .= 'select t.id, t.text, t.user_id, t.created_at, t.updated_at, u.name, t.reply_id ';
    $sql .= 'from tweets t join users u on t.user_id = u.id ';
    $sql .= 'order by t.updated_at desc ';
    $sql .= 'limit :per_page_tweets ';
    $sql .= 'offset :offset ';
    $stmt = getPdo()->prepare($sql);
    $stmt->bindValue(':per_page_tweets', $per_page_tweets, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @return PDOStatement ユーザー情報の連想配列を格納したPDOStatement
 * 投稿の一覧を取得します。
 */
function getCountAllTweets()
{
    $sql = 'select count(*) as cnt';
    $sql .= ' from tweets ';
    $stmt = getPdo()->prepare($sql);
    $stmt->execute();
    $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $stmt[0]['cnt'];
}

/**
 * @param string $id ユーザーID
 * @return PDOStatement ユーザー情報の連想配列を格納したPDOStatement
 * 名前を元にユーザー情報を取得します。
 */
function getUserById($id)
{
    $sql = 'select id, name, profile, created_at, updated_at from users where id = :id';
    $stmt = getPdo()->prepare($sql);
    $stmt->bindValue(':id', intval($id), PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt === false) {
        return false;
    }
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($users) === 0){
        return false;
    }
    return $users[0];
}

/**
 * @param int $id ユーザーID
 * @param string $name ユーザー名
 * @param string $profile プロフィール
 * ユーザー情報の更新を行います。
 */
function updateUserById($id, $name, $profile) {
    $sql = "";
    $sql .= "update users ";
    $sql .= "set ";
    $sql .= "id = " . $id . ", ";
    $sql .= "name = '" . $name . "', ";
    $sql .= "profile = '" . $profile . "' ";
    $sql .= "where id = " . $id;
    $stmt = getPdo()->prepare($sql);
    return $stmt->execute();
}

/**
 * @return PDOStatement ユーザー情報の連想配列を格納したPDOStatement
 * ユーザーの一覧を取得します。
 */
function getUsers()
{
    $sql = "";
    $sql .= 'select * ';
    $sql .= 'from users ';
    $stmt = getPdo()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @return PDOStatement ユーザー情報の連想配列を格納したPDOStatement
 * ユーザーの一覧を取得します。
 */
function getUserByPostId($post_id)
{
    $t = getTweet($post_id);
    $u = getUserById($t[0]['user_id']);
    return $u;
}

/**
 * ページ番号が存在する確認を行う。
 * @return bool ページ番号が存在する場合、trueを返す。
 */
function isExistPage($number, $max) {
    $p = intval($number);
    if ($p >= 1 and $p <= $max) {
        return true;
    } else {
        return false;
    }
}

/**
 * ユーザーIDからユーザー名を取得する。
 */
function getUserNameByUserId($user_id) {
  $u = getUserById($user_id);
}

/**
 * 返信用テキストを取得する。
 */
function getUserReplyText($post_id) {
    $u = getUserByPostId($post_id);
    return "Re: @" . $u['name'] . ' ';
}

/**
 * サニタイジング済み文字列を取得する
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
