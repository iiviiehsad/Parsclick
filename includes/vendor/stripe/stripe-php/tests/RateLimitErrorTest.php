<?php

namespace Stripe;

class RateLimitErrorTest extends TestCase {

	/**
	 * @expectedException Stripe\Error\RateLimit
	 */
	public function testRateLimit()
	{
		$this->mockRequest('GET', '/v1/accounts/acct_DEF', array(), $this->rateLimitErrorResponse(), 429);
		Account::retrieve('acct_DEF');
	}

	private function rateLimitErrorResponse()
	{
		return array(
			'error' => array(),
		);
	}
}
