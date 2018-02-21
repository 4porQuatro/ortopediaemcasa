<?php


    NAMESPACE kcfinder;


?>


<script src="js/index.php" type="text/javascript"></script>


<script src="js_localize.php?lng=<?= $this->lang ?>" type="text/javascript"></script>


<?php


    IF ($this->opener['name'] == "tinymce"):


?>


<script src="<?= $this->config['_tinyMCEPath'] ?>/tiny_mce_popup.js" type="text/javascript"></script>


<?php


    ENDIF;





    IF (file_exists("themes/{$this->config['theme']}/js.php")):


?>


<script src="themes/<?= $this->config['theme'] ?>/js.php" type="text/javascript"></script>


<?php


    ENDIF;


?>


<script type="text/javascript">


_.version = "<?= self::VERSION ?>";


_.support.zip = <?= (class_exists('ZipArchive') && !$this->config['denyZipDownload']) ? "true" : "false" ?>;


_.support.check4Update = <?= ((!isset($this->config['denyUpdateCheck']) || !$this->config['denyUpdateCheck']) && (ini_get("allow_url_fopen") || function_exists("http_get") || function_exists("curl_init") || function_exists('socket_create'))) ? "true" : "false" ?>;


_.lang = "<?= text::jsValue($this->lang) ?>";


_.type = "<?= text::jsValue($this->type) ?>";


_.theme = "<?= text::jsValue($this->config['theme']) ?>";


_.access = <?= json_encode($this->config['access']) ?>;


_.dir = "<?= text::jsValue($this->session['dir']) ?>";


_.uploadURL = "<?= text::jsValue($this->config['uploadURL']) ?>";


_.thumbsURL = _.uploadURL + "/<?= text::jsValue($this->config['thumbsDir']) ?>";


_.opener = <?= json_encode($this->opener) ?>;


_.cms = "<?= text::jsValue($this->cms) ?>";


$.$.kuki.domain = "<?= text::jsValue($this->config['cookieDomain']) ?>";


$.$.kuki.path = "<?= text::jsValue($this->config['cookiePath']) ?>";


$.$.kuki.prefix = "<?= text::jsValue($this->config['cookiePrefix']) ?>";


$(function() { _.resize(); _.init(); });


$(window).resize(_.resize);


</script>


