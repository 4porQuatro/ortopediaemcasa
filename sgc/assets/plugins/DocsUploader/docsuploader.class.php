<?php

class DocsUploader
{
    /**
     * Get upload folder name.
     * @return string
     */
    public static function getFolder()
    {
        return dirname($_SERVER['DOCUMENT_ROOT']) . "/public/uploads/files/";
    }

    /**
     * Get main domain URL.
     * @return string
     */
    function getURL()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domain_pieces = explode('.', $_SERVER['HTTP_HOST']);
        if($domain_pieces[0] == "sgc"){
            unset($domain_pieces[0]);
        }

        return $protocol . implode('.', $domain_pieces);
    }

    /*
     *	Set folder
     */
    public static function setFolder()
    {
        $folder = self::getFolder();

        if (isset($_POST['subfolder'])) {
            $folder .= $_POST['subfolder'] . "/";
        }

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        echo file_exists($folder);
    }

    /*
     *	Upload file
     */
    public static function uploadFiles()
    {
        require_once('fileutilities.class.php');

        $return_arr = array('errors' => array(), 'files' => array());
        $allowed_formats = array();
        $max_size = 0;
        $file_input_name = "";
        $folder = self::getFolder();


        if (isset($_POST['value']) && !empty($_POST['value'])) {
            $return_arr['files'] = json_decode($_POST['value']);
        }
        if (isset($_POST['allowed_formats'])) {
            $allowed_formats = explode(',', $_POST['allowed_formats']);
        }
        if (isset($_POST['max_size'])) {
            $max_size = $_POST['max_size'];
        }
        if (isset($_POST['subfolder'])) {
            $folder .= $_POST['subfolder'] . "/";
        }

        $files = array();
        foreach ($_FILES['files'] as $attr => $values) {
            foreach ($values as $key => $val) {
                $files[$key][$attr] = $val;
            }
        }

        foreach ($files as $key => $file) {
            $errors = "";

            if (!empty($file['name'])) {
                $doc_size = $file['size'];
                $filename = $file['name'];
                $extension = explode(".", $filename);
                $doc_extension = strtolower($extension[sizeof($extension) - 1]);

                if ($doc_size < 0 || $doc_size > $max_size)
                    $errors .= "O tamanho do ficheiro \"" . $filename . "\" não é válido.\n";
                if (!in_array($doc_extension, $allowed_formats))
                    $errors .= "O formato do ficheiro \"" . $filename . "\" não é válido.\n";
            }

            if (empty($errors)) {
                $doc_source = FileUtilities::generateFilename($folder, $file['name']);

                move_uploaded_file($file['tmp_name'], $folder . $doc_source);
                if (file_exists($folder . $doc_source)) {
                    array_push($return_arr['files'], array('filename' => $filename, 'source' => $doc_source, 'title' => NULL));
                }
            } else {
                $return_arr['errors'][$key] = $errors;
            }
        }

        echo json_encode($return_arr);
    }

    /*
     *	Delete file
     */
    public static function deleteFile()
    {
        $folder = self::getFolder();
        $file_arr = array();

        if (isset($_POST['value']) && !empty($_POST['value'])) {
            $files_arr = json_decode($_POST['value']);
        }
        if (isset($_POST['array_index'])) $array_index = $_POST['array_index'];
        if (isset($_POST['subfolder'])) {
            $folder .= $_POST['subfolder'] . "/";
        }

        // delete file from server
        if (file_exists($folder . $files_arr[$array_index]->source)) {
            unlink($folder . $files_arr[$array_index]->source);
        }
        // delete position in array
        unset($files_arr[$array_index]);
        $files_arr = array_values($files_arr);

        // return
        echo json_encode($files_arr);
    }

    /*
     *	Update order
     */
    public static function updateOrder()
    {
        // manage files
        $files_arr = $tmp_arr = array();
        $order_arr = array();

        if (isset($_POST['value']) && !empty($_POST['value'])) {
            $files_arr = json_decode($_POST['value']);
        }
        if (isset($_POST['order_arr'])) $order_arr = $_POST['order_arr'];

        foreach ($files_arr as $key => $val) {
            $tmp_arr[$key] = $files_arr[$order_arr[$key]];
        }
        $files_arr = $tmp_arr;

        // return
        echo json_encode($files_arr);
    }

    /*
     *	Update title
     */
    public static function updateTitle()
    {
        // manage files
        $files_arr = array();
        $arr_index = NULL;
        $title = NULL;

        if (isset($_POST['value']) && !empty($_POST['value'])) {
            $files_arr = json_decode($_POST['value']);
        }
        if (isset($_POST['array_index'])) $array_index = $_POST['array_index'];
        if (isset($_POST['title'])) $title = $_POST['title'];

        $files_arr[$array_index]->title = $title;

        // return
        echo json_encode($files_arr);
    }
}
