<?php

class pagination {

	public $current_pgae;
	public $per_pgae;
	public $total_count;

	/**
	 * @param int $page        is the page where you want the pagination to start
	 * @param int $per_page    number of items in every pagination the default is 20 but default can be changed
	 * @param int $total_count number of all items
	 */
	public function __construct($page = 1, $per_page = 20, $total_count = 0) {
		$this->current_pgae = (int)$page;
		$this->per_pgae     = (int)$per_page;
		$this->total_count  = (int)$total_count;
	}

	/**
	 * Assuming 20 items per page:
	 * page 1 has an offset of 0   (1-1) * 20 = 0
	 * page 2 has an offset of 20  (2-1) * 20 = 20
	 * in other words, page 2 starts with item 21
	 * @return int offset
	 */
	public function offset() {
		return ($this->current_pgae - 1) * $this->per_pgae;
	}

	/**
	 * total_count divided by per_page:
	 * @return float total number of pages
	 */
	public function total_page() {
		return ceil($this->total_count / $this->per_pgae);
	}

	/**
	 * @return int previous page by getting current page and subtracting it by one
	 */
	public function previous_page() {
		return $this->current_pgae - 1;
	}

	/**
	 * @return int next page by getting current page and adding it by one
	 */
	public function next_page() {
		return $this->current_pgae + 1;
	}

	/**
	 * @return bool TRUE if we have previous page and FALSE if not
	 */
	public function has_previous_page() {
		return $this->previous_page() >= 1 ? TRUE : FALSE;
	}

	/**
	 * @return bool TRUE if we have next page and FALSE if not
	 */
	public function has_next_page() {
		return $this->next_page() <= $this->total_page() ? TRUE : FALSE;
	}
}