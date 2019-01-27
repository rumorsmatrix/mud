<?php

namespace Rumorsmatrix\Mud;

class Parser {


	public function __construct() { }


	static public function handle(array $data, Player $player, Server $server) {

		foreach($data as $key => $value) {

			if ($key === 'move') {
				$current_location = $player->getCurrentLocation($server);
				if (in_array($value, $current_location->getConnections())) {
					$player->moveToLocation($value, $server);
					static::sendLocation($player, $server);
				}
			}


		}
	}


	static public function sendLocation(Player $player, Server $server) {
		$location = $server->getLocationByID($player->location_id);
		$server->send($player, $location->getYAML());
		$server->send($player,  $location->getHTML());
	}


}

