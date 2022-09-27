<?php

namespace Watcher\GetPhpFileIncludes;

use ReflectionClass;

class GetPhpFileIncludes {

	public static function getPhpFileIncludes($file): array {
		if (!file_exists($file) || !is_file($file)) {
			return [];
		}
		$includes = [$file];

		if (!is_readable($file)) {
			return $includes;
		}

		$handle = fopen($file, "r");

		if ($handle) {
			$imports = [];
			while (($line = fgets($handle)) !== false) {
				if (preg_match('/^use .+;$/', $line)) {
					$imports[] = trim(preg_replace('/(^use )|(;$)/', '', $line));
				}
			}

			fclose($handle);
			foreach ($imports as $import) {
				try {
					$reflectionClass = new ReflectionClass($import);
				} catch (\Throwable $exception) {
					continue;
				}
				$fileName = $reflectionClass->getFileName();
				if ($fileName && !preg_match('/\/vendor\//', $fileName)) {
					if (!preg_match('/\/vendor\//', $fileName)) {
						foreach (self::getPhpFileIncludes($fileName) as $includedFile) {
							if (!in_array($includedFile, $includes)) {
								$includes[] = $includedFile;

							}
						}
					}
					if (!in_array($fileName, $includes)) {
						$includes[] = $fileName;
					}
				}
			}
		}

		return $includes;
	}

}
