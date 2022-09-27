<?php

namespace Watcher;

use ArrayAccess;
use Watcher\GetPhpFileIncludes\GetPhpFileIncludes;

class FileIncludesIterator implements ArrayAccess {

	private $testFiles = [];

	private $sourceFiles = [];

	public function addFile($filePath) {
		if (!isset($this->testFiles[$filePath])) {
			$this->testFiles[$filePath] = [];
		}
		foreach (GetPhpFileIncludes::getPhpFileIncludes($filePath) as $include) {
			$this->testFiles[$filePath][] = $include;
			if (!isset($this->sourceFiles[$include])) {
				$this->sourceFiles[$include] = [];
			}
			$this->sourceFiles[$include] = $filePath;
		}
	}

	public function offsetExists($offset) {
		return isset($this->testFiles[$offset]) ||
			isset($this->sourceFiles[$offset]);
	}

	public function offsetGet($offset) {
		if (isset($this->testFiles[$offset])) {
		    return $offset;
		}

		return $this->sourceFiles[$offset];
	}

	public function offsetSet($offset, $value) {
		$this->addFile($value);
	}

	public function offsetUnset($offset) {
		unset($this->testFiles[$offset]);
	}

}
