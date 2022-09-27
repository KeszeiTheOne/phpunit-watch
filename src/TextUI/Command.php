<?php

namespace Watcher\TextUI;


use Dimsh\React\Filesystem\Monitor\Monitor;
use Dimsh\React\Filesystem\Monitor\MonitorConfigurator;
use Exception;
use RuntimeException;
use Throwable;
use Watcher\FileIncludesIterator;
use Watcher\Listeners\TestRunnerFileChangerListener;
use Watcher\ListFiles\ListFiles;
use Watcher\TestRunner;

class Command {

	/**
	 * @throws Exception
	 */
	public static function main($phpunit) {
		try {
			(new static)->run($phpunit, $_SERVER['argv']);
		} catch (Throwable $t) {
			throw new RuntimeException(
				$t->getMessage(),
				(int)$t->getCode(),
				$t
			);
		}
	}

	public function run($phpunit, $arguments) {
		$testRunner = new TestRunner($phpunit, $arguments);
		if (!in_array('--watch', $arguments)) {
			$testRunner->runTest();
			exit();
		}
		$tests = PHPUNIT_WATCHER_TEST_DIRECTORY;
		$iterator = new FileIncludesIterator();
		foreach (ListFiles::listPhpFiles($tests) as $listPhpFile) {
			$iterator->addFile($listPhpFile);
		}

		$monitor = new Monitor(MonitorConfigurator::factory()
												  ->setLevel(0)
												  ->setFilesToMonitor([
													  '*.php',
												  ])
												  ->setBaseDirectory(getcwd()));

		$changeListener = new TestRunnerFileChangerListener($iterator, $testRunner);

		$monitor
			->on(Monitor::EV_MODIFY, [$changeListener, 'onChange'])
			->run();
	}

}