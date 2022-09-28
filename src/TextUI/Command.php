<?php

namespace Watcher\TextUI;

use Exception;
use MKraemer\ReactInotify\Inotify;
use React\EventLoop\Factory;
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
		$loop = Factory::create();
		$inotify = new Inotify($loop);
		foreach (ListFiles::listFiles(getcwd() . "/src") as $file) {
			$inotify->add($file, IN_MODIFY | IN_CLOSE_WRITE);
		}
		foreach (ListFiles::listFiles(getcwd() . "/tests") as $file) {
			$inotify->add($file, IN_MODIFY | IN_CLOSE_WRITE);
		}
		$changeListener = new TestRunnerFileChangerListener($iterator, $testRunner);
		$inotify->on(IN_CLOSE_WRITE, [$changeListener, "onChange"]);

		$loop->run();
	}

}
