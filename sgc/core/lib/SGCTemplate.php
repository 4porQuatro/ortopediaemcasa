<?php
	class SGCTemplate{
		private $owner;
		private $owner_website;
		private $app_title;

		/*
		*	CONSTRUCTOR
		*/
		public function __construct(){
			$this->owner = "4por4";
			$this->owner_website = "http://www.4por4.pt";
			$this->app_title = "SGC - Sistema de Gestão de Conteúdos";
		}

		/*
		*	GETTERS
		*/
		public function getOwner(){ return $this->owner; }
		public function getOwnerWebsite(){ return $this->owner_website; }
		public function getAppTitle(){ return $this->app_title; }

		/*
		*	SETTERS
		*/
		public function setOwner($owner){ $this->owner = $owner; }
		public function setOwnerWebsite($owner_website){ $this->owner_website = $owner_website; }
		public function setAppTitle($app_title){ $this->app_title = $app_title; }


		/*......................................... PRINT METHODS .........................................*/

		public function importMetaTags(){
			echo
			'<meta charset="UTF-8">', PHP_EOL;
		}

		public function importHeadTitle(){
			echo
			'<title>' . $this->getAppTitle() . '</title>', PHP_EOL;
		}

		public function importStyles(){
			echo
			'<link rel="stylesheet" type="text/css" href="/assets/css/layout.css">
			<link rel="stylesheet" type="text/css" href="/assets/plugins/CustomScrollbar/jquery.mCustomScrollbar.min.css">', PHP_EOL;
		}

		public function importHeadScripts(){
			echo
			'<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
			<script type="text/javascript" src="/assets/js/modernizr.custom.17573.js"></script>', PHP_EOL;
		}
		public function importScripts(){
			echo
			'<script type="text/javascript" src="/assets/js/modernizr.custom.17573.js"></script>
			<script type="text/javascript" src="/assets/js/features.js"></script>
			<script type="text/javascript" src="/assets/plugins/CustomScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
			<script type="text/javascript" src="/assets/plugins/CustomInputs/custom_inputs.jquery.js"></script>
			<script>
			$(window).load(function(){
				$(\'select\').customSelect();
				$(\'input[type="radio"]\').customRadioButton();
				$(\'input[type="checkbox"]\').customCheckBox();
			});
			</script>', PHP_EOL;
		}

		/**
		 *	This is a recursive function for printing the menu tree
		 *
		 *	@param $arr				The array with the menu data
		 *	@param $parent			The parent that is the starting point of the tree
		 *	@param $folder			The corresponding folder in the files structure
		 *	@param $level			The level of the current iteration
		 *
		 */
		private function printMenu($arr, $parent = NULL, $folder = NULL, $level = 0){
			echo
			'<ul class="menu">', PHP_EOL;
			foreach($arr[$parent] as $id => $val){
				if($level == 0){
					echo
					'<li><span>' . $val->title . '</span>', PHP_EOL;
				}else{
					$class = (preg_match('|\b' . $arr[NULL][$val->parent_id]->folder . '/' . $val->folder . '\b|', $_SERVER['REQUEST_URI']))  ? ' class="active"' : '';
					echo
					'<li' . $class . '><a href="/modules/' . $folder . '/' . $val->folder . '">' . $val->title . '</a>', PHP_EOL;
				}
				if(isset($arr[$id])){
					$this->printMenu($arr, $id, $val->folder, $level + 1);
				}
				echo
				'</li>', PHP_EOL;
			}
			echo
			'</ul>', PHP_EOL;
		}

		public function printSideBar($mysqli){
			$result_languages = $mysqli->query("SELECT * FROM languages ORDER BY priority") or die($mysqli->error);

			echo
			'<div id="side_bar">
				<header>
					<div class="top">
						<h1><a href="/main.php">' . $this->getAppTitle() . '</a></h1>

						<a class="logout_btn sprite power_btn" href="/scripts/logout.php">Logout</a>
					</div>

					<div class="bottom">
						<div class="username_pane">Olá ' . $_SESSION['sgc_username'] . '</div>', PHP_EOL;

			if($result_languages->num_rows > 1){
				echo
				'

						<form name="language_form" method="post" action="/scripts/change_language.php">
							<select name="language_id">', PHP_EOL;

				while($lines_languages = $result_languages->fetch_object()){
					$selected = NULL;
					if($lines_languages->id == $_SESSION['sgc_language_id']){
						$selected = 'selected="selected"';
					}

					echo
					'			<option value="' . $lines_languages->id . '" ' . $selected . '>' . $lines_languages->iso . '</option>', PHP_EOL;
				}
				echo
				'			</select>
							<input type="hidden" name="op" value="change_language">
						</form>', PHP_EOL;
			}

			echo
			'
					</div>
				</header><div id="menu_wrapper">
					<div class="menu_pane">', PHP_EOL;

			if(isset($_SESSION['sgc_permissions_arr']) && sizeof($_SESSION['sgc_permissions_arr'])){
				$this->printMenu($_SESSION['sgc_permissions_arr']);
			}

			echo
			'		</div>
				</div>

				<div class="credits">
					desenvolvido por <a href="' . $this->getOwnerWebsite() . '" target="_blank">' . $this->getOwner() . '</a>
				</div>
			</div>', PHP_EOL;
		}

		public function printFooter(){
			echo
			'<footer>desenvolvido por <a href="' . $this->getOwnerWebsite() . '" target="_blank">' . $this->getOwner() . '</a></footer>', PHP_EOL;
		}
	}

	$template = new SGCTemplate();
?>
