<?php


header('Content-Type: text/plain; charset=utf-8');

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
	http_response_code(500);
	echo "ERROR: Composer autoload not found. Expected at $autoload\n";
	exit(1);
}

require $autoload;

echo "OK: autoload included.\n";
if (class_exists(\Composer\Autoload\ClassLoader::class)) {
	echo "Composer ClassLoader available: yes\n";
} else {
	echo "Composer ClassLoader available: no\n";
}

echo "Timestamp: " . date('c') . "\n";

