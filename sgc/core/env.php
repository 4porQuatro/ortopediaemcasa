<?php

function setEnvArray()
{
    $env_file_path = dirname($_SERVER['DOCUMENT_ROOT']) . '/.env';

    $envs = [];

    $handle = fopen($env_file_path, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $pieces = explode("=", $line);

            if(isset($pieces[0]) && isset($pieces[1]))
            {
                $envs[$pieces[0]] = trim($pieces[1]);
            }
        }

        fclose($handle);
    }

    return $envs;
}

function env_var($key){
    $envs = setEnvArray();

    return (isset($envs[$key])) ? $envs[$key] : $key;
}
