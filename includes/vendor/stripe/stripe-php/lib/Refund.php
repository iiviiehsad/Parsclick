<?php

namespace Stripe;

class Refund extends ApiResource {

	/**
	 * @param string            $id The ID of the refund to retrieve.
	 * @param array|string|null $options
	 * @return Refund
	 */
	public static function retrieve($id, $options = NULL)
	{
		return self::_retrieve($id, $options);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @return Collection of Refunds
	 */
	public static function all($params = NULL, $options = NULL)
	{
		return self::_all($params, $options);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @return Refund The created refund.
	 */
	public static function create($params = NULL, $options = NULL)
	{
		return self::_create($params, $options);
	}

	/**
	 * @param array|string|null $opts
	 * @return Refund The saved refund.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}
}
