<?php

namespace Stripe\Util;

use ArrayIterator;
use IteratorAggregate;

class Set implements IteratorAggregate {

	private $_elts;

	public function __construct($members = array())
	{
		$this->_elts = array();
		foreach($members as $item) {
			$this->_elts[$item] = TRUE;
		}
	}

	public function includes($elt)
	{
		return isset($this->_elts[$elt]);
	}

	public function add($elt)
	{
		$this->_elts[$elt] = TRUE;
	}

	public function discard($elt)
	{
		unset($this->_elts[$elt]);
	}

	public function getIterator()
	{
		return new ArrayIterator($this->toArray());
	}

	public function toArray()
	{
		return array_keys($this->_elts);
	}
}
