<?php
header('Content-Type: text/plain');
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "dirname(SCRIPT_NAME): " . dirname($_SERVER['SCRIPT_NAME']) . "\n";
echo "BASE_URL hasil hitung: " . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . "\n";