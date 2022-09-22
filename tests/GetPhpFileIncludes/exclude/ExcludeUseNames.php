<?php

namespace Tests\Watcher\GetPhpFileIncludes\exclude;

use Tests\Watcher\GetPhpFileIncludes\testIncludes\a\b\Foo;

class ExcludeUseNames {

	/**
	 * @var Foo
	 */
	public $user;

	public $notuseThis;

	public function notuse () {
		
	}

}