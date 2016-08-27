<?php //namespace Parsclick;
class pagination
{
	public $current_page;
	public $per_page;
	public $total_count;

	/**
	 * pagination constructor.
	 *
	 * @param int $page
	 * @param int $per_page
	 * @param int $total_count
	 */
	public function __construct($page = 1, $per_page = 20, $total_count = 0)
	{
		$this->current_page = (int) $page;
		$this->per_page     = (int) $per_page;
		$this->total_count  = (int) $total_count;
	}

	/**
	 * @return int
	 */
	public function offset()
	{
		return ($this->current_page - 1) * $this->per_page;
	}

	/**
	 * @return bool
	 */
	public function has_previous_page()
	{
		return $this->previous_page() ? TRUE : FALSE;
	}

	/**
	 * @return int
	 */
	public function previous_page()
	{
		return $this->current_page - 1;
	}

	/**
	 * @return bool
	 */
	public function has_next_page()
	{
		return $this->next_page() <= $this->total_page() ? TRUE : FALSE;
	}

	/**
	 * @return int
	 */
	public function next_page()
	{
		return $this->current_page + 1;
	}

	/**
	 * @return float
	 */
	public function total_page()
	{
		return ceil($this->total_count / $this->per_page);
	}

} // END of CLASS