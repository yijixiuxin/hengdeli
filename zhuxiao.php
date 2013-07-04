<?php
require_once(dirname(__FILE__)."/init.php");
model_user::logout();
header("location:login.html");