<?php
if (php_sapi_name() == "cli") {
    // In cli-mode
    define('LINE_BREAK', "\n");
} else {
    // Not in cli-mode
    define('LINE_BREAK', "<br />");
}

require 'Vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$cryptTime = new KS\CryptTime();

$plainText = 'Kittinan';
$encryptText = $cryptTime->encrypt($plainText);

echo 'Encrypt Text : ' . $encryptText . LINE_BREAK;