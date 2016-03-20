<?php

namespace Stripe;

class Account extends ApiResource {

	/**
	 * @param string|null       $id
	 * @param array|string|null $opts
	 * @return Account
	 */
	public static function retrieve($id = NULL, $opts = NULL)
	{
		if( ! $opts && is_string($id) && substr($id, 0, 3) === 'sk_') {
			$opts = $id;
			$id   = NULL;
		}

		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Account
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Collection of Accounts
	 */
	public static function all($params = NULL, $opts = NULL)
	{
		return self::_all($params, $opts);
	}

	public function instanceUrl()
	{
		if($this['id'] === NULL) {
			return '/v1/account';
		} else {
			return parent::instanceUrl();
		}
	}

	/**
	 * @param array|string|null $opts
	 * @return Account
	 */
	public function save($opts = NULL)
	{
		return $this->_save();
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Account The deleted account.
	 */
	public function delete($params = NULL, $opts = NULL)
	{
		return $this->_delete($params, $opts);
	}
}
