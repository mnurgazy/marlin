<?php
require ("functions.php");

session_start();

deleteSessionIfExist ();

redirect_to ("page_login.php");
