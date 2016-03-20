<?php

namespace Stripe;

class Product extends ApiResource {

	/**
	 * @param string            $id The ID of the Product to retrieve.
	 * @param array|string|null $opts
	 * @return Product
	 */
	public static function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Product The created Product.
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Collection of Products
	 */
	public static function all($params = NULL, $opts = NULL)
	{
		return self::_all($params, $opts);
	}

	/**
	 * @param array|string|null $opts
	 * @return Product The saved Product.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}
}
