<?php

require_once 'smush.php';

$options = array();
$path = false;

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

		default:
			if (preg_match('/^-/', $arg)) {
				echo "Optimize a single image or a whole folder in the cloud.\n";
				echo "\n";
				echo "gif`s:\n";
				echo "  - called with a folder gif`s will not be optimized\n";
				echo "  - called on a singe .gif, it will be optimized if it is optimizeable\n";
				echo "\n";
				echo "Usage:\n";
				echo "  php smushit.php /apps/x/public/images [options]\n";
				echo "  php smushit.php /apps/x/public/images/x.png [options]\n";
				echo "  php smushit.php /apps/x/public/images/*.png [options]\n";
				echo "\n";
				echo "Options are:\n";
				echo "  -q, --quiet		no output\n";
				echo "  -c, --convert-gifs	convert all .gif`s in the given folder\n";
				echo "  -h, --help		show this\n";

				exit;
			}
			else
				$path = $arg;
	}

smush::it($path, $options);

?>