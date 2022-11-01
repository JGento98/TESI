<?php 
require_once('./config.php');
 require_once( ROOT_PATH . '/includes/functions.php');
 require_once( ROOT_PATH . '/includes/head_section.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
        <div class="container">
            <form class="form-signin"method="post" action="login.php" style="width:30%;display:block;margin-left:auto;margin-right:auto;">
                <img src="logoblack.png" alt="" width="350" height="270" style="display:block;margin-left:auto;margin-right:auto;">
                <h4 class="h4 font-weight-normal text-center">Admin area</h4>
                <br>
                <label class="float-left">Username</label>
                <input type="text" name="username" id="username" autocomplete="off" class="form-control" required="" autofocus="">
                <br>
                <label class="float-left">Password</label>
                <input type="password" id="password" name="password" autocomplete="off" class="form-control"  required="" autofocus="">
                <br>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="login_btn">Sign in</button>
                <p><a href="index.php">Torna alla Home</a></p>
                <p class="mt-5 mb-3 text-muted">EasyScrape © 2021-2022</p>
            </form>
        </div>
</body>
</html>


      