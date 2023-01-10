<?php
require 'mainconfig.php';
unset($_SESSION['login']);
exit(header("Location: ".$config['web']['base_url']."auth/login.php"));