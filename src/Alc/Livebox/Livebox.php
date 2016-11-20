<?php

namespace Alc\Livebox;

use Alc\Curl\CurlInterface;

class Livebox {

	private $curl;
	private $liveboxIp;
	private $contextID;

	/**
	 * __construct
	 */
	public function __construct( CurlInterface $curl, $liveboxIp = '192.168.1.1' ) {

		$this->curl = $curl;

		$cookie = '/tmp/livebox.cookie';
		if( file_exists($cookie) ) unlink($cookie);
		$this->curl->setCookieJar($cookie);

		$this->liveboxIp = $liveboxIp;
	}

	/**
	 * getLiveboxAddress
	 */
	private function getLiveboxAddress() {

		return 'http://'.$this->liveboxIp;
	}

	/**
	 * login
	 */
	public function login( $username = 'admin', $password = 'admin' ) {

        $this->curl->setOptions(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/x-www-form-urlencoded; charset=UTF-8',

            ),
        ));

		$json = $this->curl
			->post($this->getLiveboxAddress().'/authenticate?'.http_build_query(array(
				'username' => $username,
				'password' => $password,
			)), array(
				'username' => $username,
				'password' => $password,
			))
			->getJson();

		$this->contextID = $json->data->contextID;

		return $json;
	}

	/**
	 * makeAjaxRequest
	 */
	private function makeAjaxRequest( $url, $post = array() ) {

        $this->curl->setOptions(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-Context: '.$this->contextID,
            ),
        ));

		return $this->curl->post($url, $post)->getJson();
	}

	/**
	 * userManagementGetUsers
	 */
	public function userManagementGetUsers() {

		return $this->makeAjaxRequest($this->getLiveboxAddress().'/sysbus/UserManagement:getUsers', '{"parameters":{}}');
	}

	/**
	 * getInfoDSL
	 */
	public function getInfoDSL() {

		return $this->makeAjaxRequest($this->getLiveboxAddress().'/sysbus/NeMo/Intf/data:getMIBs', json_encode(array(
				'parameters' => array(
					'mibs' => 'dsl',
					'flag' => '',
					'traverse' => 'down',
			)))
		);
	}

	/**
	 * getDSLStats
	 */
	public function getDSLStats() {

		return $this->makeAjaxRequest($this->getLiveboxAddress().'/sysbus/NeMo/Intf/dsl0:getDSLStats', '{"parameters":{}}');
	}

	/**
	 * getWANStatus
	 */
	public function getWANStatus() {

		return $this->makeAjaxRequest($this->getLiveboxAddress().'/sysbus/NMC:getWANStatus', '{"parameters":{}}');
	}

	/**
	 * reboot
	 */
	public function reboot() {

		return $this->makeAjaxRequest($this->getLiveboxAddress().'/sysbus/NMC:reboot', '{"parameters":{}}');
	}
}
