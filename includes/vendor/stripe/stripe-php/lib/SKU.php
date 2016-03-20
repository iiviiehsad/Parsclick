<?php

namespace Stripe;

class SKU extends ApiResource {

	/**
	 * @param string            $id The ID of the SKU to retrieve.
	 * @param array|string|null $opts
	 * @return SKU
	 */
	public static function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return SKU The created SKU.
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Collection of SKUs
	 */
	public static function all($params = NULL, $opts = NULL)
	{
		return self::_all($params, $opts);
	}

	/**
	 * @param array|string|null $opts
	 * @return SKU The saved SKU.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}
}
