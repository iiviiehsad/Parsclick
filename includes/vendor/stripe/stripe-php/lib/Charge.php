<?php

namespace Stripe;

class Charge extends ApiResource {

	/**
	 * @param string            $id The ID of the charge to retrieve.
	 * @param array|string|null $options
	 * @return Charge
	 */
	public static function retrieve($id, $options = NULL)
	{
		return self::_retrieve($id, $options);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @return Collection of Charges
	 */
	public static function all($params = NULL, $options = NULL)
	{
		return self::_all($params, $options);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @return Charge The created charge.
	 */
	public static function create($params = NULL, $options = NULL)
	{
		return self::_create($params, $options);
	}

	/**
	 * @param array|string|null $options
	 * @return Charge The saved charge.
	 */
	public function save($options = NULL)
	{
		return $this->_save($options);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @return Charge The refunded charge.
	 */
	public function refund($params = NULL, $options = NULL)
	{
		$url = $this->instanceUrl() . '/refund';
		list($response, $opts) = $this->_request('post', $url, $params, $options);
		$this->refreshFrom($response, $opts);

		return $this;
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @return Charge The captured charge.
	 */
	public function capture($params = NULL, $options = NULL)
	{
		$url = $this->instanceUrl() . '/capture';
		list($response, $opts) = $this->_request('post', $url, $params, $options);
		$this->refreshFrom($response, $opts);

		return $this;
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @deprecated Use the `save` method on the Dispute object
	 * @return array The updated dispute.
	 */
	public function updateDispute($params = NULL, $options = NULL)
	{
		$url = $this->instanceUrl() . '/dispute';
		list($response, $opts) = $this->_request('post', $url, $params, $options);
		$this->refreshFrom(array('dispute' => $response), $opts, TRUE);

		return $this->dispute;
	}

	/**
	 * @param array|string|null $options
	 * @deprecated Use the `close` method on the Dispute object
	 * @return Charge The updated charge.
	 */
	public function closeDispute($options = NULL)
	{
		$url = $this->instanceUrl() . '/dispute/close';
		list($response, $opts) = $this->_request('post', $url, NULL, $options);
		$this->refreshFrom($response, $opts);

		return $this;
	}

	/**
	 * @param array|string|null $opts
	 * @return Charge The updated charge.
	 */
	public function markAsFraudulent($opts = NULL)
	{
		$params = array('fraud_details' => array('user_report' => 'fraudulent'));
		$url    = $this->instanceUrl();
		list($response, $opts) = $this->_request('post', $url, $params, $opts);
		$this->refreshFrom($response, $opts);

		return $this;
	}

	/**
	 * @param array|string|null $opts
	 * @return Charge The updated charge.
	 */
	public function markAsSafe($opts = NULL)
	{
		$params = array('fraud_details' => array('user_report' => 'safe'));
		$url    = $this->instanceUrl();
		list($response, $opts) = $this->_request('post', $url, $params, $opts);
		$this->refreshFrom($response, $opts);

		return $this;
	}
}
