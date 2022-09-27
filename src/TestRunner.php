<?php

namespace Watcher;

class TestRunner {

	private $phpunitPath;

	private $arguments;

	public function __construct($phpunitPath, $arguments) {
		$this->phpunitPath = $phpunitPath;
		$this->arguments = $arguments;
	}

	public function runTest($file = null) {
		if ($this->isTeamCity()) {
			system('echo "##teamcity[testingStarted]"');
		} else {
			system('clear');
		}
		system($this->phpunitPath . " " . $this->createArgumentsWithFile($file));
		if ($this->isTeamCity()) {
			system('echo "##teamcity[testingFinished]"');
		}
	}

	private function createArgumentsWithFile($path = null): string {
		$arguments = [];
		if ($path) {
			$arguments = [escapeshellarg($path)];
		}
		for ($i = 1; $i < count($this->arguments); $i++) {
			$var = $this->arguments[$i];
			if ($var === '--watch') {
				continue;
			}
			$arguments[] = escapeshellarg($var);
		}

		return implode(" ", $arguments);
	}

	private function isTeamCity(): bool {
		return in_array('--teamcity', $this->arguments);
	}

}
