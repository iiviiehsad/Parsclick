<?php

namespace Stripe\Error;

class InvalidRequest extends Base {

	public function __construct($message, $stripeParam, $httpStatus = NULL, $httpBody = NULL, $jsonBody = NULL, $httpHeaders = NULL)
	{
		parent::__construct($message, $httpStatus, $httpBody, $jsonBody, $httpHeaders);
		$this->stripeParam = $stripeParam;
	}

	public function getStripeParam()
	{
		return $this->stripeParam;
	}
}
