<?php
require ('functions.php');
$avatar = $_FILES['avatar'];

upload_avatar ($avatar, $_SESSION['user_id']);
set_flash_message('success', "Профиль успешно обновлен");
redirect_to ("page_profile.php?user_id=".$_SESSION['user_id']);