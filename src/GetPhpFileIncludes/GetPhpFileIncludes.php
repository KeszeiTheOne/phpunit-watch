<?php

namespace Watcher\GetPhpFileIncludes;

class GetPhpFileIncludes {

	public static function getPhpFileIncludes($rootDir, $filePath): array {
		if (!file_exists($filePath)) {
			return [];
		}
		$includeScript = realpath(__DIR__ . '/../../PhpFileIncludes');
		$exec = exec("cd $rootDir; $includeScript $filePath");
		$jsonDecode = json_decode($exec, true);
		if (false === $jsonDecode) {
			return [];
		}
		return array_values($jsonDecode);
	}

}