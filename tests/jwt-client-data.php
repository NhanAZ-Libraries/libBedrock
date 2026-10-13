<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use NhanAZ\libBedrock\Jwt;
use NhanAZ\libBedrock\exception\libBedrockUnexpectedValueException;

function check(bool $condition, string $message) : void {
	if (!$condition) {
		throw new RuntimeException($message);
	}
}

$jwt = new Jwt([[], ['SkinImageHeight' => 128, 'SkinImageWidth' => 64], '']);
check($jwt->getSkinImageHeight() === '128', 'Skin image height did not retain its string return contract');
check($jwt->getSkinImageWidth() === '64', 'Skin image width did not retain its string return contract');

foreach ([
	['SkinImageHeight', static fn(Jwt $jwt) : string => $jwt->getSkinImageHeight()],
	['SkinImageWidth', static fn(Jwt $jwt) : string => $jwt->getSkinImageWidth()]
] as [$field, $getter]) {
	$invalidJwt = new Jwt([[], [$field => '128'], '']);
	try {
		$getter($invalidJwt);
		throw new RuntimeException($field . ' accepted a non-integer client-data value');
	} catch (libBedrockUnexpectedValueException) {
	}
}
