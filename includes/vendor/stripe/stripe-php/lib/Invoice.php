<?php

namespace Stripe;

class Invoice extends ApiResource {

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Invoice The created invoice.
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}

	/**
	 * @param string            $id The ID of the invoice to retrieve.
	 * @param array|string|null $opts
	 * @return Invoice
	 */
	public static function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Collection of Invoices
	 */
	public static function all($params = NULL, $opts = NULL)
	{
		return self::_all($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Invoice The upcoming invoice.
	 */
	public static function upcoming($params = NULL, $opts = NULL)
	{
		$url = static::classUrl() . '/upcoming';
		list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
		$obj = Util\Util::convertToStripeObject($response->json, $opts);
		$obj->setLastResponse($response);

		return $obj;
	}

	/**
	 * @param array|string|null $opts
	 * @return Invoice The saved invoice.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}

	/**
	 * @return Invoice The paid invoice.
	 */
	public function pay($opts = NULL)
	{
		$url = $this->instanceUrl() . '/pay';
		list($response, $opts) = $this->_request('post', $url, NULL, $opts);
		$this->refreshFrom($response, $opts);

		return $this;
	}
}
