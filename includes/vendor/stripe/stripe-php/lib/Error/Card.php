<?php

namespace Stripe\Error;

class Card extends Base {

	public function __construct($message, $stripeParam, $stripeCode, $httpStatus, $httpBody, $jsonBody, $httpHeaders = NULL)
	{
		parent::__construct($message, $httpStatus, $httpBody, $jsonBody, $httpHeaders);
		$this->stripeParam = $stripeParam;
		$this->stripeCode  = $stripeCode;
	}

	public function getStripeCode()
	{
		return $this->stripeCode;
	}

	public function getStripeParam()
	{
		return $this->stripeParam;
	}
}
