<?php
    require ("functions.php");
    $user_id = $_GET['user_id'];

    if (!(is_not_logged_in ())) {
        redirect_to ("page_login.php");
    }

    if (!($_SESSION['role'] == 'admin') and !is_author($_GET['user_id'], $user_id)) {
    	set_flash_message("danger", "Можно редактировать только свой профиль");
    	redirect_to("users.php");
	}
    
    $user = get_user_by_id ($user_id);


    delete($user_id);
    set_flash_message ("success", "Пользователь удален");
    if ($_SESSION['log-in'] == $user['email']) {
    	session_unset();
    	session_destroy();
    	redirect_to ("page_register.php");
    } else {    	
    	redirect_to ("users.php");
    }

   

    