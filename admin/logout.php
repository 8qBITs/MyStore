<?php
	require("../includes/config.php");
	mysqli_query($link, "DELETE FROM `logins` WHERE hash='".mysqli_real_escape_string($link,$_COOKIE["loggedin"])."'");
	setcookie("loggedin","");
	session_destroy();
	echo '<script>window.location.href = "../index.php";</script>';

?>