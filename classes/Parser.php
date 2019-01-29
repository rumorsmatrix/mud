<?php

namespace Rumorsmatrix\Mud;

class Parser {


	public function __construct() { }


	static public function handle(array $data, Player $player, Server $server) {

		foreach($data as $key => $value) {
			$value = trim($value);

			if ($key === 'move') {
				$current_location = $player->getCurrentLocation();
				if (in_array($value, $current_location->getConnections())) {
					$player->moveToLocation($value);
					$player->lookAtLocation();
				}
			}


			elseif ($key === 'examine' && !empty($value)) {
				$current_location = $player->getCurrentLocation();
				$actions = $current_location->getActions();

				if (!isset($actions['examine'])) return false;

				if (in_array($value, $actions['examine'])) {
					$description = Description::getHTML($value);
					$server->send($player, $description);
				}
			}


			elseif ($key === 'say' && !empty($value)) {
				$player->say($value);
			}

		}
	}




	// todo: should this be on the server? or the player? or the location?
	static public function sendLocation(Player $player, Server $server) {
		$location = $server->getLocationByID($player->location_id);
		$server->send($player, $location->getYAML());
		$server->send($player,  $location->getHTML());
	}


}

