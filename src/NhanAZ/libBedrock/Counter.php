<?php

declare(strict_types=1);

namespace NhanAZ\libBedrock;

use pocketmine\plugin\PluginBase;
use SplFileInfo;
use vennv\vapm\Async;
use vennv\vapm\Error;
use vennv\vapm\FiberManager;
use vennv\vapm\Stream;
use vennv\vapm\VapmPMMP;
use function count;
use function file;
use function is_array;
use function is_string;
use function str_word_count;
use function strlen;

class Counter {

	/**
	 * Get folder information for all files in a directory with a given extension.
	 *
	 * @param PluginBase $plugin    The plugin to get the folder info for.
	 * @param string     $dir       The directory to scan.
	 * @param string     $extension The file extension to filter by.
	 */
	public static function getFolderInfo(PluginBase $plugin, string $dir, string $extension) : Async {
		VapmPMMP::init($plugin);
		return new Async(
			/**
			 * @return array{"totalFiles": int, "totalLines": int, "totalWords": int, "totalChars": int} An array of file information. */
			function () use ($dir, $extension) : array {
				$files = new \RecursiveIteratorIterator(
					iterator: new \RecursiveDirectoryIterator($dir),
					mode: \RecursiveIteratorIterator::CHILD_FIRST
				);
				$txtFiles = [];
				foreach ($files as $file) {
					if (!($file instanceof SplFileInfo) || !$file->isFile() || $file->getExtension() != $extension) {
						continue;
					}
					$path = $file->getRealPath();
					if ($path === false) {
						continue;
					}
					$txtFiles[] = $path;
					FiberManager::wait();
				}
				$totalLines = 0;
				$totalWords = 0;
				$totalChars = 0;
				foreach ($txtFiles as $txtFile) {
					$value = file($txtFile);
					if (is_array($value)) {
						$totalLines += count($value);
					}
					$content = Async::await(Stream::read($txtFile));
					if ($content !== Error::UNABLE_TO_OPEN_FILE && is_string($content)) {
						$totalWords += str_word_count($content);
						$totalChars += strlen($content);
					}
				}
				return [
					"totalFiles" => count($txtFiles),
					"totalLines" => $totalLines,
					"totalWords" => $totalWords,
					"totalChars" => $totalChars
				];
			}
		);
	}
}
