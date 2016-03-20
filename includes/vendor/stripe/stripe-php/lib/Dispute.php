<?php

namespace Stripe;

class Dispute extends ApiResource {

	/**
	 * @param string            $id The ID of the dispute to retrieve.
	 * @param array|string|null $options
	 * @return Dispute
	 */
	public static function retrieve($id, $options = NULL)
	{
		return self::_retrieve($id, $options);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @return array An array of Disputes.
	 */
	public static function all($params = NULL, $options = NULL)
	{
		return self::_all($params, $options);
	}

	/**
	 * @param array|string|null $options
	 * @return Dispute The saved charge.
	 */
	public function save($options = NULL)
	{
		return $this->_save($options);
	}

	/**
	 * @param array|null        $params
	 * @param array|string|null $options
	 * @return Dispute The closed dispute.
	 */
	public function close($options = NULL)
	{
		$url = $this->instanceUrl() . '/close';
		list($response, $opts) = $this->_request('post', $url, NULL, $options);
		$this->refreshFrom($response, $opts);

		return $this;
	}
}
