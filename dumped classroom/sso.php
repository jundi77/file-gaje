<?php
require_once('config.php');
$url = 'https://classroom.its.ac.id/auth/oauth2/login.php?id=1&wantsurl=https%3A%2F%2Fclassroom.its.ac.id%2F&sesskey='.sesskey();
header('Location: '.$url);
die();
?>
