<?php

declare(strict_types=1);

namespace NhanAZ\libBedrock;

use pocketmine\plugin\PluginBase;
use SplFileInfo;
use function count;
use function file;
use function file_get_contents;
use function is_array;
use function is_dir;
use function str_word_count;
use function strlen;

class Counter {

	/**
	 * Get folder information for all files in a directory with a given extension.
	 *
	 * @param PluginBase $plugin    Retained for source compatibility with the old signature.
	 * @param string     $dir       The directory to scan.
	 * @param string     $extension The file extension to filter by.
	 * @return array{"totalFiles": int, "totalLines": int, "totalWords": int, "totalChars": int}
	 * @since 2.0.0 Results are returned synchronously; VAPM is no longer required.
	 */
	public static function getFolderInfo(PluginBase $plugin, string $dir, string $extension) : array {
		return self::getFolderInfoFromDirectory($dir, $extension);
	}

	/**
	 * Count matching files and their lines, words, and bytes without scheduling background work.
	 *
	 * @return array{"totalFiles": int, "totalLines": int, "totalWords": int, "totalChars": int}
	 * @since 2.0.0
	 */
	public static function getFolderInfoFromDirectory(string $dir, string $extension) : array {
		if (!is_dir($dir)) {
			throw new \InvalidArgumentException("Directory does not exist: $dir");
		}

		$files = new \RecursiveIteratorIterator(
			iterator: new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
			mode: \RecursiveIteratorIterator::LEAVES_ONLY
		);
		$totalFiles = 0;
		$totalLines = 0;
		$totalWords = 0;
		$totalChars = 0;
		foreach ($files as $file) {
			if (!$file instanceof SplFileInfo || !$file->isFile() || $file->getExtension() !== $extension) {
				continue;
			}
			$path = $file->getRealPath();
			if ($path === false) {
				continue;
			}
			$totalFiles++;
			$lines = file($path);
			if (is_array($lines)) {
				$totalLines += count($lines);
			}
			$content = file_get_contents($path);
			if ($content !== false) {
				$totalWords += str_word_count($content);
				$totalChars += strlen($content);
			}
		}

		return [
			"totalFiles" => $totalFiles,
			"totalLines" => $totalLines,
			"totalWords" => $totalWords,
			"totalChars" => $totalChars
		];
	}
}
