<?php

declare(strict_types=1);

namespace pocketmine\utils {
	final class Config {
		public int $reloads = 0;

		/** @param array<string, mixed> $values */
		public function __construct(private string $path, private array $values) {}

		public function getPath() : string { return $this->path; }
		public function get(string $key) : mixed { return $this->values[$key] ?? null; }
		public function exists(string $key) : bool { return array_key_exists($key, $this->values); }
		public function reload() : void { ++$this->reloads; }
	}
}

namespace pocketmine\scheduler {
	final class ClosureTask {
		public function __construct(public \Closure $callback) {}
	}
}

namespace pocketmine\plugin {
	use pocketmine\scheduler\ClosureTask;
	use pocketmine\utils\Config;

	final class TestLogger {
		public function critical(string $message) : void {}
	}

	final class TestScheduler {
		public int $scheduled = 0;

		public function scheduleDelayedTask(ClosureTask $task, int $ticks) : void { ++$this->scheduled; }
	}

	class PluginBase {
		public int $saves = 0;
		private TestLogger $logger;
		private TestScheduler $scheduler;

		public function __construct(private Config $config) {
			$this->logger = new TestLogger();
			$this->scheduler = new TestScheduler();
		}

		public function saveDefaultConfig() : bool {
			++$this->saves;
			return file_put_contents($this->config->getPath(), "default config\n") !== false;
		}

		public function getConfig() : Config { return $this->config; }
		public function getLogger() : TestLogger { return $this->logger; }
		public function getScheduler() : TestScheduler { return $this->scheduler; }
	}
}

namespace {
	require dirname(__DIR__) . '/src/NhanAZ/libBedrock/exception/libBedrockUnexpectedValueException.php';
	require dirname(__DIR__) . '/src/NhanAZ/libBedrock/ConfigChecker.php';

	use NhanAZ\libBedrock\ConfigChecker;
	use NhanAZ\libBedrock\exception\libBedrockUnexpectedValueException;
	use pocketmine\plugin\PluginBase;
	use pocketmine\utils\Config;

	function check(bool $condition, string $message) : void {
		if (!$condition) {
			throw new \RuntimeException($message);
		}
	}

	$directory = sys_get_temp_dir() . '/libbedrock-config-checker-' . bin2hex(random_bytes(6));
	check(mkdir($directory), 'Could not create disposable fixture directory');

	$currentPath = $directory . '/current.yml';
	file_put_contents($currentPath, "current config\n");
	$current = new Config($currentPath, ['version' => '1.2.3']);
	$currentPlugin = new PluginBase($current);
	check(ConfigChecker::checkConfigVersion($currentPlugin, $current, 'version', '1.2.3') === false, 'Current version was treated as outdated');
	check(file_get_contents($currentPath) === "current config\n", 'Current config changed');
	check(!file_exists($directory . '/current_old.yml') && $currentPlugin->saves === 0, 'Current config was backed up');

	$futurePath = $directory . '/future.yml';
	file_put_contents($futurePath, "future config\n");
	$future = new Config($futurePath, ['version' => '2.0.0']);
	$futurePlugin = new PluginBase($future);
	check(ConfigChecker::checkConfigVersion($futurePlugin, $future, 'version', '1.2.3') === false, 'Future version was downgraded');
	check(file_get_contents($futurePath) === "future config\n" && $futurePlugin->saves === 0, 'Future config changed');

	$oldPath = $directory . '/old.yml';
	file_put_contents($oldPath, "old config\n");
	$old = new Config($oldPath, ['version' => '1.0.0']);
	$oldPlugin = new PluginBase($old);
	check(ConfigChecker::checkConfigVersion($oldPlugin, $old, 'version', '1.2.3') === true, 'Older config was not upgraded');
	check(file_get_contents($directory . '/old_old.yml') === "old config\n", 'Older config backup differs');
	check(file_get_contents($oldPath) === "default config\n", 'Default config was not saved');
	check($oldPlugin->saves === 1 && $old->reloads === 1 && $oldPlugin->getScheduler()->scheduled === 1, 'Upgrade lifecycle differs');

	$backupPath = $directory . '/backup.yml';
	file_put_contents($backupPath, "original config\n");
	file_put_contents($directory . '/backup_old.yml', "prior backup\n");
	$backup = new Config($backupPath, ['version' => '1.0.0']);
	$backupPlugin = new PluginBase($backup);
	try {
		ConfigChecker::checkConfigVersion($backupPlugin, $backup, 'version', '1.2.3');
		throw new \RuntimeException('Existing backup was overwritten');
	} catch (\RuntimeException $exception) {
		check($exception->getMessage() !== 'Existing backup was overwritten', 'Existing backup was overwritten');
	}
	check(file_get_contents($backupPath) === "original config\n", 'Original config changed after backup conflict');
	check(file_get_contents($directory . '/backup_old.yml') === "prior backup\n", 'Prior backup changed');
	check($backupPlugin->saves === 0, 'Default config was saved after backup conflict');

	$invalid = new Config($directory . '/invalid.yml', ['version' => 123]);
	try {
		ConfigChecker::checkConfigVersion(new PluginBase($invalid), $invalid, 'version', '1.2.3');
		throw new \RuntimeException('Invalid version was accepted');
	} catch (libBedrockUnexpectedValueException $exception) {
		check(!file_exists($invalid->getPath()), 'Invalid config was written');
	}

	echo "PASS: current/future config preserved, older config backed up, conflicting backup and invalid value rejected\n";
}
