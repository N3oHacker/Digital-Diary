<!-- Coded by Mahender Singh Aka NeoHacker. 
A diary is a record (originally in handwritten format) with discrete entries arranged by date reporting on what has happened 
over the course of a day or other period. A personal diary may include a person's experiences, and/or thoughts or feelings, 
including comments on current events outside the writer's direct experience. Someone who keeps a diary is known as a diarist. 
Diaries undertaken for institutional purposes play a role in many aspects of human civilization, including government records (e.g. Hansard),
business ledgers and military records. In British English, the word may also denote a preprinted journal format.
-->
<html><head>
<title>Digital Diary</title>
<link rel="stylesheet" type="text/css" media="screen" href="./style.css" />
<meta charset="UTF-8">
</head><body>
<div class=wrapper>
<span>
<?php 
date_default_timezone_set("PRC");
echo date("M jS, l");?>
</span>
<div>
<form method="POST" action="index.php"> 
    <div><textarea name="msg" rows="4"></textarea></div>
    <div class=btn><input name="Btn" type="submit" value="Submit"></div>
</form>
</div>

<?php
/******************************Configurations*******************************************/

$SAME_FILE = True;    // if set to True, auto-archive function is disabled.

/***************************************************************************************/
$filename = "./posts.txt";
file_exists($filename) or file_put_contents($filename, "\xEF\xBB\xBF<div class=post><div class=time>".date("M d##H:i##D")."</div><div class=msg>-- start --</div></div>");
$original_posts = file_get_contents($filename);
if (isset($_POST["msg"])) {
    $msg = $_POST["msg"];
    ($msg=='') and die('Empty message.');
    $msg = preg_replace("/\bhttp:\/\/(\w+)+.*\b/",'<a href="$0">$0</a>',$msg);
    preg_match("/(\w{3}) (\d{2})##\d{2}:\d{2}##\w{3}/s",$original_posts,$matches) or die('No date found. Please contact pilicurg@163.com');
    $post_month= $matches[1];
    $post_day= $matches[2];
    $current_month = date("M");
    $current_day = date("d");
    if($SAME_FILE || ($current_month===$post_month)){
        if($current_day===$post_day && $current_month===$post_month){
            $time = date("H:i");
        }
        else{
            $time = date("M d##H:i##D");
        }
        $posts = "<div class=post><div class=time>$time</div><div class=msg>$msg</div></div>" . $original_posts;
        file_put_contents($filename, $posts);
        $posts = preg_replace("/(>\w{3} \d{2})##(\d{2}:\d{2})##(\w{3}<)/","$1<br />$2<br />$3",$posts);
        echo nl2br($posts);
    }
    else{
        $time = date("M d##H:i##D");
        $posts = "<div class=post><div class=time>$time</div><div class=msg>$msg</div></div>";
        if($post_month==='Dec' && $current_month==='Jan'){
            $newfile = "posts_".strval(intval(date("Y"))-1).'_'.$post_month.'.txt';
        }
        else{
            $newfile = "posts_".date("Y").'_'.$post_month.'.txt';
        }
        if (rename($filename, $newfile)){
            file_put_contents($filename, "\xEF\xBB\xBF".$posts);
        }
        else{
            die('Unable to rename $filename to $newfile');
        }
        $posts = preg_replace("/(>\w{3} \d{2})##(\d{2}:\d{2})##(\w{3}<)/","$1<br />$2<br />$3",$posts);
        echo nl2br($posts);
    }    
    redirect('index.php');
}
else{
    $posts = preg_replace("/(>\w{3} \d{2})##(\d{2}:\d{2})##(\w{3}<)/","$1<br />$2<br />$3",$original_posts);
    echo nl2br($posts);
}

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}

?>
</div>
<span><a href="#">©2017 Coded by Mahender Singh</a></span>
</body>
</html>