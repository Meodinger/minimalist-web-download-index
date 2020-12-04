<?php

// Base URL of the website, without trailing slash.
$base_url = getenv('MWN_BASE_URL') ?: '';

// Current file path
$dir = dirname(__FILE__);

// Make the right PATH
$uri = $_SERVER["REQUEST_URI"];

// Avoid wrong subDir
if (strlen($uri) < 2) {
    $uri = '/?';
}

// Avoid '..' dir
if (stristr($uri, '/..')) {
    $uri = '/?';
}

// get subDir
$subDir = substr($uri, 2);
$target = $dir.$subDir;

/**
 * @param $target string
 * @return array[]
 */
function get(string $target) {

    $dirs = array();
    $files = array();

    if (is_dir($target)) {
         $values = scandir($target);

         foreach ($values as $value) {
            if ($value != '.' && $value != '..') {
                if (is_dir($target.'/'.$value)) {
                    $dirs[] = $value;
                } else if (is_file($target.'/'.$value)) {
                    $files[] = $value;
                }
            }
        }
    }

    return array("dirs" => $dirs, "files" => $files);
}

$root = get($target);

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php print $base_url; ?>/favicon.ico">
    <link rel="stylesheet" href="<?php print $base_url; ?>/styles.css">
    <title>CLOUD DISK SIMPLE</title>
</head>
<body>
    <h1>DIRS</h1>
    <ul id="dirs"><?php
        foreach ($root["dirs"] as $dir) {
            $url = $base_url.$uri.'/'.$dir;
            echo '<li><a href="'.$url.'">'.$dir.'</a></li>';
        }
    ?></ul>

    <h1>FILES</h1>
    <ul id="files"><?php
        foreach ($root["files"] as $file) {
            $url = $base_url.($subDir ? $subDir.'/' : '').$file;
            echo '<li><a href="'.$url.'">'.$file.'</a></li>';
        }
    ?></ul>
</body>
</html>
