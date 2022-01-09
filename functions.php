<?php

session_start();

function get_user_by_email ($email) {
	$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
	$email = $_POST['email'];
	$sql = "SELECT * FROM users WHERE email=:email";
	$statement = $pdo->prepare($sql);
	$statement->execute(['email' => $email]);
	$user = $statement->fetch(PDO::FETCH_ASSOC);

	return $user;
}

function login ($email, $password) {
	$user = get_user_by_email($email);
	if (empty($user)) {
		return false;
	} elseif (!password_verify($password, $user['password'])) {
		return false;
	} else {
		return $user;
	}
}

function add_user ($email, $password, $role) {
	$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
	$sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, 'user')"; //по умолчанию задаем юзер в роль
	$statement = $pdo->prepare($sql);
	$statement->execute(['email' => $email,
						'password' => password_hash($password, PASSWORD_DEFAULT)]);

	$userMail = $email;
	$statement = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
	$statement->execute([$userMail]);
	$user_id = $statement->fetchColumn();
	return $user_id;
}

function set_flash_message ($name, $message) {
	$_SESSION[$name] = $message;
}

function display_flash_message ($name) {
	if (isset($_SESSION[$name])) {
		echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
		unset ($_SESSION[$name]);
	}
}

function redirect_to ($path) {
	header("Location: {$path}");
	exit;
}


function is_not_logged_in () {
	if (!isset($_SESSION['log-in']))
		return true;
	else
		return false;
}

function deleteSessionIfExist () {
	if (session_id() !="") {
		$_SESSION = array();
		session_unset();
		session_destroy();
	}
}


function AllUsers () {
	$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
    $sql = "SELECT * FROM users";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

function is_admin () {
	if (isset($_SESSION['log-in']) && !empty($_SESSION['log-in'])) {
            $pdo = new PDO("mysql:host=localhost;dbname=my_project;", "root", "");
            $log_in = $_SESSION['log-in'];
            $sql = "SELECT role FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$log_in]);
            $role = $stmt->fetchColumn();
            if ($role == 'admin') {
                $_SESSION['role'] = 'admin';
            }
            else $_SESSION['role'] = 'user';

        }

        else
            return false;
}

function edit_info ($user_id = null, $username, $job, $phone, $address) {
	if ($user_id) {
		$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
		$sql = "UPDATE users SET  name = '$username', position = '$job', phone = '$phone', address = '$address' WHERE user_id = $user_id";
		$statement = $pdo->prepare($sql);
		$statement->execute();
	}
}

function set_status ($status, $user_id=null) {
	if ($user_id) {
		$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
		$sql = "UPDATE users SET status='$status' WHERE user_id=$user_id";
		$statement = $pdo->prepare($sql);
		$statement->execute();
	}
}

/*$user_id int
$image string
проверяет имеется ли аватар у пользователя
return: boolean*/

function has_image($user_id) {
	$image = get_user_by_id ($user_id);
	if ($user_id) {
		if (!empty ($image['avatar'])) {
			echo $image['avatar'];
		} else {
			echo "img/demo/avatars/avatar-m.png";
		  }
	}
}

function upload_avatar ($avatar, $user_id) {
		$ext = pathinfo($avatar['name'], PATHINFO_EXTENSION);
		$name = uniqid() . "." . $ext;
		$path = "img/demo/avatars/";
		move_uploaded_file($avatar['tmp_name'], $path . $name);
		$avatarlink = $path . $name;

		$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
		$sql = "UPDATE users SET avatar='$avatarlink' WHERE user_id=$user_id";
		$statement = $pdo->prepare($sql);
		$statement->execute();
			
}

function add_social_links ($vk, $telegram, $instagram, $user_id=null) {
	if ($user_id) {
		$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
		$sql = "UPDATE users SET vk='$vk', telegram='$telegram', instagram='$instagram' WHERE user_id=$user_id";
		$statement = $pdo->prepare($sql);
		$statement->execute();
	}
}

function get_user_by_id ($user_id) {
	$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
	$sql = "SELECT * FROM users WHERE user_id='$user_id'";
	$statement = $pdo->prepare($sql);
	$statement->execute();
	$user = $statement->fetch(PDO::FETCH_ASSOC);

	return $user;
}

function is_author ($logged_user_id, $edit_user_id) {
	return ($logged_user_id == $edit_user_id);	
}

function edit_credentials ($user_id, $email, $password) {
	if ($user_id) {
		$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");
		$sql = "UPDATE users SET  email = :email, password = :password WHERE user_id = :user_id";
		$statement = $pdo->prepare($sql);
		$statement->execute(['email' => $email,
							 'password' => password_hash($password, PASSWORD_DEFAULT),
							 'user_id' => $user_id]);
	}
}

function delete ($user_id) {
	$user = get_user_by_id ($user_id);
	if ($user_id) {
		$pdo = new PDO ("mysql:host=localhost;dbname=my_project;", "root", "");		
		$sql = "DELETE FROM users WHERE user_id = :user_id";
		$statement = $pdo->prepare($sql);
		$statement->execute(['user_id' => $user_id]);

		$path = $user['avatar'];
		unlink($path);
	}
}
