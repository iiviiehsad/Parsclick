<?php require_once(LIB_PATH . DS . 'database.php');

class Course extends DatabaseObject {

	protected static $table_name = "courses";
	protected static $db_fields  = [
			'id',
			'category_id',
			'author_id',
			'name',
			'youtubePlaylist',
			'file_link',
			'position',
			'visible',
			'content',
			'created_at'
	];
	public           $id;
	public           $category_id;
	public           $author_id;
	public           $name;
	public           $youtubePlaylist;
	public           $file_link;
	public           $position;
	public           $visible;
	public           $content;
	public           $created_at;
	private          $time;

	/**
	 * @param int  $course_id gets the course ID
	 * @param bool $public    TRUE is default and will display the hidden and FALSE will not display the hidden
	 * @return bool|mixed set of courses
	 */
	public static function find_by_id($course_id = 0, $public = TRUE)
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE id = " . $database->escape_value($course_id);
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$sql .= " LIMIT 1";
		$course_set = self::find_by_sql($sql);

		return ! empty($course_set) ? array_shift($course_set) : FALSE;
	}

	/**
	 * @param bool $public TRUE is default and will display the hidden and FALSE will not display the hidden
	 * @return array of courses
	 */
	public static function find_all($public = TRUE)
	{
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		if($public) {
			$sql .= " WHERE visible = 1 ";
		}
		$sql .= " ORDER BY position DESC ";

		return self::find_by_sql($sql);
	}

	/**
	 * @param string $search gets the search query
	 * @param bool   $public TRUE is default and will display the hidden and FALSE will not display the hidden
	 * @return array|null result
	 */
	public static function search($search = "", $public = TRUE)
	{
		global $database;
		$sql = "SELECT * FROM " . self::$table_name . " WHERE ";
		$sql .= "name LIKE '%{$database->escape_value($search)}%'";
		if($public) {
			$sql .= " AND visible = 1";
		}
		$result_set = self::find_by_sql($sql);

		return ! empty($result_set) ? $result_set : NULL;
	}

	/**
	 * @param int  $category_id gets the category ID
	 * @param bool $public      TRUE is default and will display the hidden and FALSE will not display the hidden
	 * @return mixed number of courses for the category
	 */
	public static function count_courses_for_category($category_id = 0, $public = TRUE)
	{
		global $database;
		$sql = "SELECT COUNT(*) FROM " . self::$table_name;
		$sql .= " WHERE category_id = " . $category_id;
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int  $category_id get the category ID
	 * @param bool $public      TRUE is default and will display the hidden and FALSE will not display the hidden
	 * @return array of courses
	 */
	public static function find_courses_for_category($category_id = 0, $public = TRUE)
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE category_id = " . $database->escape_value($category_id);
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$sql .= " ORDER BY position DESC";

		return self::find_by_sql($sql);
	}

	/**
	 * @param int $category_id gets the category ID
	 * @return int number of courses for category ID given
	 */
	public static function num_courses_for_category($category_id = 0)
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE category_id = " . $database->escape_value($category_id);
		$sql .= " ORDER BY position ASC";
		$article_set = $database->query($sql);

		return $database->num_rows($article_set);
	}

	/**
	 * @param int $category_id gets the category ID
	 * @return bool|mixed set of articles
	 */
	public static function find_default_course_for_category($category_id = 0)
	{
		$article_set = self::find_courses_for_category($category_id);

		return ! empty($article_set) ? array_shift($article_set) : FALSE;
	}

	/**
	 * @return bool TRUE if course is new and FALSE if old
	 */
	public function recent()
	{
		$this->time = 60 * 60 * 24 * 7 * 2; // 2 weeks
		if(strtotime($this->created_at) + $this->time > time()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param int  $category_id
	 * @param bool $public if it is visible to public
	 * @return mixed number of recent course(s) for category
	 */
	public static function count_recent_course_for_category($category_id = 0, $public = TRUE)
	{
		global $database;
		$sql = "SELECT COUNT(*) FROM " . self::$table_name;
		$sql .= " WHERE category_id = " . $category_id;
		$sql .= " AND created_at > NOW() - INTERVAL 2 WEEK ";
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * Finds the comments for the course by using the function find_comments_for_course
	 *
	 * @return array of comments for the course
	 */
	public function comments()
	{
		return Comment::find_comments_for_course($this->id);
	}

	/**
	 * @param bool|TRUE $public sets TRUE if subject is visible and FALSE if subject is not visible
	 * @return bool|mixed newest course
	 */
	public static function find_newest_course($public = TRUE)
	{
		$sql = "SELECT * FROM " . self::$table_name;
		if($public) {
			$sql .= " WHERE visible = 1 ";
		}
		$sql .= " ORDER BY id DESC LIMIT 1";
		$course_set = self::find_by_sql($sql);

		return ! empty($course_set) ? array_shift($course_set) : FALSE;
	}
}