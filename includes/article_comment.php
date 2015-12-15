<?php require_once(LIB_PATH . DS . 'database.php');

class ArticleComment extends DatabaseObject {

	protected static $table_name = "article_comments";
	protected static $db_fields  = ['id', 'member_id', 'article_id', 'created', 'body'];
	public           $id;
	public           $member_id;
	public           $article_id;
	public           $created;
	public           $body;

	/**
	 * @param int $course_id gets the article ID
	 * @return mixed number of comments for the article
	 */
	public static function count_comments_for_article($course_id = 0)
	{
		global $database;
		$sql = "SELECT COUNT(*) FROM " . self::$table_name;
		$sql .= " WHERE article_id = " . $database->escape_value($course_id);
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);
		return array_shift($row);
	}

	/**
	 * @param        $member_id  int gets the member ID
	 * @param        $article_id int gets the article ID
	 * @param string $body       gets the message body
	 * @return bool|\Comment TRUE if comment inserted into database and FALSE if not
	 */
	public static function make($member_id, $article_id, $body = "")
	{
		if(! empty($member_id) && ! empty($article_id) && ! empty($body)) {
			$comment             = new ArticleComment();
			$comment->id         = (int)'';
			$comment->member_id  = (int)$member_id;
			$comment->article_id = (int)$article_id;
			$comment->created    = strftime("%Y-%m-%d %H:%M:%S", time());
			$comment->body       = $body;
			return $comment;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param int $article_id gets the article ID
	 * @return array of comments for the article
	 */
	public static function find_comments_for_article($article_id = 0)
	{
		global $database;
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE article_id=" . $database->escape_value($article_id);
		$sql .= " ORDER BY created DESC";
		return self::find_by_sql($sql);
	}

	/**
	 * @param int $article_id gets the course related ID
	 * @param int $limit     limits comments per page
	 * @param int $offset    the pagination offset
	 * @return array of comments in each page
	 */
	public static function find_comments($article_id = 0, $limit = 0, $offset = 0)
	{
		$sql = "SELECT * FROM " . self::$table_name . " WHERE article_id = {$article_id} LIMIT {$limit} OFFSET {$offset}";
		return self::find_by_sql($sql);
	}
}