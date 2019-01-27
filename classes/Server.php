<?php

namespace Rumorsmatrix\Mud;
use vakata\websocket\Server as WebsocketsServer;
use Rumorsmatrix\Mud\Player as Player;
use Rumorsmatrix\Mud\Location as Location;


class Server {

	private $websockets = null;
	private $log_file = "";
	private $construct_time = 0;
	private $listening_time = 0;
	private $players = [];
	private $locations = [];

	public function __construct($address = 'ws://127.0.0.1:8080', $cert = null, $log_file = "") {
		$this->construct_time = time();
		$this->log_file = $log_file;
		$this->players = [];
		$this->locations = [];

		try {
			$this->websockets = new WebsocketsServer($address, $cert);
			$this->registerCallbacks();

		} catch (\Exception $e) {
			print_r($e);
			die();
		}
	}


	private function registerCallbacks() {

		$this->websockets->validateClient(
			function ($client) {
				return $this->validateClient($client);
			}
		);

		$this->websockets->onConnect(
			function ($client) {
				$this->onConnect($this->players[$client['cookies']['ws_session']]);
			}
		);

		$this->websockets->onDisconnect(
			function ($client) {
				$this->onDisconnect($this->players[$client['cookies']['ws_session']]);
			}
		);

		$this->websockets->onMessage(
			function ($sender, $message) {
				if (!empty($this->players[$sender['cookies']['ws_session']])) {
					$this->onMessage($this->players[$sender['cookies']['ws_session']], $message);
				}
			}
		);

		$this->websockets->onTick(
			function () {
				$this->onTick();
			}
		);

	}


	private function validateClient(array $client) {
		$this->log("Validating client...");
		if (
			(isset($client['headers']['origin']) && $client['headers']['origin'] === 'https://rumorsmatrix.com') &&
			(isset($client['cookies']['ws_session']))
		) {

			if (empty($this->players[$client['cookies']['ws_session']])) {

				// see if this player exists in the database
				$player = Player::where('session', $client['cookies']['ws_session'])->first();

				if ($player) {
					// this player exists, add it to the players in memory
					/** @var Player $player */
					$player->setClient($client);
					$this->players[$client['cookies']['ws_session']] = $player;


					$this->log("Accepted connection (added to memory): " . $player->name);
					return true;

				} else {
					// no session/player in the database.
					$this->log("Declined connection: no valid player/session combination.");
					return false;
				}

			} else {

				// this session is already in the players list, so we're happy
				$this->players[$client['cookies']['ws_session']]->setClient($client);
				$this->log("Accepted connection (already in memory): " . $this->players[$client['cookies']['ws_session']]->name );
				return true;
			}

		} else {
			$this->log("Declined connection.");
			return false;
		}
	}


	private function onConnect(Player $player) {
		$this->log("Connected: " . $player->name);

		// send the player's current location to them
		Parser::sendLocation($player, $this);
	}

	private function onDisconnect(Player $player) {
		$this->log($player->name . " disconnected.");

		// we have to check, because they might have opened a new socket elsewhere (ie: closed old tab with new one already connected)
		if (isset($this->players[$player->getClient()['cookies']['ws_session']])) {
			unset($this->players[$player->getClient()['cookies']['ws_session']]);
		}
	}


	private function onMessage(Player $player, $message) {
		if ($message == "PING") {
			$this->onPing($player);
			return;
		}

		$this->log("Parsing [" . $player->name . "]: " . $message);
		$data = json_decode($message, true);
		Parser::handle($data, $player, $this);
	}


	private function onTick() {

	}

	private function onPing(Player $player) {
		$this->send($player, "PONG");
	}


	public function send(Player $player, $message) {
		if (is_array($message)) $message = json_encode($message);
		$client_socket = $player->getClient()['socket'];
		$this->websockets->send($client_socket, $message);
	}

	public function broadcast($message) {
		$this->log("---- Broadcasting start");
		foreach ($this->players as $player) {
			$this->send($player, $message);
		}
		$this->log("---- Broadcasting finished");
	}


	public function startListening() {
		echo "Listening...\n";
		$this->listening_time = time();
		$this->websockets->run();
	}


	public function getClients() {
		$this->websockets->getClients();
	}

	public function getLocations() {
		return $this->locations;
	}

	public function getLocation($location_id) {
		return (isset($this->locations[$location_id])) ? $this->locations[$location_id] : NULL;
	}

	public function setLocation($index, Location $location) {
		$this->locations[$index] = $location;
	}

	// todo: need an unsetLocation() call or memory will get clogged up with them!


	public function log($message) {
		if (empty($this->log_file)) {
			echo date('Y-m-d H:i:s') . "\t" . $message . "\n";
			return;
		}
	}


}