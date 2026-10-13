<?php

declare(strict_types=1);

namespace NhanAZ\libBedrock;

use NhanAZ\libBedrock\exception\libBedrockUnexpectedValueException;
use pocketmine\network\mcpe\JwtUtils;
use function is_bool;
use function is_int;
use function is_string;

class Jwt {

	/** @param array{mixed[], mixed[], string} $clientData */
	public function __construct(private array $clientData) {
	}

	public static function parse(string $clientDataJwt) : self {
		$clienData = JwtUtils::parse($clientDataJwt);
		return new self($clienData);
	}

	public function getalg() : string {
		$alg = $this->clientData[0]["alg"];
		if (!is_string($alg)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $alg;
	}

	public function getx5u() : string {
		$x5u = $this->clientData[0]["x5u"];
		if (!is_string($x5u)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $x5u;
	}

	// TODO: AnimatedImageData

	public function getArmSize() : string {
		$ArmSize = $this->clientData[1]["ArmSize"];
		if (!is_string($ArmSize)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $ArmSize;
	}

	public function getCapeData() : string {
		$CapeData = $this->clientData[1]["CapeData"];
		if (!is_string($CapeData)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $CapeData;
	}

	public function getCapeId() : string {
		$CapeId = $this->clientData[1]["CapeId"];
		if (!is_string($CapeId)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $CapeId;
	}

	public function getCapeImageHeight() : int {
		$CapeImageHeight = $this->clientData[1]["CapeImageHeight"];
		if (!is_int($CapeImageHeight)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $CapeImageHeight;
	}

	public function getCapeImageWidth() : int {
		$CapeImageWidth = $this->clientData[1]["CapeImageWidth"];
		if (!is_int($CapeImageWidth)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $CapeImageWidth;
	}

	public function isCapeOnClassicSkin() : bool {
		$CapeOnClassicSkin = $this->clientData[1]["CapeOnClassicSkin"];
		if (!is_bool($CapeOnClassicSkin)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $CapeOnClassicSkin;
	}

	public function getClientRandomId() : int {
		$ClientRandomId = $this->clientData[1]["ClientRandomId"];
		if (!is_int($ClientRandomId)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $ClientRandomId;
	}

	public function getCurrentInputMode() : int {
		$CurrentInputMode = $this->clientData[1]["CurrentInputMode"];
		if (!is_int($CurrentInputMode)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $CurrentInputMode;
	}

	public function getDefaultInputMode() : int {
		$DefaultInputMode = $this->clientData[1]["DefaultInputMode"];
		if (!is_int($DefaultInputMode)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $DefaultInputMode;
	}

	public function getDeviceId() : string {
		$DeviceId = $this->clientData[1]["DeviceId"];
		if (!is_string($DeviceId)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $DeviceId;
	}

	public function getDeviceModel() : string {
		$DeviceModel = $this->clientData[1]["DeviceModel"];
		if (!is_string($DeviceModel)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $DeviceModel;
	}

	public function getDeviceOS() : int {
		$DeviceOS = $this->clientData[1]["DeviceOS"];
		if (!is_int($DeviceOS)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $DeviceOS;
	}

	public function getGameVersion() : string {
		$GameVersion = $this->clientData[1]["GameVersion"];
		if (!is_string($GameVersion)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $GameVersion;
	}

	public function getGuiScale() : int {
		$GuiScale = $this->clientData[1]["GuiScale"];
		if (!is_int($GuiScale)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $GuiScale;
	}

	public function isEditorMode() : bool {
		$IsEditorMode = $this->clientData[1]["IsEditorMode"];
		if (!is_bool($IsEditorMode)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $IsEditorMode;
	}

	public function getLanguageCode() : string {
		$LanguageCode = $this->clientData[1]["LanguageCode"];
		if (!is_string($LanguageCode)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $LanguageCode;
	}

	public function isOverrideSkin() : bool {
		$OverrideSkin = $this->clientData[1]["OverrideSkin"];
		if (!is_bool($OverrideSkin)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $OverrideSkin;
	}

	// TODO: PersonaPieces

	public function isPersonaSkin() : bool {
		$PersonaSkin = $this->clientData[1]["PersonaSkin"];
		if (!is_bool($PersonaSkin)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $PersonaSkin;
	}

	// TODO: PieceTintColors

	public function getPlatformOfflineId() : string {
		$PlatformOfflineId = $this->clientData[1]["PlatformOfflineId"];
		if (!is_string($PlatformOfflineId)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $PlatformOfflineId;
	}

	public function getPlatformOnlineId() : string {
		$PlatformOnlineId = $this->clientData[1]["PlatformOnlineId"];
		if (!is_string($PlatformOnlineId)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $PlatformOnlineId;
	}

	public function getPlayFabId() : string {
		$PlayFabId = $this->clientData[1]["PlayFabId"];
		if (!is_string($PlayFabId)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $PlayFabId;
	}

	public function isPremiumSkin() : bool {
		$PremiumSkin = $this->clientData[1]["PremiumSkin"];
		if (!is_bool($PremiumSkin)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $PremiumSkin;
	}

	public function getSelfSignedId() : string {
		$SelfSignedId = $this->clientData[1]["SelfSignedId"];
		if (!is_string($SelfSignedId)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $SelfSignedId;
	}

	public function getServerAddress() : string {
		$ServerAddress = $this->clientData[1]["ServerAddress"];
		if (!is_string($ServerAddress)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $ServerAddress;
	}

	public function getSkinAnimationData() : string {
		$SkinAnimationData = $this->clientData[1]["SkinAnimationData"];
		if (!is_string($SkinAnimationData)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $SkinAnimationData;
	}

	public function getSkinColor() : string {
		$SkinColor = $this->clientData[1]["SkinColor"];
		if (!is_string($SkinColor)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $SkinColor;
	}

	public function getSkinData() : string {
		$SkinData = $this->clientData[1]["SkinData"];
		if (!is_string($SkinData)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $SkinData;
	}

	public function getSkinGeometryData() : string {
		$SkinGeometryData = $this->clientData[1]["SkinGeometryData"];
		if (!is_string($SkinGeometryData)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $SkinGeometryData;
	}

	public function getSkinGeometryDataEngineVersion() : string {
		$SkinGeometryDataEngineVersion = $this->clientData[1]["SkinGeometryDataEngineVersion"];
		if (!is_string($SkinGeometryDataEngineVersion)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $SkinGeometryDataEngineVersion;
	}

	public function getSkinId() : string {
		$SkinId = $this->clientData[1]["SkinId"];
		if (!is_string($SkinId)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $SkinId;
	}

	public function getSkinImageHeight() : string {
		$SkinImageHeight = $this->clientData[1]["SkinImageHeight"];
		if (!is_int($SkinImageHeight)) {
			throw new libBedrockUnexpectedValueException();
		}
		return (string) $SkinImageHeight;
	}

	public function getSkinImageWidth() : string {
		$SkinImageWidth = $this->clientData[1]["SkinImageWidth"];
		if (!is_int($SkinImageWidth)) {
			throw new libBedrockUnexpectedValueException();
		}
		return (string) $SkinImageWidth;
	}

	public function getSkinResourcePatch() : string {
		$SkinResourcePatch = $this->clientData[1]["SkinResourcePatch"];
		if (!is_string($SkinResourcePatch)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $SkinResourcePatch;
	}

	public function getThirdPartyName() : string {
		$ThirdPartyName = $this->clientData[1]["ThirdPartyName"];
		if (!is_string($ThirdPartyName)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $ThirdPartyName;
	}

	public function isThirdPartyNameOnly() : bool {
		$ThirdPartyNameOnly = $this->clientData[1]["ThirdPartyNameOnly"];
		if (!is_bool($ThirdPartyNameOnly)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $ThirdPartyNameOnly;
	}

	public function isTrustedSkin() : bool {
		$TrustedSkin = $this->clientData[1]["TrustedSkin"];
		if (!is_bool($TrustedSkin)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $TrustedSkin;
	}

	public function getUIProfile() : int {
		$UIProfile = $this->clientData[1]["UIProfile"];
		if (!is_int($UIProfile)) {
			throw new libBedrockUnexpectedValueException();
		}
		return $UIProfile;
	}
}
