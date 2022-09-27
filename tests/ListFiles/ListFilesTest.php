<?php

namespace Tests\Watcher\ListFiles;

use PHPUnit\Framework\TestCase;
use Watcher\ListFiles\ListFiles;

class ListFilesTest extends TestCase {

	/**
	 * @test
	 */
	public function listEmptyOnNonExistFolder() {
		$this->assertSame([], $this->list($this->path('/nonoexist')));
	}

	/**
	 * @test
	 */
	public function nonListedEmptyFolder() {
		$empty = $this->path("/empty");
		if (!is_dir($empty)) {
			mkdir($empty);
		}

		$this->assertSame([], $this->list($empty));

		rmdir($empty);
	}

	/**
	 * @test
	 */
	public function listOneFile() {
		$this->assertSame([
			$this->path("/onefile/one.txt"),
		], $this->list($this->path("/onefile")));
	}

	/**
	 * @test
	 */
	public function listRecursive() {
		$dir = '/recursive';
		$this->assertSame([
			$this->path("$dir/one.txt"),
			$this->path("$dir/sub/subsub/third.txt"),
			$this->path("$dir/sub/two.txt"),
		], $this->list($this->path("$dir")));
	}

	/**
	 * @test
	 */
	public function excludeFilesWithRegex() {
		$this->assertSame([
			$this->path("/exclude/keep.xml"),
			$this->path("/exclude/sub/keep2.xml"),
		], $this->list($this->path("/exclude"), '/\.xml$/'));
	}

	private function list($path, $pattern = null) {
		return ListFiles::listFiles($path, $pattern);
	}

	private function path($path) {
		return __DIR__ . $path;
	}

}

