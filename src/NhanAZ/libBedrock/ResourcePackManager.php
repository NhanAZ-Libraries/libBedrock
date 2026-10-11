<?php

declare(strict_types=1);

namespace NhanAZ\libBedrock;

use NhanAZ\libBedrock\exception\libBedrockUnexpectedValueException;
use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ResourcePack;
use pocketmine\resourcepacks\ZippedResourcePack;
use Symfony\Component\Filesystem\Path;
use function array_search;
use function array_splice;
use function file_get_contents;
use function is_null;
use function str_starts_with;
use function strlen;
use function substr;
use function unlink;

class ResourcePackManager {

	private static ?ResourcePack $pack = null;
	private static ?PluginBase $owner = null;

	public static function registerResourcePack(PluginBase $plugin, string $encryptKey = "") : void {
		if (self::$pack !== null) {
			throw new libBedrockUnexpectedValueException('A resource pack is already registered');
		}
		if ($encryptKey !== "" && strlen($encryptKey) !== 32) {
			throw new libBedrockUnexpectedValueException('Resource pack encryption key must be exactly 32 bytes');
		}
		$plugin->getLogger()->debug('Compiling resource pack');
		$zip = new \ZipArchive();
		if ($zip->open(Path::join($plugin->getDataFolder(), $plugin->getName() . '.mcpack'), \ZipArchive::CREATE | \ZipArchive::EXCL) !== true) {
			throw new libBedrockUnexpectedValueException('Could not create resource pack archive');
		}
		$prefix = $plugin->getName() . ' Pack/';
		$entries = 0;
		try {
			foreach ($plugin->getResources() as $resourcePath => $resource) {
				if (!$resource->isFile() || !str_starts_with($resourcePath, $prefix)) {
					continue;
				}
				$relativePath = substr($resourcePath, strlen($prefix));
				if ($relativePath === '' || Path::normalize($relativePath) !== $relativePath) {
					throw new libBedrockUnexpectedValueException('Invalid resource pack path');
				}
				$content = file_get_contents($resource->getPathname());
				if ($content === false || !$zip->addFromString($relativePath, $content)) {
					throw new libBedrockUnexpectedValueException('Could not add resource pack entry');
				}
				++$entries;
			}
		} finally {
			$closed = $zip->close();
		}
		if (!$closed || $entries === 0) {
			throw new libBedrockUnexpectedValueException('Resource pack archive is empty or could not be closed');
		}
		$plugin->getLogger()->debug('Resource pack compiled');

		$plugin->getLogger()->debug('Registering resource pack');
		$plugin->getLogger()->debug('Resource pack compiled');
		self::$pack = $pack = new ZippedResourcePack(Path::join($plugin->getDataFolder(), $plugin->getName() . '.mcpack'));
		self::$owner = $plugin;
		$packId = $pack->getPackId();
		$manager = $plugin->getServer()->getResourcePackManager();

		$currentResourcePacks = $manager->getResourceStack();
		$currentResourcePacks[] = $pack;
		$manager->setResourceStack($currentResourcePacks);
		$manager->setResourcePacksRequired(true);

		if ($encryptKey !== "") {
			$manager->setPackEncryptionKey($packId, $encryptKey);
		}

		$plugin->getLogger()->debug('Resource pack registered');
	}

	public static function unRegisterResourcePack(PluginBase $plugin) : void {
		$manager = $plugin->getServer()->getResourcePackManager();
		$pack = self::$pack;
		if (is_null($pack) || self::$owner !== $plugin) {
			throw new libBedrockUnexpectedValueException();
		}
		$currentResourcePacks = $manager->getResourceStack();
		$key = array_search($pack, $currentResourcePacks, true);

		if ($key !== false) {
			array_splice($currentResourcePacks, $key, 1);
			$manager->setResourceStack($currentResourcePacks);
		}
		$manager->setPackEncryptionKey($pack->getPackId(), null);
		$plugin->getLogger()->debug('Resource pack unregistered');

		unlink(Path::join($plugin->getDataFolder(), $plugin->getName() . '.mcpack'));
		self::$pack = null;
		self::$owner = null;
		$plugin->getLogger()->debug('Resource pack file deleted');
	}
}
