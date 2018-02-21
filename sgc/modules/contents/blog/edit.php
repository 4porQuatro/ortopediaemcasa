<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');

	$table = "posts";
	$pk = "id";

	if(!isset($_GET['edit_hash'])){
		header("location: index.php");
		exit;
	}

	$entity = entity($mysqli, $table);
	$entity->mapDBValues($pk, $_GET['edit_hash'], $language_id);

	$entity->setQueryFields([
		's-title',
		's-slug',
		's-published_at',
		's-content',
		's-description',
		's-keywords',
		's-list_images',
		's-banner_images',
		'i-highlight',
		'i-active'
	]);

	if(!$entity->getDBValue($pk)){
		header("location: index.php");
		exit;
	}

	if(isset($_POST['op']) && $_POST['op'] == "update"){
		// map posts
		$posts = $entity->mapPosts();

		// validate
		$errors = "";

		if(!$entity->checkRequiredFields()){
			$errors .= "<br>Preencha todos os campos obrigatórios.";
		}

		if(empty($errors)){
			$mysqli->autocommit(false);

			$entity->posts_arr['slug'] = ($posts['title'] != $entity->getDBValue("title")) ? createSlug($posts['title'], $table, $mysqli) : $entity->getDBValue("slug");

			// update record
			$stmt_update = $mysqli->prepare(
				"UPDATE $table SET {$entity->query_fields['names=placeholders']}
				WHERE language_id = $language_id
				AND $pk = {$entity->getDBValue($pk)}"
			) or die('<h3>Preparing statement...</h3>' . $mysqli->error);
			$stmt_update->bind_param($entity->query_fields['types'], ...$entity->getQueryFieldsParams());
			$stmt_update->execute() or die('<h3>Updating record...</h3>' . $stmt_update->error);

			/*......................................................................................*/

			/*
			*	Delete related posts
			*/
			$mysqli->query("DELETE FROM " . $table . "_related WHERE post_id = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die('<h3>Deleting related posts...</h3>' . $mysqli->error);

			/*
			 *	Insert related posts
			 */
			if(isset($_POST['rel_post_id'])){
				$insert_rel_post_query = "INSERT INTO " . $table . "_related  (post_id, language_id, related_post_id) VALUES (" . $entity->getDBValue($pk) . ", " . $language_id . ", ?) ON DUPLICATE KEY UPDATE related_post_id = ?";
				$stmt_insert_rel_post = $mysqli->prepare($insert_rel_post_query) or die('<h3>Preparing to insert related post...</h3>' . '<p>' . $insert_rel_post_query . '</p>' . $mysqli->error);
				$stmt_insert_rel_post->bind_param("ii", $rel_post_id, $rel_post_id);

				foreach($_POST['rel_post_id'] as $key=>$row_index){
					$rel_post_id = $_POST['rel_post_id'][$key];

					if(!empty($rel_post_id)){
						$stmt_insert_rel_post->execute() or die('<h3>Inserting related post...</h3>' . '<p>' . $insert_rel_post_query . '</p>' . $stmt_insert_rel_post->error);
					}
				}
			}

			/*............................................................................*/

			$mysqli->commit();
			header("location: index.php?edit=success");
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
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
        <?php $template->importHeadScripts(); ?>
    </head>

    <body>
        <?php $template->printSideBar($mysqli); ?>

        <div id="data_container">
            <div class="record_options_pane">
                <a class="record_opt_btn" href="index.php">&larr; Cancelar</a>
            </div>
            <h2>Editar registo nr.º <?= $entity->getDBValue($pk); ?></h2>

            <?php
                if(isset($errors) && !empty($errors))
                    echo '<p class="error"><b>Foram encontrados os seguintes erros:</b>' . $errors . '</p>';
            ?>

            <ul id="form_menu">
                <li>Geral</li>
                <li>SEO</li>
	            <li>Relacionados</li>
                <li>Imagens</li>
            </ul>
            <form class="form_model" name="edit_record_form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" autocomplete="off">
                <div class="form_pane">
					<table>
                        <tr>
                            <th>Título *</th>
                            <th style="width: 25%;">Data *</th>
                        </tr>
                        <tr>
                            <td><input type="text" name="title" maxlength="<?= $entity->maxlen("title") ?>" value="<?= $entity->output("title") ?>"></td>
							<td><input type="text" name="published_at" maxlength="10" value="<?= $entity->output("published_at") ?>"></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <th>Conteúdo *</th>
                        </tr>
                        <tr>
                            <td><textarea name="content"><?= $entity->output("content"); ?></textarea></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
							<td style="width: 20%;">
								<input type="checkbox" name="highlight" id="highlight" value="1"<?php if($entity->getScopeValue("highlight") == 1) echo ' checked'; ?>> <label for="highlight">Destacar</label>
							</td>
                            <td>
								<input type="checkbox" name="active" id="active" value="1"<?php if($entity->getScopeValue("active") == 1) echo ' checked'; ?>> <label for="active">Ativar</label>
							</td>
                        </tr>
                    </table>
                </div>

                <div class="form_pane">
                    <table>
                        <tr>
                            <th>Descrição</th>
                        </tr>
                        <tr>
                            <td><input type="text" name="description" value="<?= $entity->output("description") ?>" placeholder="Insira uma breve descrição do registo." maxlength="180"></td>
                        </tr>

                        <tr>
                            <th>Palavras-chave</th>
                        </tr>
                        <tr>
                            <td><input type="text" name="keywords" value="<?= $entity->output("keywords") ?>" placeholder="Insira palavras-chave relacionadas com o registo (ex: kw1,kw2,kw3)." maxlength="180"></td>
                    </table>
                </div>

	            <div class="form_pane">
					<?php
						if(!isset($_POST['rel_post_key'])){
							//-> get rel_posts for this record
							$result_rel_post = $mysqli->query("SELECT * FROM posts_related WHERE post_id = " . $entity->getDBValue($pk) . " AND language_id = " . $language_id) or die($mysqli->error);
							if($result_rel_post->num_rows){
								$index = 0;
								while($rel_post_rec = $result_rel_post->fetch_object()){
									$_POST['rel_post_key'][$index] = $index;
									$_POST['rel_post_id'][$index] = $rel_post_rec->related_post_id;
									$index++;
								}
							}else{
								$_POST['rel_post_key'][0] = 0;
								$_POST['rel_post_id'][0] = '';
							}
						}
					?>
	                <table>
	                    <?php
							foreach($_POST['rel_post_key'] as $key=>$value){
	                    ?>
	                    <tr class="rel_post_row">
	                        <td>
	                        	<select name="rel_post_id[]">
	                            	<option value="">posts</option>
									<?php
	                                    $result = $mysqli->query("SELECT id, title FROM posts WHERE id != " . $entity->getDBValue($pk) . " AND language_id = " . $language_id);
	                                    if($result->num_rows){
	                                        while($rec = $result->fetch_object()){
												$selected = (isset($_POST['rel_post_id'][$key]) && $_POST['rel_post_id'][$key] == $rec->id) ? ' selected' : '';
	                                ?>
	                                <option value="<?= $rec->id ?>"<?= $selected ?>><?= $rec->title ?></option>
	                                <?php
											}
										}
									?>
	                            </select>
	                        </td>
	                        <td style="width:1%">
	                        	<input type="hidden" name="rel_post_key[]" value="<?= $key ?>">
	                        	<span class="inline_btn rel_post_remove_row">Remover</span>
	                        </td>
	                    </tr>
	                    <?php
							}
	                    ?>
	                    <tr>
	                        <td colspan="4">
	                            <input type="button" class="inline_btn" id="rel_post_add_btn" value="Adicionar post">
	                        </td>
	                    </tr>
	                </table>
	            </div>

	            <div class="form_pane">
	            	<h3>Imagens para a listagem</h3>
	            	<input type="hidden" name="list_images" value="<?= $entity->output("list_images") ?>">

					<hr class="hline">

	            	<h3>Imagens para o banner</h3>
	            	<input type="hidden" name="banner_images" value="<?= $entity->output("banner_images") ?>">
	            </div>

                <input type="submit" value="Gravar">
                <input type="hidden" name="op" value="update">
            </form>
        </div>
        <?php $template->importScripts(); ?>
		<script type="text/javascript" src="../../../assets/plugins/CKEditor/ckeditor.js"></script>
	    <script type="text/javascript" src="../../../assets/js/dynamicFields.js"></script>
	    <script type="text/javascript" src="../../../assets/plugins/ImagesUploader/image_uploader.jquery.js"></script>
	    <script type="text/javascript">
		CKEDITOR.replaceAll(function(textarea, config){});

		$('[name="published_at"]').datepicker({
			dateFormat: 'yy-mm-dd'
		});

		dynamicFields('#rel_post_add_btn', '.rel_post_remove_row', '.rel_post_row');

		$('[name*="images"], [name="images"]').imagesUploader({
			subfolder: '<?= $table ?>',
		});
	    </script>
    </body>
</html>
