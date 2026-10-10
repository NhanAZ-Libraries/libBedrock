# libBedrock

![libBedrock icon](assets/libBedrock_Icon_GIF.gif)

libBedrock is a utility library and virion for Axolotl-PM plugin development. Its source contains item and block parsers, vector and sound helpers, resource pack management, and a file counter.

[CI results](https://github.com/NhanAZ-Libraries/libBedrock/actions/workflows/ci.yml) | [Published releases](https://github.com/NhanAZ-Libraries/libBedrock/releases) | [Source repository](https://github.com/NhanAZ-Libraries/libBedrock)

## Compatibility and status

The [`virion.yml`](virion.yml) manifest declares plugin API `5.0.0`. That declaration does not establish compatibility with every Axolotl-PM 5.x release. The [v1.1.9 release](https://github.com/NhanAZ-Libraries/libBedrock/releases/tag/v1.1.9) is historical. A current combined PHAR and its runtime behavior have not been verified for this library.

`Counter` depends on VAPM, while other helpers use Axolotl-PM and Hydrogen APIs. Check the dependency and license terms of every component before distributing a bundle. Static analysis of source code does not verify that those dependencies can be packaged or loaded together.

## Using the library

Existing integrations should pin a source revision or release and test it with the exact Axolotl-PM runtime and dependency graph they deploy. The repository does not currently provide a verified build of the latest source for general installation.

For example, `BedrockMath::center()` returns a new vector offset by half a block on each axis:

```php
use NhanAZ\libBedrock\BedrockMath;
use pocketmine\math\Vector3;

$center = BedrockMath::center(new Vector3(0, 64, 0));
// (0.5, 64.5, 0.5)
```

See the [source classes](src/NhanAZ/libBedrock) for the public methods and their parameters. This example does not establish that every helper is supported by every Axolotl-PM 5.x version.

## Building and verification

[DevTools](https://github.com/NhanAZ/DevTools) can build and validate plugin PHARs when their dependencies meet its packaging contract. A DevTools build of libBedrock with its current dependency graph has not passed that contract. Do not treat a passing source CI run or an older release as proof of a verified current PHAR.

The [CI workflow](.github/workflows/ci.yml) runs source checks. Review the linked workflow run for its exact source revision and tests before reusing a result. Package and runtime checks remain necessary for a consumer plugin.

## License and credits

The libBedrock source is distributed under the existing [MIT license](LICENSE). Dependencies and bundled assets retain their own terms. The source includes a wrapper for [Hydrogen](https://github.com/thebigcrafter/Hydrogen) and uses [VAPM](https://github.com/VennDev/LibVapmPMMP) in `Counter`. No change to those projects' licenses is implied here.

Contributions should follow the [contributing guide](CONTRIBUTING.md) and [code of conduct](CODE_OF_CONDUCT.md). For support, use [NhanAZ Discord](https://discord.gg/j2X83ujT6c).
