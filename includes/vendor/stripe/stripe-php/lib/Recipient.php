<?php

namespace Stripe;

class Recipient extends ApiResource {

	/**
	 * @param string            $id The ID of the recipient to retrieve.
	 * @param array|string|null $opts
	 * @return Recipient
	 */
	public static function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Collection of Recipients
	 */
	public static function all($params = NULL, $opts = NULL)
	{
		return self::_all($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Recipient The created recipient.
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}

	/**
	 * @param array|string|null $opts
	 * @return Recipient The saved recipient.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}

	/**
	 * @param array|null $params
	 * @return Recipient The deleted recipient.
	 */
	public function delete($params = NULL, $opts = NULL)
	{
		return $this->_delete($params, $opts);
	}

	/**
	 * @param array|null $params
	 * @return Collection of the Recipient's Transfers
	 */
	public function transfers($params = NULL)
	{
		if($params === NULL) {
			$params = array();
		}
		$params['recipient'] = $this->id;
		$transfers           = Transfer::all($params, $this->_opts);

		return $transfers;
	}
}
