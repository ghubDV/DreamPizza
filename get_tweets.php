<?php
include 'dbconn.php';
//getting API classes to communicate to the API

require_once('TwitterAPIExchange.php');

session_start();
if(isset($_SESSION['current_page'])){
  $prev_page=$_SESSION['current_page'];
  $_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
}
else{
  $prev_page='';
}

//check if the login session expired

if(!isset($_SESSION['email'])){
  header('location:'.$prev_page);
}

if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'])>1800){
  header('location:logout');
}
else if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'])<1800){
  $_SESSION['last_activity']=time();
}
else if(!isset($_SESSION['last_activity'])){
  $_SESSION['last_activity']=time();
}

//Twitter API credentials
$settings = array(
  'oauth_access_token' => "your_access_token",
  'oauth_access_token_secret' => "your_access_token_secret",
  'consumer_key' => "your_consumer_key",
  'consumer_secret' => "your_access_secret"
);

$user_email=$_SESSION['email'];

if(empty($_SESSION['twitter'])){
  header('location:myaccount?_twitter');
  die();
}

$screen_name=$_SESSION['twitter'];
$url = "https://api.twitter.com/1.1/search/tweets.json";
$requestMethod = "GET";
$hashtag='order';
$getfield = '?q=from:'.$screen_name.'+%23order+*+@DreamPzza';

$twitter = new TwitterAPIExchange($settings);
$string = json_decode( $twitter->setGetfield($getfield)
                  ->buildOauth($url, $requestMethod)
                  ->performRequest(), true
                     );

$tweetCount=0;
foreach($string['statuses'] as $fields){
  $tweet=$fields['text'];
  $tweet_usr=$fields['user']['screen_name'];
  $tweet = substr($tweet,strpos($tweet, "*")+1);
  $tweet = substr($tweet,0,strpos($tweet, "*"));
  if($tweet!=''){
    $tweetCount++;
    $tweetArr[$tweetCount]=$tweet;
    $idArr[$tweetCount]=$fields['id_str'];
  }
}

//check if there are new tweets

$query="SELECT all_tweets FROM users WHERE email = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s", $user_email);
$sql_sec->execute();
$result=$sql_sec->get_result();
$fetch=$result->fetch_array(MYSQLI_ASSOC);
$db_total_tweets = $fetch['all_tweets'];

$query="SELECT tweet_id FROM tweets WHERE email = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s", $user_email);
$sql_sec->execute();
$result=$sql_sec->get_result();


while($row=$result->fetch_array(MYSQLI_ASSOC)){
  if ($idArr[1]==$row['tweet_id']){

    //update the number of tweets

    if($db_total_tweets != $tweetCount){

      $query="UPDATE users SET all_tweets = ? WHERE email = ?";
      $sql_sec=$db_connect->prepare($query);
      $sql_sec->bind_param("is",$tweetCount,$user_email);
      $sql_sec->execute();
      $result=mysqli_affected_rows($db_connect);

      if(!$result){
      header('location:your_orders?db_error');
      die();
      }
    }

    //eliminate delted tweets from the database

    $query="SELECT tweet_id FROM tweets WHERE email = ?";
    $sql_sec=$db_connect->prepare($query);
    $sql_sec->bind_param("s",$user_email);
    $sql_sec->execute();
    $result=$sql_sec->get_result();

    while($row=$result->fetch_array(MYSQLI_ASSOC)){
      $delete=true;
      foreach($idArr as $tweet_id){
        if($row['tweet_id']==$tweet_id){
          $delete=false;
          break;
        }
      }
      if($delete==true){
        $query="DELETE FROM tweets WHERE tweet_id = ?";
        $sql_sec=$db_connect->prepare($query);
        $sql_sec->bind_param("s",$row['tweet_id']);
        $sql_sec->execute();

      }
    }
    header('location:'.$prev_page);
    die();
  }
}

echo $tweetArr[1].'<br>';

//parsing the tweet and getting the price of the order

//initializing variables

$poz_qty=0;
$poz_name=3;
$j=0;
$prod_name="";
$total_price=0;

//splitting the words when whitespace or , are encountered

$parse = preg_split('#[\\s,]#', $tweetArr[1], -1, PREG_SPLIT_NO_EMPTY);

foreach($parse as $str){
  if($j==$poz_qty){
    $prod_qty=(int)$str;
    if($prod_qty>25 || $prod_qty<=0){
      header('location:your_orders?_error');
      die();
    }
    $poz_qty+=4;
  }
  else if($j==$poz_name){
    $prod_name=$str;
    $poz_name+=4;
    if(isset($prod_qty)){
      $query="SELECT product_price FROM products WHERE product_name = ?";
      $sql_sec=$db_connect->prepare($query);
      $sql_sec->bind_param("s",$prod_name);
      $sql_sec->execute();
      $result=$sql_sec->get_result();
      $fetch=$result->fetch_array(MYSQLI_ASSOC);
      if(mysqli_num_rows($result)){
        $total_price+=$prod_qty*$fetch['product_price'];
      }
      else{
        header('location:your_orders?_error');
        die();
      }
    }
    else{
        header('location:your_orders?_error');
        die();
    }
  }
  $j++;
}

if($j<3){
  header('location:your_orders?_error');
  die();
}

//adding the order in the database

$from="twitter";
$query="INSERT INTO orders (email, products_ordered, total_price, order_from) VALUES (?, ?, ?, ?)";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("ssis",$user_email,$tweetArr[1],$total_price,$from);
$sql_sec->execute();
$result=mysqli_affected_rows($db_connect);

if(!$result){
  header('location:your_orders?db_error');
  die();
}

//update the total number of tweets by the user

if($db_total_tweets != $tweetCount){

  $query="UPDATE users SET all_tweets = ? WHERE email = ?";
  $sql_sec=$db_connect->prepare($query);
  $sql_sec->bind_param("is",$tweetCount,$user_email);
  $sql_sec->execute();
  $result=mysqli_affected_rows($db_connect);

  if(!$result){
  header('location:your_orders?db_error');
  die();
  }
}

foreach($idArr as $tweet_id){
  $query="SELECT tweet_id FROM tweets WHERE tweet_id = ?";
  $sql_sec=$db_connect->prepare($query);
  $sql_sec->bind_param("s",$tweet_id);
  $sql_sec->execute();
  $result=$sql_sec->get_result();

  if(!mysqli_num_rows($result)){
    $query="INSERT INTO tweets (email, tweet_id) VALUES (?, ?)";
    $sql_sec=$db_connect->prepare($query);
    $sql_sec->bind_param("si",$user_email,$tweet_id);
    $sql_sec->execute();
    $result=mysqli_affected_rows($db_connect);

    if(!$result){
      header('location:your_orders?db_error');
      die();
    }
  }
}

//eliminate delted tweets from the database

$query="SELECT tweet_id FROM tweets WHERE email = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s",$user_email);
$sql_sec->execute();
$result=$sql_sec->get_result();

while($row=$result->fetch_array(MYSQLI_ASSOC)){
  $delete=true;
  foreach($idArr as $tweet_id){
    if($row['tweet_id']==$tweet_id){
      $delete=false;
      break;
    }
  }
  if($delete==true){
    $query="DELETE FROM tweets WHERE tweet_id = ?";
    $sql_sec=$db_connect->prepare($query);
    $sql_sec->bind_param("s",$row['tweet_id']);
    $sql_sec->execute();

  }
}

header('location:'.$prev_page);
die();

?>
