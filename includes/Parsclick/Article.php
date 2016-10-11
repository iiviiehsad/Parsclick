<?php //namespace Parsclick;
class Article extends DatabaseObject
{
	protected static $table_name = 'articles';
	protected static $db_fields  = [
		'id',
		'subject_id',
		'author_id',
		'name',
		'position',
		'visible',
		'content',
		'created_at',
	];
	public           $id;
	public           $subject_id;
	public           $author_id;
	public           $name;
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
		$sql = "
			SELECT articles.id, articles.subject_id, articles.author_id, articles.name 
			FROM articles 
				JOIN authors ON articles.author_id = authors.id
			WHERE articles.name LIKE '%{$database->escape_value($search)}%'
				OR authors.first_name LIKE '%{$database->escape_value($search)}%'
				OR authors.last_name LIKE '%{$database->escape_value($search)}%'
		";
		if ($public) {
			$sql .= 'AND articles.visible = 1';
		}
		$result_set = self::find_by_sql($sql);

		return ! empty($result_set) ? $result_set : NULL;
	}

	/**
	 * @param int $subject_id
	 * @param bool $public
	 * @return mixed
	 */
	public static function count_articles_for_subject($subject_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE subject_id = ' . $database->escape_value($subject_id);
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int $author_id
	 * @param bool $public
	 * @return mixed
	 */
	public static function count_articles_for_author($author_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE author_id = ' . $database->escape_value($author_id);
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int $subject_id
	 * @return mixed
	 */
	public static function count_invisible_articles_for_subject($subject_id = 0)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE subject_id = ' . $database->escape_value($subject_id);
		$sql .= ' AND visible = 0 ';
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int $subject_id
	 * @param bool $public
	 * @return int
	 */
	public static function num_articles_for_subject($subject_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT * ';
		$sql .= ' FROM ' . self::$table_name;
		$sql .= ' WHERE subject_id = ' . $database->escape_value($subject_id);
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$sql .= ' ORDER BY created_at ASC';
		$article_set = $database->query($sql);

		return $database->num_rows($article_set);
	}

	/**
	 * @param int $subject_id
	 * @return bool|mixed
	 */
	public static function find_default_article_for_subject($subject_id = 0)
	{
		$article_set = self::find_articles_for_subject($subject_id);

		return ! empty($article_set) ? array_shift($article_set) : FALSE;
	}

	/**
	 * @param int $subject_id
	 * @param bool $public
	 * @return array
	 */
	public static function find_articles_for_subject($subject_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT * ';
		$sql .= ' FROM ' . self::$table_name;
		$sql .= ' WHERE subject_id = ' . $database->escape_value($subject_id);
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$sql .= ' ORDER BY position DESC';

		return self::find_by_sql($database->escape_value($sql));
	}

	/**
	 * @param bool $public
	 * @return bool|mixed
	 */
	public static function find_newest_article($public = TRUE)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name;
		if ($public) {
			$sql .= ' WHERE visible = 1 ';
		}
		$sql .= ' ORDER BY created_at DESC LIMIT 1';
		$course_set = self::find_by_sql($database->escape_value($sql));

		return ! empty($course_set) ? array_shift($course_set) : FALSE;
	}

	/**
	 * @param int $author_id
	 * @return bool|mixed
	 */
	public static function find_newest_article_for_author($author_id = 0)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name . ' WHERE ' . ' author_id = ' . $database->escape_value($author_id);
		$sql .= ' ORDER BY created_at DESC LIMIT 1';
		$course_set = self::find_by_sql($database->escape_value($sql));

		return ! empty($course_set) ? array_shift($course_set) : FALSE;
	}

	/**
	 * @param int $subject_id
	 * @param bool $public
	 * @return mixed
	 */
	public static function count_recent_articles_for_subject($subject_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE subject_id = ' . $database->escape_value($subject_id);
		$sql .= ' AND created_at > NOW() - INTERVAL 2 WEEK ';
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int $author_id
	 * @param bool $public
	 * @return array
	 */
	public static function find_articles_for_author($author_id = 0, $public = TRUE)
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

		return self::find_by_sql($database->escape_value($sql));
	}

	/**
	 * @param null $date
	 * @return bool
	 */
	public function recent($date = NULL)
	{
		$date     = $date ?: $this->created_at;
		$future   = (new DateTime('+2 weeks'))->getTimestamp();
		$interval = $future - time();

		return strtotime($date) + $interval > time();
	}

	/**
	 * @return array
	 */
	public function comments()
	{
		return ArticleComment::find_comments_for_article($this->id);
	}
} // END of CLASS