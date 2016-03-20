<?php

namespace Stripe;

class InvoiceItem extends ApiResource {

	/**
	 * @param string            $id The ID of the invoice item to retrieve.
	 * @param array|string|null $opts
	 * @return InvoiceItem
	 */
	public static function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return Collection of InvoiceItems
	 */
	public static function all($params = NULL, $opts = NULL)
	{
		return self::_all($params, $opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return InvoiceItem The created invoice item.
	 */
	public static function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}

	/**
	 * @param array|string|null $opts
	 * @return InvoiceItem The saved invoice item.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $opts
	 * @return InvoiceItem The deleted invoice item.
	 */
	public function delete($params = NULL, $opts = NULL)
	{
		return $this->_delete($params, $opts);
	}
}
