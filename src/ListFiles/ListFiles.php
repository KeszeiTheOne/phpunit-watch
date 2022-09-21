<?php

namespace Watcher\ListFiles;

class ListFiles {

	public static function listPhpFiles($dir) {
		return self::listFiles($dir, '/\.php$/');
	}

	public static function listFiles($dir, $pattern = null): array {
		if (!is_dir($dir)) {
			return [];
		}

		$files = scandir($dir);
		$results = [];

		foreach ($files as $key => $value) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				if (null === $pattern || preg_match($pattern, $path)) {
					$results[] = $path;
				}
			} else if ($value != "." && $value != "..") {
				$results = array_merge($results, self::listFiles($path, $pattern));
			}
		}

		return $results;

	}

}