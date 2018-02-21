<?php

function urli18n($route = '', $path = '')
{
    if(!empty($route))
    {
        $route = '/' . trans('routes.' . $route);
    }

    if(!empty($path))
    {
        $path = '/' . $path;
    }

    return url(config('app.locale_prefix') . $route . $path);
}

/**
 * This function receives an array with the substitutions
 * to be applied in a text.  In the original test, the
 * {{var}} delimiters must be used.
 *
 * @param $string
 * @return mixed
 */
function str_parser(array $vars, $string)
{
    return preg_replace_callback(
        '/{{(.*?)}}/',
        function($m) use ($vars) {
            return isset($vars[$m[1]]) ? $vars[$m[1]] : $m[0]; // If the key is uknown, just use the match value
        },
        $string
    );
}