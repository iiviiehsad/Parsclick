<?php //namespace Parsclick;
class Course extends DatabaseObject
{
	protected static $table_name = 'courses';
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
		'created_at',
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

	/**
	 * @param string $search
	 * @param bool $public
	 * @return array|null
	 */
	public static function search($search = '', $public = TRUE)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name . ' WHERE ';
		$sql .= "name LIKE '%{$database->escape_value($search)}%'";
		if ($public) {
			$sql .= ' AND visible = 1';
		}
		$result_set = self::find_by_sql($sql);

		return ! empty($result_set) ? $result_set : NULL;
	}

	/**
	 * @param int $category_id
	 * @param bool $public
	 * @return mixed
	 */
	public static function count_courses_for_category($category_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE category_id = ' . $database->escape_value($category_id);
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int $category_id
	 * @return int
	 */
	public static function num_courses_for_category($category_id = 0)
	{
		global $database;
		$sql = 'SELECT * ';
		$sql .= ' FROM ' . self::$table_name;
		$sql .= ' WHERE category_id = ' . $database->escape_value($category_id);
		$sql .= ' ORDER BY position ASC';
		$article_set = $database->query($sql);

		return $database->num_rows($article_set);
	}

	/**
	 * @param int $category_id
	 * @return bool|mixed
	 */
	public static function find_default_course_for_category($category_id = 0)
	{
		global $database;
		$article_set = self::find_courses_for_category($database->escape_value($category_id));

		return ! empty($article_set) ? array_shift($article_set) : FALSE;
	}

	/**
	 * @param int $category_id
	 * @param bool $public
	 * @return array
	 */
	public static function find_courses_for_category($category_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT * ';
		$sql .= ' FROM ' . self::$table_name;
		$sql .= ' WHERE category_id = ' . $database->escape_value($category_id);
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$sql .= ' ORDER BY position DESC';

		return self::find_by_sql($sql);
	}

	/**
	 * @param bool $public
	 * @return bool|mixed
	 */
	public static function find_newest_course($public = TRUE)
	{
		$sql = 'SELECT * FROM ' . self::$table_name;
		if ($public) {
			$sql .= ' WHERE visible = 1 ';
		}
		$sql .= ' ORDER BY id DESC LIMIT 1';
		$course_set = self::find_by_sql($sql);

		return ! empty($course_set) ? array_shift($course_set) : FALSE;
	}

	/**
	 * @param int $author_id
	 * @return bool|mixed
	 */
	public static function find_newest_course_for_author($author_id = 0)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name . ' WHERE ' . ' author_id = ' . $database->escape_value($author_id);
		$sql .= ' ORDER BY created_at DESC LIMIT 1';
		$course_set = self::find_by_sql($database->escape_value($sql));

		return ! empty($course_set) ? array_shift($course_set) : FALSE;
	}

	/**
	 * @param int $category_id
	 * @param bool $public
	 * @return mixed
	 */
	public static function count_recent_course_for_category($category_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE category_id = ' . $database->escape_value($category_id);
		$sql .= ' AND created_at > NOW() - INTERVAL 8 WEEK ';
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int $category_id
	 * @return mixed
	 */
	public static function count_invisible_courses_for_category($category_id = 0)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE category_id = ' . $database->escape_value($category_id);
		$sql .= ' AND visible = 0 ';
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int $author_id
	 * @param bool $public
	 * @return array
	 */
	public static function find_courses_for_author($author_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT * ';
		$sql .= ' FROM ' . self::$table_name;
		$sql .= ' WHERE author_id = ' . $database->escape_value($author_id);
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		if ( ! $public) {
			$sql .= ' AND visible = 0 ';
		}
		$sql .= ' ORDER BY created_at DESC';

		return self::find_by_sql($sql);
	}

	/**
	 * @param null $date
	 * @return bool
	 */
	public function recent($date = NULL)
	{
		$date     = $date ?: $this->created_at;
		$future   = (new DateTime('+8 weeks'))->getTimestamp();
		$interval = $future - time();

		return strtotime($date) + $interval > time();
	}

	/**
	 * @return array
	 */
	public function comments()
	{
		return Comment::find_comments_for_course($this->id);
	}
} // END of CLASS