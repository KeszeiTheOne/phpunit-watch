<?php

namespace Tests\Watcher\GetPhpFileIncludes\exclude;

use Tests\Watcher\GetPhpFileIncludes\testIncludes\a\b\Foo;
use Tests\Watcher\GetPhpFileIncludes\testIncludes\a\Bazz;

class ExcludeExistImport {

	/**
	 * @var Bazz
	 */
	public $bazz;

	/**
	 * @var Foo
	 */
	public $foo;

}