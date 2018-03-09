<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/includes.php');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<?php $template->importMetaTags(); ?>
		<?php $template->importHeadTitle(); ?>
		<?php $template->importStyles(); ?>
		<?php $template->importHeadScripts(); ?>
	</head>

	<body>
		<?php $template->printSideBar($mysqli); ?>

		<div id="data_container">
	    	<div class="record_options_pane">
	        	<?php if($_SESSION['sgc_super_user']){ ?>
	    		<a class="record_opt_btn" href="new.php">Criar registo &rarr;</a>
	            <?php } ?>
	        </div>
	    	<h2>Records list</h2>

			<?php
	        	if(isset($_GET['insert']) && $_GET['insert'] == "success"){
			?>
	        <script>
			$(document).ready(function(){
				setTimeout(function(){$("p#insert_op").fadeOut(500);}, 3000);
			});
	        </script>
	        <?php
					echo '<p class="success" id="insert_op">O registo foi criado com sucesso!</p>';
				}
			?>

	        <?php
	        	if(isset($_GET['edit']) && $_GET['edit'] == "success"){
			?>
	        <script>
			$(document).ready(function(){
				setTimeout(function(){$("p#edit_op").fadeOut(500);}, 3000);
			});
			</script>
	        <?php
					echo '<p class="success" id="edit_op">O registo foi atualizado com sucesso!</p>';
				}
			?>

	        <div class="records_pane"></div>
		</div>

		<?php $template->importScripts(); ?>
	    <script src="../../../assets/js/jquery.cookies.js"></script>
	    <script src="../../../assets/plugins/RecordsList/records_list.js"></script>
	    <script>
	    $(document).ready(function(){
	        $('.records_pane').recordsList({
	            table: 'pages',
	            primary_key: 'id',
	            language_id: <?= $_SESSION['sgc_language_id'] ?>,
				columns: ['id', 'reference'],
	            sort_field: 'reference',
				removable: <?= ($_SESSION['sgc_super_user']) ? 'true' : 'false' ?>
	        });
	    });
	    </script>
	</body>
</html>
