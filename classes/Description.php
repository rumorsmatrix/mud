<?php

namespace Rumorsmatrix\Mud;


class Description {

	static public function getHTML($slug) {
		$parser = new \Mni\FrontYAML\Parser();
		$filename = __DIR__ . '/../content/descriptions/' . $slug . '.md';

		if ($filename) {
			$document = file_get_contents($filename);
			$markdown = $parser->parse($document);
			return $markdown->getContent();

		} else {
			echo "Failed to open description file: {$filename}\n";
			return false;
		}

	}


}