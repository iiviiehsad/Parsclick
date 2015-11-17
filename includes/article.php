<?php
require_once(LIB_PATH . DS . 'database.php');

/**
 * Class Article inherits from DatabaseObject Class to work with articles database table
 */
class Article extends DatabaseObject {

	protected static $table_name = "articles";
	protected static $db_fields  = array('id', 'subject_id', 'author_id', 'name', 'position', 'visible', 'content');
	public           $id;
	public           $subject_id;
	public           $author_id;
	public           $name;
	public           $position;
	public           $visible;
	public           $content;

	/**
	 * @param int  $subject_id gets the subject ID
	 * @param bool $public     sets TRUE if article is visible and FALSE if article is not visible
	 * @return mixed counts the number of articles for subject given
	 */
	public static function count_articles_for_subject($subject_id = 0, $public = TRUE) {
		global $database;
		$sql = "SELECT COUNT(*) FROM " . static::$table_name;
		$sql .= " WHERE subject_id = " . $subject_id;
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);
		return array_shift($row);
	}

	/**
	 * @param string $search gets the search query
	 * @param bool   $public sets TRUE if article is visible and FALSE if article is not visible
	 * @return array|null the result
	 */
	public static function search($search = "", $public = TRUE) {
		global $database;
		$sql = "SELECT * FROM " . self::$table_name . " WHERE ";
		$sql .= "name LIKE '%{$database->escape_value($search)}%'";
		if($public) {
			$sql .= " AND visible = 1";
		}
		$result_set = self::find_by_sql($sql);
		return !empty($result_set) ? $result_set : NULL;
	}

	/**
	 * @param int  $article_id gets the article ID
	 * @param bool $public     sets TRUE if subject is visible and FALSE if subject is not visible
	 * @return bool|mixed set of articles
	 */
	public static function find_by_id($article_id = 0, $public = TRUE) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE id = " . $database->escape_value($article_id);
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$sql .= " LIMIT 1";
		$article_set = static::find_by_sql($sql);
		return !empty($article_set) ? array_shift($article_set) : FALSE;
	}

	/**
	 * @param int  $subject_id integer gets the subject ID
	 * @param bool $public     sets TRUE if subject is visible and FALSE if subject is not visible
	 * @return array of articles for subjects given
	 */
	public static function find_articles_for_subject($subject_id = 0, $public = TRUE) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE subject_id = " . $database->escape_value($subject_id);
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$sql .= " ORDER BY position DESC";
		return static::find_by_sql($sql);
	}

	/**
	 * @param $subject_id integer gets the subject ID
	 * @return integer number of articles for subject given
	 */
	public static function num_articles_for_subject($subject_id = 0) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE subject_id = " . $database->escape_value($subject_id);
		$sql .= " ORDER BY position ASC";
		$article_set = $database->query($sql);
		return $database->num_rows($article_set);
	}

	/**
	 * @param int $subject_id gets the subject ID
	 * @return bool|mixed set of articles
	 */
	public static function find_default_article_for_subject($subject_id = 0) {
		$article_set = self::find_articles_for_subject($subject_id);
		return !empty($article_set) ? array_shift($article_set) : FALSE;
	}
}