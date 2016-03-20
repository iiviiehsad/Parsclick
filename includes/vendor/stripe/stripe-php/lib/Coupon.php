<?php

namespace Stripe;

class Coupon extends ApiResource {

	/**
	 * @param string            $id The ID of the coupon to retrieve.
	 * @param array|string|null $opts
	 * @return Coupon
	 */
	public static function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Coupon The created coupon.
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Collection of Coupons
	 */
	public static function all($params = NULL, $opts = NULL)
	{
		return self::_all($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Coupon The deleted coupon.
	 */
	public function delete($params = NULL, $opts = NULL)
	{
		return $this->_delete($params, $opts);
	}

	/**
	 * @param array|string|null $opts
	 * @return Coupon The saved coupon.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}
}
