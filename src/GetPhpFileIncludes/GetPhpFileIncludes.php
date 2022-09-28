<?php

namespace Watcher\GetPhpFileIncludes;

use ArrayIterator;
use IteratorAggregate;
use ReflectionClass;

class GetPhpFileIncludes implements IteratorAggregate {

	private $file;

	private $includes = [];

	public function __construct($file) { $this->file = $file; }

	public static function getPhpFileIncludes($file): array {
		return iterator_to_array(new self($file));
	}

	public function setIncludes(array $includes): self {
		$this->includes = $includes;

		return $this;
	}

	public function getIterator() {
		if (!file_exists($this->file) || !is_file($this->file) || in_array($this->file, $this->includes)) {
			return new ArrayIterator($this->includes);
		}

		$this->includes = array_merge($this->includes, [$this->file]);

		if (!is_readable($this->file)) {
			return new ArrayIterator($this->includes);
		}

		foreach ($this->getFileImports($this->file) as $import) {
			try {
				$reflectionClass = new ReflectionClass($import);
			}
			catch (\Throwable $exception) {
				continue;
			}
			foreach ($this->getFileIncludes($reflectionClass->getFileName()) as $fileInclude) {
				if (!in_array($fileInclude, $this->includes)) {
					$this->includes[] = $fileInclude;

				}
			}
		}

		return new ArrayIterator($this->includes);
	}

	private function getFileImports($file): array {
		$handle = fopen($file, "r");
		if (!$handle) {
			return [];
		}
		$imports = [];
		while (($line = fgets($handle)) !== false) {
			if (preg_match('/^use .+;$/', $line)) {
				$imports[] = trim(preg_replace('/(^use )|(;$)/', '', $line));
			}
		}
		fclose($handle);

		return $imports;
	}

	private function getFileIncludes($fileName): array {
		$includes = [];
		if (!$fileName || preg_match('/\/vendor\//', $fileName)) {
			return $includes;
		}
		$includes[] = $fileName;

		foreach ((new self($fileName))->setIncludes($this->includes)->getIterator() as $includedFile) {
			if (!preg_match('/\/vendor\//', $includedFile)) {
				$includes[] = $includedFile;

			}
		}

		return $includes;
	}

}
