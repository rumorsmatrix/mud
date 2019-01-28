<?php

namespace Rumorsmatrix\Mud;


/**
 * Class Location
 * @package Rumorsmatrix\Mud
 * @property-read string slug
 */
class Location extends \Illuminate\Database\Eloquent\Model {

	protected $table = 'locations';
	private $yaml_parser = null;
	private $markdown = '';
	private $yaml = null;
	private $html = '';
	private $connections = [];
	private $players_present = [];


	public function __construct() {
		parent::__construct();
		if (!isset($this->yaml_parser)) $this->yaml_parser = new \Mni\FrontYAML\Parser();
	}


	public function getConnections() {
		if (empty($this->yaml)) $this->yaml = $this->markdown->getYAML();
		return $this->connections;
	}

	public function getPlayersPresent() {
		return $this->players_present;
	}

	public function setPlayerPresent(Player $player) {
		$this->players_present[$player->id] = $player;
	}

	public function setPlayerNotPresent(Player $player, Server $server) {
		if (isset($this->players_present[$player->id])) {
			unset($this->players_present[$player->id]);

			// did the last player just leave?
			if (count($this->players_present) === 0) {
				$server->unsetLocation($this->id);
			}
		}
	}

	public function getYAML() {
		if (empty($this->markdown)) $this->markdown = $this->getMarkdown();
		if (empty($this->yaml)) $this->yaml = $this->markdown->getYAML();

		if (!empty($this->yaml['connections'])) {
			$this->connections = $this->yaml['connections'];
			unset($this->yaml['connections']);
		}
		return $this->yaml;
	}


	public function getHTML() {
		if (empty($this->markdown)) $this->markdown = $this->getMarkdown();
		if (empty($this->html)) $this->html = $this->markdown->getContent();
		if (empty($this->yaml)) $this->html = $this->markdown->getYAML();

		$html = $this->html;
		if (isset($this->yaml['title'])) {
			$html = "<h2 class=\"location-title\">{$this->yaml['title']}</h2>" . $html;
		}
		return $html;
	}


	private function getMarkdown() {
		if (!empty($this->markdown)) return $this->markdown;
		$document = $this->getDocument();
		$this->markdown = $this->yaml_parser->parse($document);
		return $this->markdown;
	}

	private function getDocument() {
		$filename = __DIR__ . '/../content/locations/' . $this->slug . '.md';
		return file_exists($filename) ? file_get_contents($filename) : false;
	}

	public static function getIDFromSlug($slug) {
		$location_id = static::select('id')->where('slug', '=', $slug)->get()->pluck('id')->toArray();
		return (!empty($location_id)) ? $location_id[0] : false;
	}


}
