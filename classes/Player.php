<?php

namespace Rumorsmatrix\Mud;


/**
 * Class Player
 * @package Rumorsmatrix\Mud
 * @property-read string $name
 * @property-read int $location_id
 * @property Server $server
 */
class Player extends \Illuminate\Database\Eloquent\Model {

	protected $client = null;
	protected $server = null;


	/**
	 * Player constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return null
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * @param null $client
	 */
	public function setClient($client) {
		$this->client = $client;
	}

	public function setServer(Server $server) {
		$this->server = $server;
	}


	public function getCurrentLocation() {
		return $this->server->getLocationByID($this->location_id);
	}


	public function lookAtLocation() {
		$location = $this->server->getLocationByID($this->location_id);
		$this->server->send($this, $location->getYAML());
		$this->server->send($this,  $location->getHTML());
	}


	public function moveToLocation($location_slug) {

		$new_location_id = Location::getIDFromSlug($location_slug);
		if ($new_location_id) {

			$current_location = $this->getCurrentLocation();
			$current_location->setPlayerNotPresent($this);

			$this->location_id = $new_location_id;
			$this->save();

			$location = $this->server->getLocationByID($new_location_id);
			$location->setPlayerPresent($this);
		}
	}



}