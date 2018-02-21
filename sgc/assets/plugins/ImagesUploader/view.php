<?php
require_once('imagesuploader.class.php');

$folder = ImagesUploader::getFolder();
$path = ImagesUploader::getURL() . '/uploads/images/';

if (isset($_POST['subfolder'])) {
    $folder .= $_POST['subfolder'] . "/";
    $path .= $_POST['subfolder'] . "/";
}

$json = isset($_POST['value']) ? $_POST['value'] : '';

if (!empty($json)) {
    $files_arr = json_decode($json);

    if (sizeof($files_arr)) {
        foreach ($files_arr as $key => $file) {
            echo
                '<li id="' . $key . '">', PHP_EOL;

            if (file_exists($folder . $file->source)) {
                echo
                    '<div class="ph">
						<img class="prev_img" src="' . $path . $file->source . '?' . md5(microtime()) . '">
					</div>', PHP_EOL;
            } else {
                echo
                '<div class="ph no_image"></div>', PHP_EOL;
            }

            echo
                '	<div class="pane">
						<div class="header">
							<span class="delete_image_btn"></span>
							<div class="text_input">' . $file->filename . '</div>
						</div>

						<div class="inputs_pane">
							<div class="input_wrapper"><input type="text" name="image_title[]" maxlength="350" value="' . $file->title . '" placeholder="Título da imagem" autocomplete="off"></div>
						</div>
					</div>
				</li>', PHP_EOL;
        }
    }
}
