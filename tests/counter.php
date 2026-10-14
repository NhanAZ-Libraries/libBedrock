<?php

declare(strict_types=1);

use NhanAZ\libBedrock\Counter;

$autoload = getenv('AXOLOTL_AUTOLOAD');
if ($autoload !== false && is_file($autoload)) {
	require_once $autoload;
}
require_once dirname(__DIR__) . '/src/NhanAZ/libBedrock/Counter.php';

$assert = static function (bool $condition, string $message) : void {
	if (!$condition) {
		throw new RuntimeException($message);
	}
};

$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'libbedrock-counter-' . bin2hex(random_bytes(8));
if (!mkdir($root) || !mkdir($root . '/nested')) {
	throw new RuntimeException('Could not create the isolated counter fixture directory.');
}

try {
	file_put_contents($root . '/one.txt', "one two\n");
	file_put_contents($root . '/nested/two.txt', 'three');
	file_put_contents($root . '/nested/skip.php', '<?php');

	$result = Counter::getFolderInfoFromDirectory($root, 'txt');
	$assert($result === [
		'totalFiles' => 2,
		'totalLines' => 2,
		'totalWords' => 3,
		'totalChars' => 13,
	], 'Counter did not report the expected recursive file statistics.');

	try {
		Counter::getFolderInfoFromDirectory($root . '/missing', 'txt');
		throw new RuntimeException('A missing directory should be rejected.');
	} catch (InvalidArgumentException) {
	}
} finally {
	foreach ([$root . '/nested/two.txt', $root . '/nested/skip.php', $root . '/one.txt'] as $path) {
		if (is_file($path)) {
			unlink($path);
		}
	}
	if (is_dir($root . '/nested')) {
		rmdir($root . '/nested');
	}
	if (is_dir($root)) {
		rmdir($root);
	}
}

echo "PASS: Counter recursively reports matching files and rejects an unavailable directory\n";
