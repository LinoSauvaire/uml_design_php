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

// Sanity check: create a User from the project's domain model and print a short message.
if (!class_exists(\App\Domain\User::class)) {
	$fallback = __DIR__ . '/../src/Domain/User.php';
	if (file_exists($fallback)) {
		require_once $fallback;
		echo "Included fallback file: $fallback\n";
	} else {
		echo "Fallback User file not found: $fallback\n";
	}
}

try {
	$user = new \App\Domain\User('u-1', 'Lino Sauvaire', 'sauvairelino@gmail.com');
	echo "User created: {$user->id()} - {$user->name()} ({$user->email()})\n";
} catch (\Throwable $e) {
	echo "User creation failed: " . $e->getMessage() . "\n";
}

