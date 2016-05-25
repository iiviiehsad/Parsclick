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
		'created_at'
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
	 * @param string $search gets the search query
	 * @param bool   $public sets TRUE if article is visible and FALSE if article is not visible
	 * @return array|null the result
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
	 * @param int  $subject_id gets the subject ID
	 * @param bool $public     sets TRUE if article is visible and FALSE if article is not visible
	 * @return mixed counts the number of articles for subject given
	 */
	public static function count_articles_for_subject($subject_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE subject_id = ' . $subject_id;
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
		$sql .= ' WHERE subject_id = ' . $subject_id;
		$sql .= ' AND visible = 0 ';
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param      $subject_id integer gets the subject ID
	 * @param bool $public
	 * @return int number of articles for subject given
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
		$sql .= ' ORDER BY position ASC';
		$article_set = $database->query($sql);

		return $database->num_rows($article_set);
	}

	/**
	 * @param int $subject_id gets the subject ID
	 * @return bool|mixed set of articles
	 */
	public static function find_default_article_for_subject($subject_id = 0)
	{
		$article_set = self::find_articles_for_subject($subject_id);

		return ! empty($article_set) ? array_shift($article_set) : FALSE;
	}

	/**
	 * @param int  $subject_id integer gets the subject ID
	 * @param bool $public     sets TRUE if subject is visible and FALSE if subject is not visible
	 * @return array of articles for subjects given
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

		return self::find_by_sql($sql);
	}

	/**
	 * @param bool|TRUE $public sets TRUE if subject is visible and FALSE if subject is not visible
	 * @return bool|mixed newest article
	 */
	public static function find_newest_article($public = TRUE)
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
	 * @param int  $subject_id gets the subject ID
	 * @param bool $public     if it is visible to public
	 * @return mixed number of recent article(s) for subject
	 */
	public static function count_recent_articles_for_subject($subject_id = 0, $public = TRUE)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE subject_id = ' . $subject_id;
		$sql .= ' AND created_at > NOW() - INTERVAL 2 WEEK ';
		if ($public) {
			$sql .= ' AND visible = 1 ';
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * Finds articles for specific author
	 *
	 * @param int       $author_id
	 * @param bool|TRUE $public
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
		$sql .= ' ORDER BY position DESC';

		return self::find_by_sql($sql);
	}

	/**
	 * @param null $date
	 * @return bool TRUE if article is new and FALSE if old
	 */
	public function recent($date = NULL)
	{
		$date     = $date ?: $this->created_at;
		$future   = (new DateTime('+2 weeks'))->getTimestamp();
		$interval = $future - time();

		return strtotime($date) + $interval > time();
	}

	/**
	 * @param null $date DateTime
	 * @return bool
	 */
	public function updated($date = NULL)
	{
		// $date     = $date ?: $this->updated_at;
		$future   = (new DateTime('+1 day'))->getTimestamp();
		$interval = $future - time();

		return strtotime($date) + $interval > time();
	}

	/**
	 * Finds the comments for the course by using the function find_comments_for_article
	 *
	 * @return array
	 */
	public function comments()
	{
		return ArticleComment::find_comments_for_article($this->id);
	}

} // END of CLASS