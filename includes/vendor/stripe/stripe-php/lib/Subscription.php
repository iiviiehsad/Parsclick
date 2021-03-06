<?php

namespace Stripe;

class Subscription extends ApiResource {

	/**
	 * @param array|null $params
	 * @return Subscription The deleted subscription.
	 */
	public function cancel($params = NULL, $opts = NULL)
	{
		return $this->_delete($params, $opts);
	}

	/**
	 * @param array|string|null $opts
	 * @return Subscription The saved subscription.
	 */
	public function save($opts = NULL)
	{
		return $this->_save($opts);
	}

	/**
	 * @return Subscription The updated subscription.
	 */
	public function deleteDiscount()
	{
		$url = $this->instanceUrl() . '/discount';
		list($response, $opts) = $this->_request('delete', $url);
		$this->refreshFrom(array('discount' => NULL), $opts, TRUE);
	}

	/**
	 * @return string The API URL for this Stripe subscription.
	 */
	public function instanceUrl()
	{
		$id       = $this['id'];
		$customer = $this['customer'];
		if( ! $id) {
			throw new Error\InvalidRequest("Could not determine which URL to request: " . "class instance has invalid ID: $id", NULL);
		}
		$id       = Util\Util::utf8($id);
		$customer = Util\Util::utf8($customer);

		$base         = Customer::classUrl();
		$customerExtn = urlencode($customer);
		$extn         = urlencode($id);

		return "$base/$customerExtn/subscriptions/$extn";
	}
}
