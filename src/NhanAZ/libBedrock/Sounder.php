<?php

declare(strict_types=1);

namespace NhanAZ\libBedrock;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class Sounder {

	/**
	 * Plays a sound for the specified player.
	 *
	 * @param Player     $player    The player to play the sound for.
	 * @param string     $soundName The name of the sound to play.
	 * @param float|null $x         The x coordinate of the sound. If null, the player's x coordinate will be used.
	 * @param float|null $y         The y coordinate of the sound. If null, the player's y coordinate will be used.
	 * @param float|null $z         The z coordinate of the sound. If null, the player's z coordinate will be used.
	 * @param float      $volume    The volume of the sound.
	 * @param float      $pitch     The pitch of the sound.
	 */
	public static function play(Player $player, string $soundName, ?float $x = null, ?float $y = null, ?float $z = null, float $volume = 1.0, float $pitch = 1.0) : void {
		$playerPos = $player->getPosition();
		$packet = new PlaySoundPacket();
		$packet->soundName = $soundName;
		$packet->x = $x ?? $playerPos->getX();
		$packet->y = $y ?? $playerPos->getY();
		$packet->z = $z ?? $playerPos->getZ();
		$packet->volume = $volume;
		$packet->pitch = $pitch;
		$player->getNetworkSession()->sendDataPacket($packet);
	}
}
