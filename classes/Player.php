<?php

namespace Rumorsmatrix\Mud;


/**
 * Class Player
 * @package Rumorsmatrix\Mud
 * @property-read string $name
 * @property-read int $location_id
 */
class Player extends \Illuminate\Database\Eloquent\Model {

	protected $client = null;


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


	public function getCurrentLocation(Server $server) {
		return $server->getLocation($this->location_id);
	}


	public function moveToLocation($location_slug, $server) {
		$new_location_id = Location::getIDFromSlug($location_slug);

		if ($new_location_id) {
			$this->location_id = $new_location_id;
			$this->save();
		}
	}



}