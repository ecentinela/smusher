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
	echo str_pad("  -r, --recursive", 26, " ") . "execute the action on all subdirectories\n";
	echo str_pad("  -h, --help", 26, " ") . "show this\n";

	exit;
}

if ($_SERVER['argc'] == 1)
	help();

require_once 'smush.php';

$options = array();
$path = false;

$arguments = array_splice($_SERVER['argv'], 1);

foreach ($arguments as $arg) {
	$is_option = preg_match('/^-/', $arg);

	if ($is_option && !$path)
		help();

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

		case '--recursive':
		case '-r':
			$options[] = 'recursive';
			break;

		default:
			if ($is_option || $path)
				help();

			$path = $arg;
	}
}

smush::it($path, $options);

?>