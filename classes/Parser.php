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

		if (null !== $server->getLocation($player->location_id)) {
			// location is in-memory on the server object
			$location = $server->getLocation($player->location_id);
			$server->log("Location ({$location->slug}) is already in memory.");

		} else {
			// load the location from the database
			$location = Location::find($player->location_id);

			if ($location) {
				$server->log("Loaded location ({$location->slug}) from database to memory.");
				$server->setLocation($location->id, $location);

			} else {
				// todo: the player's location ID is invalid, this should never happen?
				echo "INVALID LOCATION ID: " . $player->location_id;
				die();
			}

		}

		$server->send($player, $location->getYAML());
		$server->send($player,  $location->getHTML());
	}


}

