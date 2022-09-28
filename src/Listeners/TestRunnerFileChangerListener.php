<?php

namespace Watcher\Listeners;

use Watcher\FileIncludesIterator;
use Watcher\TestRunner;

class TestRunnerFileChangerListener {

	/**
	 * @var FileIncludesIterator
	 */
	private $files;

	/**
	 * @var TestRunner
	 */
	private $testRunner;


	public function __construct(FileIncludesIterator $files, TestRunner $testRunner) {
		$this->files = $files;
		$this->testRunner = $testRunner;
	}


	public function onCreate($path) {
		$this->files->addFile($path);
	}

	public function onChange($path) {
		if (isset($this->files[$path])) {
			$this->testRunner->runTest($this->files[$path]);
		}
	}

}
