<?php

Class smush {

	// original, redirects to somewhere else..
	// const url = 'http://smush.it/ws.php';

	// official but does not work
	// const url = 'http://developer.yahoo.com/yslow/smushit/ws.php';

	// used at the new page but does not hande uploads
	// const url = 'http://smushit.com/ysmush.it/ws.php';

	// used at the new page but does not hande uploads
	// const url = 'http://smushit.eperf.vip.ac4.yahoo.com/ysmush.it/ws.php';

	// working
	const url = 'http://ws1.adq.ac4.yahoo.com/ysmush.it/ws.php';

	// regexp for check extension
	private static $regexp;

	/*
	*/
	static function it($path, $options = array()) {
		$regexp = in_array('gifs', $options) ? '/\.(jpg|jpeg|png|gif)$/i' : '/\.(jpg|jpeg|png)$/i';
		$quiet = in_array('quiet', $options);
		$pretend = in_array('pretend', $options);

		// create the curl object
		$curl = curl_init(self::url);

		// set default options
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true
		));

		// is the path is a folder, we get all images on these folder
		$fn = is_dir($path) ? 'folder' : 'file';

		// call the method
		call_user_func('smush::' . $fn, $curl, $path, $regexp, $quiet, $pretend);

		// close curl to free memory
		curl_close($curl);
	}

	/*
	*/
	private static function folder($curl, $path, $regexp, $quiet, $pretend) {
		// loop through all files on the folder to get images
		$it = new DirectoryIterator($path);

		foreach ($it as $file) {
			$path = $file->getPathname();

			// if it's a folder, scan it too
			if ($file->isDir() && !$file->isDot())
				self::folder($curl, $path, $regexp, $quiet, $pretend);
			// smush jpg, jpeg and png images
			// gif images are converted to gifs if option is setted
			elseif (preg_match($regexp, $path)) {
				self::file($curl, $path, $regexp, $quiet, $pretend);

				if (!$quiet)
					echo "\n";
			}
		}
	}

	/*
	*/
	private static function file($curl, $path, $regexp, $quiet, $pretend) {
		// check that the file exists
		if (!file_exists($path))
			throw new Exception('Invalid file path: ' . $path);
		// check it is a valid field
		elseif (preg_match($regexp, $path)) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, array(
				'files' => '@' . $path
			));

			if (!$quiet)
				echo "  smushing " . $path . "\n";

			// call the server app
			$response = curl_exec($curl);

			// if no response from the server
			if ($response === false) {
				if (!$quiet)
					echo "  error: the server has gone\n";
			}
			// server respond
			else {
				// decode the json response
				$data = json_decode($response);

				// if there is some error
				if (!empty($data->error)) {
					if (!$quiet)
						echo "  error: " . strtolower($data->error) . "\n";
				}
				// if optimized size is larget than the original
				elseif ($data->src_size < $data->dest_size) {
					if (!$quiet)
						echo "  error: got larger\n";
				}
				// if optimized size is smaller than 20 bytes (prevent empty images)
				elseif ($data->dest_size < 20) {
					if (!$quiet)
						echo "  error: empty file downloaded";
				}
				// if size are equal
				elseif ($data->src_size == $data->dest_size) {
					if (!$quiet)
						echo "  cannot be optimized further";
				}
				else {
					if (!$quiet)
						echo str_pad("  " . $data->src_size . " -> " . $data->dest_size, 26, " ") . " = " . round($data->dest_size * 100 / $data->src_size) . "%\n";

					// if it's a gif image it is converted to a png file
					if (preg_match('/\.gif$/i', $path)) {
						unlink($path);

						$path = substr($path, 0, -3) . 'png';
					}

					if ($pretend)
						return true;

					$content = file_get_contents($data->dest);

					return file_put_contents($path, $content);
				}
			}
		}
		elseif (!$quiet)
			echo "  error: invalid file " . $path . "\n";
	}

}

?>