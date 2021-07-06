<?php
require ('functions.php');
$status = $_POST['statusList'];

set_status ($status, $_SESSION['user_id']);
set_flash_message('success', "Профиль успешно обновлен");
redirect_to ("page_profile.php?user_id=".$_SESSION['user_id']);