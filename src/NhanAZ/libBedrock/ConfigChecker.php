<?php

declare(strict_types=1);

namespace NhanAZ\libBedrock;

use NhanAZ\libBedrock\exception\libBedrockUnexpectedValueException;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;
use RuntimeException;
use function basename;
use function dirname;
use function file_exists;
use function is_file;
use function is_link;
use function is_string;
use function pathinfo;
use function rename;
use function trim;
use function version_compare;
use const DIRECTORY_SEPARATOR;
use const PATHINFO_EXTENSION;
use const PATHINFO_FILENAME;

class ConfigChecker {

	/**
	 * @return array<string>
	 */
	private static function getConfigData(Config $config) : array {
		$configPath = $config->getPath();
		$originalConfigName = basename($configPath);
		$configExtension = pathinfo($configPath, PATHINFO_EXTENSION);
		$oldConfigName = pathinfo($configPath, PATHINFO_FILENAME) . "_old";
		if ($configExtension !== "") {
			$oldConfigName .= "." . $configExtension;
		}

		return [
			"configPath" => dirname($configPath) . DIRECTORY_SEPARATOR,
			"configName" => $originalConfigName,
			"oldConfigName" => $oldConfigName
		];
	}

	public static function checkConfigVersion(PluginBase $plugin, Config $config, string $configKey, string $latestVersion, string $updateMessage = "") : bool {
		$currentVersion = $config->get($configKey);
		if (!is_string($currentVersion)) {
			throw new libBedrockUnexpectedValueException('Parameter #3 $configKey of method NhanAZ\libBedrock\ConfigChecker::checkConfigVersion() expects string, mixed given.');
		}
		if (!version_compare($currentVersion, $latestVersion, "<")) {
			return false;
		}
		$configData = self::getConfigData($config);
		$configPath = $configData["configPath"];
		$originalConfig = $configData["configName"];
		$oldConfig = $configData["oldConfigName"];
		if (trim($updateMessage) === "") {
			$updateMessage = "Your $originalConfig file is outdated. Your old $originalConfig has been saved as $oldConfig and a new $originalConfig file has been generated. Please update accordingly.";
		}
		$originalPath = $configPath . $originalConfig;
		$backupPath = $configPath . $oldConfig;
		if (!is_file($originalPath) || is_link($originalPath)) {
			throw new RuntimeException("Cannot back up missing or linked config file: {$originalPath}");
		}
		if (file_exists($backupPath) || is_link($backupPath)) {
			throw new RuntimeException("Refusing to overwrite existing config backup: {$backupPath}");
		}
		if (!rename($originalPath, $backupPath)) {
			throw new RuntimeException("Cannot back up config file: {$originalPath}");
		}
		if (!$plugin->saveDefaultConfig()) {
			throw new RuntimeException("Cannot save default config after backing up {$originalPath}");
		}
		$plugin->getConfig()->reload();
		$task = new ClosureTask(function () use ($plugin, $updateMessage) : void {
			$plugin->getLogger()->critical($updateMessage);
		});
		$plugin->getScheduler()->scheduleDelayedTask($task, 3 * 20);
		return true;
	}
}
