<?php

$mysqli = new mysqli(env_var('DB_HOST'), env_var('DB_USERNAME'), env_var('DB_PASSWORD'), env_var('DB_DATABASE'));
$mysqli->set_charset('utf8');
