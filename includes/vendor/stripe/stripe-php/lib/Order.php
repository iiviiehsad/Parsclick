<?php

namespace Stripe;

class Order extends ApiResource {

	/**
	 * @param string            $id The ID of the Order to retrieve.
	 * @param array|string|null $opts
	 * @return Order
	 */
	public static function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Order The created Order.
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Collection of Orders
	 */
	public static function all($params = NULL, $opts = NULL)
	{
		return self::_all($params, $opts);
	}

	/**
	 * @param array|string|null $opts
	 * @return Order The saved Order.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}

	/**
	 * @return Order The paid order.
	 */
	public function pay($params = NULL, $opts = NULL)
	{
		$url = $this->instanceUrl() . '/pay';
		list($response, $opts) = $this->_request('post', $url, $params, $opts);
		$this->refreshFrom($response, $opts);

		return $this;
	}
}
