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
			$this->path('/testIncludes/a/b/Foo.php'),
		], $this->getIncludes($this->path('/testIncludes/a/b/Foo.php')));
	}

	/**
	 * @test
	 */
	public function getPhpFileIncludes() {
		$this->assertSame([
			$this->path('/testIncludes/a/Bazz.php'),
			$this->path('/testIncludes/a/b/Foo.php'),
		], $this->getIncludes($this->path('/testIncludes/a/Bazz.php')));
	}

	/**
	 * @test
	 */
	public function getPhpFileChildIncludes() {
		$this->assertSame([
			$this->path('/testIncludes/Apple.php'),
			$this->path('/testIncludes/a/Bazz.php'),
			$this->path('/testIncludes/a/b/Foo.php'),
		], $this->getIncludes($this->path('/testIncludes/Apple.php')));
	}

	/**
	 * @test
	 */
	public function excludeUseNames() {
		$this->assertSame([
			$this->path('/exclude/ExcludeUseNames.php'),
			$this->path('/testIncludes/a/b/Foo.php'),
		], $this->getIncludes($this->path('/exclude/ExcludeUseNames.php')));
	}

	/**
	 * @test
	 */
	public function excludeSameFile() {
		$this->assertSame([
			$this->path('/exclude/ExcludeExistImport.php'),
			$this->path('/testIncludes/a/b/Foo.php'),
			$this->path('/testIncludes/a/Bazz.php'),
		], $this->getIncludes($this->path('/exclude/ExcludeExistImport.php')));
	}

	private function getIncludes($filePath): array {
		return GetPhpFileIncludes::getPhpFileIncludes($filePath);
	}

	private function path($path): string {
		return __DIR__ . $path;
	}

}

