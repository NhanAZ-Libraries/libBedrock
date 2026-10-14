# libBedrock

![libBedrock icon](assets/libBedrock_Icon_GIF.gif)

libBedrock is a utility library and virion for Axolotl-PM plugin development. Its source contains item and block parsers, vector and sound helpers, resource pack management, and a file counter.

[CI results](https://github.com/NhanAZ-Libraries/libBedrock/actions/workflows/ci.yml) | [Published releases](https://github.com/NhanAZ-Libraries/libBedrock/releases) | [Source repository](https://github.com/NhanAZ-Libraries/libBedrock)

## Compatibility and status

The Composer dependency is constrained to Axolotl-PM `^5.47.0`, and both manifests declare API minimum `5.47.0`. This is the package's declared loadability floor, not evidence that runtime behavior has been verified throughout the allowed range. Check the linked [CI results](https://github.com/NhanAZ-Libraries/libBedrock/actions/workflows/ci.yml) for the exact locked dependencies exercised by each run. Stable release [v2.0.0](https://github.com/NhanAZ-Libraries/libBedrock/releases/tag/v2.0.0) removes VAPM and changes `Counter` to a synchronous API. Version [v1.1.9](https://github.com/NhanAZ-Libraries/libBedrock/releases/tag/v1.1.9) remains available for consumers that need the 1.x API.

Version 2 removes the VAPM dependency. `Counter::getFolderInfo()` now returns its result synchronously; callers migrating from 1.x must stop awaiting an `Async` value. The plugin argument remains in that method for call-site compatibility. `Counter::getFolderInfoFromDirectory()` is the plugin-independent entry point. Both methods scan on the server thread, so use them only for directories small enough not to delay a tick. This is a major API change; migrate callers before upgrading from 1.x.

`ResourcePackManager::registerResourcePack()` accepts an empty encryption key or a key of exactly 32 bytes. An invalid key is rejected before the helper creates a pack or changes the server resource stack. It reads packaged resources directly when creating the archive and leaves an existing `<plugin> Pack` directory in plugin data untouched. If `<plugin>.mcpack` already exists in plugin data, registration fails without overwriting it. A second registration is rejected until the pack is unregistered. Only the plugin that registered a pack may unregister it; a call from another plugin is rejected before changing the resource stack or its own files. An isolated probe on the tested Axolotl-PM runtime verified registration, preservation of existing data and removal of a generated pack through the public resource pack manager API. This probe used only the helper and its exception class, not a PHAR containing the full library dependency graph.

`ConfigChecker::checkConfigVersion()` leaves configurations at the requested version or a newer version untouched. For an older version, it refuses to replace an existing backup and saves a default configuration only after the original file has been backed up. A source fixture checks these outcomes with disposable files; it does not establish full-library PHAR or runtime compatibility.

## Using the library

Integrations should pin a source revision or release and test it with the exact Axolotl-PM runtime and dependency graph they deploy. The `v2.0.0` release includes the PHAR built from the tagged commit and checked by the linked CI workflow. The PHAR also loaded and enabled on Axolotl-PM 5.49.1 in an isolated runtime; that single patch does not establish compatibility across the full Composer range.

For example, `BedrockMath::center()` returns a new vector offset by half a block on each axis:

```php
use NhanAZ\libBedrock\BedrockMath;
use pocketmine\math\Vector3;

$center = BedrockMath::center(new Vector3(0, 64, 0));
// (0.5, 64.5, 0.5)
```

See the [source classes](src/NhanAZ/libBedrock) for the public methods and their parameters. This example does not establish that every helper is supported by every Axolotl-PM 5.x version.

## Building and verification

[DevTools](https://github.com/NhanAZ/DevTools) can build and validate plugin PHARs when their dependencies meet its packaging contract. The [`devtools.yml`](devtools.yml) file selects the locked Composer graph instead of importing the older `.poggit.yml` library declarations into the same build. The version 2 build resolves its locked Composer virions into a separate prepared directory and validates their licenses and provenance in the PHAR. This packaging result does not replace the exact source analysis or the runtime evidence required by a consumer plugin.

The [CI workflow](.github/workflows/ci.yml) runs source checks, including the `Counter` directory fixture and PHPStan at maximum level, then builds the PHAR and validates its contents, dependency notices, and checksum. Review the linked workflow run for its exact source revision and tests before reusing a result. The isolated runtime check verifies plugin startup; consumer behavior still needs testing in the consuming plugin.

## License and credits

The libBedrock source is distributed under the existing [MIT license](LICENSE). Dependencies and bundled assets retain their own terms. The source includes a wrapper for [Hydrogen](https://github.com/thebigcrafter/Hydrogen). It no longer depends on [VAPM](https://github.com/VennDev/LibVapmPMMP). No change to dependency licenses is implied here.

Contributions should follow the [contributing guide](CONTRIBUTING.md) and [code of conduct](CODE_OF_CONDUCT.md). For support, use [NhanAZ Discord](https://discord.gg/j2X83ujT6c).
