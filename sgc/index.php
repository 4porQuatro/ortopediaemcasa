<?php
	require_once("core/init.php");
	require_once(SGC_ROOT . DS .  'scripts' . DS . 'utilities.php');

	if(isset($_SESSION['sgc_user_id'])){
		header('location: main.php');
		exit;
	}

	/*
	*	VALIDATE VARS
	*/
	$errors = "";
	$max_login_attempts = 3;
	$throttle = 120;
	$remote_address = getUserIp();
	$allow_login = true;

	$table = "sgc_users";
	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "login"){
		// map posts
		$posts = $entity->mapPosts();

		// check attemps from this IP
		$rs_attempt = $mysqli->query("SELECT MAX(time_stamp) as 'last_time', COUNT(ip) 'attempts' FROM sgc_user_login_attempts WHERE ip = '" . $remote_address . "' AND time_stamp > (NOW() - INTERVAL " . $throttle . " MINUTE);") or die($mysqli->error);
		if($rs_attempt->num_rows){
			$attempt_obj = $rs_attempt->fetch_object();

			if($attempt_obj->attempts >= $max_login_attempts){
				$allow_login = false;
			}
		}

		if($allow_login){
			$user_obj = new stdClass;

			$stmt_user = $mysqli->prepare("SELECT user_id, name, email, password, super_user, active FROM $table WHERE email = ? LIMIT 0, 1");
			$stmt_user->bind_param("s", $posts['email']);
			$stmt_user->execute();
			$stmt_user->bind_result($user_obj->user_id, $user_obj->name, $user_obj->email, $user_obj->password, $user_obj->super_user, $user_obj->active);
			$stmt_user->store_result();

			if(!$stmt_user->num_rows){
				$errors .= 'Não existe nenhuma conta associada ao e-mail indicado';
			}else{
				$stmt_user->fetch();

				if(!Password::match($posts['password'], $user_obj->password)){
					$errors .= 'A password indicada está icorreta';
				}else if(!$user_obj->active){
					$errors .= 'A sua conta encontra-se desativada';
				}else{
					// get permissions
					$result_permissions = $mysqli->query("SELECT t1.* FROM sgc_menu AS t1 JOIN sgc_permissions AS t2 ON t1.menu_id = t2.menu_id AND t2.user_id = " . $user_obj->user_id . " AND t1.active AND super_user IN (0, " . $user_obj->super_user . ") ORDER BY t1.priority ASC;") or die('MySQLi error: [' . $mysqli->error . ']');

					if(!$result_permissions->num_rows){
						$errors .= 'Não possui permissões para aceder ao SGC!';
					}
				}
			}

			if(empty($errors)){
				// set session
				session_regenerate_id();

				$perm_arr = array();
				while($lines_permissions = $result_permissions->fetch_object()){
					$perm_arr[$lines_permissions->parent_id][$lines_permissions->menu_id] = $lines_permissions;
				}

				$names = explode(" ", $user_obj->name);
				$_SESSION['sgc_user_id'] = $user_obj->user_id;
				$_SESSION['sgc_username'] = $names[0];
				$_SESSION['sgc_super_user'] = $user_obj->super_user;
				$_SESSION['sgc_permissions_arr'] = $perm_arr;

				$_SESSION['KCFINDER'] = ['disabled' => false];

				/* get first language */
				$result_default_lang = $mysqli->query("SELECT * FROM languages ORDER BY priority LIMIT 0, 1");
				if($result_default_lang->num_rows){
					$default_lang = $result_default_lang->fetch_object();

					$_SESSION['sgc_language_id'] = $default_lang->id;
				}
				header('location: main.php');
				exit;
			}else{
				// insert login attempt
				$mysqli->query("INSERT INTO sgc_user_login_attempts (ip) VALUES ('" . $remote_address . "')");
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<?php $template->importMetaTags(); ?>
		<?php $template->importHeadTitle(); ?>
		<link rel="stylesheet" type="text/css" href="assets/css/login.css">
		<?php $template->importHeadScripts(); ?>
	</head>

	<body>
		<div class="wallpaper"></div>

	    <div id="box">
	   	  	<h1><?= $template->getAppTitle(); ?></h1>

	        <div class="error_pane">
				<?php
	            	if(isset($errors) && !empty($errors)){
						echo
						'<p><b>' . $errors . '</b></p>', PHP_EOL;
					}
				?>
	        </div>

	        <?php if($allow_login){ ?>
	        <form name="user_login_form" id="login_form" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
	            <input type="text" name="email" maxlength="200" value="<?php if(isset($_POST['email'])) echo trim(htmlentities($_POST['email'])); ?>" placeholder="Utilizador" autocomplete="off">
	            <input type="password" name="password" maxlength="20" placeholder="Password" autocomplete="off">

	            <input type="submit" value="entrar">
	            <input type="hidden" name="op" value="login">
	        </form>
	        <?php }else{ ?>
	        <div class="info_pane">
	            <h3>Excedeu o número de tentativas de login.</h3>
	            <p>Para mais informações, por favor <a href="mailto:info@4por4.pt">contacte-nos</a></p>
	        </div>
	        <?php } ?>
	    </div>

	    <?php $template->printFooter(); ?>

		<?php $template->importScripts(); ?>
	</body>
</html>
