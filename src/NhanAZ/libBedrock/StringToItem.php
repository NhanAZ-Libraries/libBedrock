<?php

declare(strict_types=1);

namespace NhanAZ\libBedrock;

use NhanAZ\libBedrock\exception\libBedrockException;
use pocketmine\item\Item;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;

class StringToItem {

	/**
	 * Converts a string to an item.
	 *
	 * @param string $string The string to convert.
	 *
	 * @return Item The item represented by the string.
	 *
	 * @throws libBedrockException if the string does not represent a valid item.
	 */
	public static function parse(string $string) : Item {
		try {
			$item = StringToItemParser::getInstance()->parse($string) ?? LegacyStringToItemParser::getInstance()->parse($string);
		} catch (LegacyStringToItemParserException $e) {
			throw new libBedrockException($e->getMessage());
		}
		return $item;
	}
}
