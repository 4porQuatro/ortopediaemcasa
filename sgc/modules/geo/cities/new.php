<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "geo_cities";

	$entity = entity($mysqli, $table);

	if(isset($_POST['op']) && $_POST['op'] == "insert"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			// insert record
			$stmt_insert = $mysqli->prepare("INSERT INTO " . $table . " (name, geo_country_id, map_x_position, map_y_position, active) VALUES(?, ?, ?, ?, ?)") or die('<h3>Preparing to insert record...</h3>' . $mysqli->error);
			$stmt_insert->bind_param(
				"siiii",
				$posts['name'],
				$posts['geo_country_id'],
				$posts['map_x_position'],
				$posts['map_y_position'],
				$posts['active']
			);
			$stmt_insert->execute() or die('<h3>Executing statement...</h3>' . $stmt_insert->error);
			$stmt_insert->store_result();

			/*............................................................................*/

			$mysqli->commit();
			header("location: index.php?insert=success");
			exit;
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<?php $template->importMetaTags(); ?>
<?php $template->importHeadTitle(); ?>
<?php $template->importStyles(); ?>
<style>
#map { width:262px; height:530px; position:relative; background-image:url(../../../assets/img/map.png); background-size:contain; background-position:left top; background-repeat:no-repeat; position:relative; cursor:crosshair; }
#pin { width:15px; height:22px; position:absolute; opacity:0; }
</style>
<?php $template->importHeadScripts(); ?>
</head>
<body>
	<?php $template->printSideBar($mysqli); ?>

    <div id="data_container">
    	<div class="record_options_pane">
    		<a class="record_opt_btn" href="index.php">&larr; Cancelar</a>
        </div>
    	<h2>Inserir registo</h2>

        <?php
			if(isset($errors) && !empty($errors))
				echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
		?>

        <ul id="form_menu">
            <li>Geral</li>
            <li>Mapa</li>
        </ul>
        <form class="form_model" name="insert_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
            <div class="form_pane">
                <table>
                    <tr>
                        <th>Nome*</th>
                        <th style="width:25%">País*</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="name" maxlength="<?= $entity->maxlen("name") ?>" value="<?= $entity->output("name") ?>"></td>
                        <td>
                        	<select name="geo_country_id">
                            	<option value="">Selecione...</option>
                            	<?php
									$result = $mysqli->query("SELECT id, name FROM geo_countries ORDER BY name") or die($mysqli->error);
									while($rec = $result->fetch_object()){
										$selected = ($rec->id == $entity->getScopeValue("geo_country_id")) ? ' selected' : '';
								?>
								<option value="<?= $rec->id ?>"<?= $selected; ?>><?= $rec->name ?></option>
								<?php
									}
								?>
                            </select>
                        </td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <td><input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Ativar</label></td>
                    </tr>
                </table>
            </div>

			<div class="form_pane">
				<div id="map">
					<div id="pin">
						<?= file_get_contents(APP_ROOT . '/public/img/map-pin.svg') ?>
					</div>
				</div>
				<input type="hidden" name="map_x_position">
				<input type="hidden" name="map_y_position">
            </div>

            <input type="submit" value="Gravar">
            <input type="hidden" name="op" value="insert">
        </form>
	</div>

	<?php $template->importScripts(); ?>
	<script type="text/javascript">
	function setMapPins()
	{
		var $map = $('#map'),
			$pin = $('#pin'),
			$posX = $('[name=map_x_position]'),
			$posY = $('[name=map_y_position]');

		$map.click(
			function(event)
			{
				var left = event.clientX - $map.offset().left - 7;
				var top = event.clientY - $map.offset().top - 22;

				setPos(left, top);
			}
		);

		var setPos = function(x, y)
		{
			$posX.val(x);
			$posY.val(y);
			$pin.css({
				'opacity' : 1,
				'left'	  : x,
				'top'	  : y
			});
		}
	}

	setMapPins();
	</script>
</body>
</html>
