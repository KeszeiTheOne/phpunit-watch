<?php

namespace Tests\Watcher\GetPhpFileIncludes;

use PHPUnit\Framework\TestCase;
use Watcher\GetPhpFileIncludes\GetPhpFileIncludes;

class GetPhpFileIncludesTest extends TestCase {

	/**
	 * @test
	 */
	public function getEmptyArrayIfNotExistFile() {
		$this->assertSame([], $this->getIncludes($this->path('/notexist.php')));
	}

	/**
	 * @test
	 */
	public function getPhpSelfFileIncludes() {
		$this->assertSame([
			$this->path('/foo.php'),
		], $this->getIncludes($this->path('/foo.php')));
	}

	/**
	 * @test
	 */
	public function getPhpFileIncludes() {
		$this->assertSame([
			$this->path('/bazz.php'),
			$this->path('/foo.php'),
		], $this->getIncludes($this->path('/bazz.php')));
	}

	/**
	 * @test
	 */
	public function getPhpFileChildIncludes() {
		$this->assertSame([
			$this->path('/apple.php'),
			$this->path('/bazz.php'),
			$this->path('/foo.php'),
		], $this->getIncludes($this->path('/apple.php')));
	}

	private function getIncludes($filePath): array {
		return GetPhpFileIncludes::getPhpFileIncludes(realpath(__DIR__ . '/../..'), $filePath);
	}

	private function path($path): string {
		return __DIR__ . $path;
	}

}

