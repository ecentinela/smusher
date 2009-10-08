<?php

function help() {
	echo "Optimize a single image or a whole folder in the cloud.\n";
	echo "\n";
	echo "gif's:\n";
	echo "  - called with a folder gif`s will not be optimized\n";
	echo "  - called on a singe .gif, it will be optimized if it is optimizeable\n";
	echo "\n";
	echo "Usage:\n";
	echo "  php smusher.php /images [options]\n";
	echo "  php smusher.php /images/x.png [options]\n";
	echo "\n";
	echo "Options are:\n";
	echo str_pad("  -q, --quiet", 26, " ") . "no output\n";
	echo str_pad("  -c, --convert-gifs", 26, " ") . "convert all .gif's in the given folder\n";
	echo str_pad("  -pc, --pretend", 26, " ") . "no changes are made\n";
	echo str_pad("  -h, --help", 26, " ") . "show this\n";

	exit;
}

if ($argc == 1)
	help();

require_once 'smush.php';

$options = array();
$path = false;

array_shift($argv);

foreach ($argv as $arg)
	switch ($arg) {
		case '--convert-gifs':
		case '-c':
			$options[] = 'gifs';
			break;

		case '--quiet':
		case '-q':
			$options[] = 'quiet';
			break;

		case '--pretend':
		case '-p':
			$options[] = 'pretend';
			break;

		default:
			if (preg_match('/^-/', $arg))
				help();

			$path = $arg;
	}

smush::it($path, $options);

?>