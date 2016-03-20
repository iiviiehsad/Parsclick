<?php

namespace Stripe;

class Token extends ApiResource {

	/**
	 * @param string            $id The ID of the token to retrieve.
	 * @param array|string|null $opts
	 * @return Token
	 */
	public static function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Token The created token.
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}
}
