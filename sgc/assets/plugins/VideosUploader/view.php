<?php
require_once('videosuploader.class.php');

$folder = VideosUploader::getFolder();
$path = VideosUploader::getURL() . '/uploads/videos/';

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
                '<li id="' . $key . '">
					<div class="pane">
						<div class="header">
							<span class="delete_video_btn"></span>
							<div class="text_input">' . $file->filename . '</div>
						</div>

						<div class="inputs_pane">
							<div class="input_wrapper"><input type="text" name="video_title[]" maxlength="150" value="' . $file->title . '" placeholder="Título do vídeo" autocomplete="off"></div>

							<a class="dup_download_btn" href="' . $path . $file->source . '" target="_blank">Download</a>
						</div>
					</div>
				</li>', PHP_EOL;
        }
    }
}
