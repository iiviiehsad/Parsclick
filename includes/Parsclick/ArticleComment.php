<?php //namespace Parsclick;

class ArticleComment extends DatabaseObject
{
	protected static $table_name = 'article_comments';
	protected static $db_fields  = ['id', 'member_id', 'article_id', 'created', 'body'];
	public           $id;
	public           $member_id;
	public           $article_id;
	public           $created;
	public           $body;

	/**
	 * @param int $article_id
	 * @return mixed
	 */
	public static function count_comments_for_article($article_id = 0)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE article_id = ' . $database->escape_value($article_id);
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param int $member_id
	 * @return mixed
	 */
	public static function count_comments_for_member($member_id = 0)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE member_id = ' . $database->escape_value($member_id);
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	/**
	 * @param $member_id
	 * @param $article_id
	 * @param string $body
	 * @return \ArticleComment|bool
	 */
	public static function make($member_id, $article_id, $body = '')
	{
		global $database;
		if ( ! empty($member_id) && ! empty($article_id) && ! empty($body)) {
			$comment             = new ArticleComment();
			$comment->id         = $database->escape_value((int) '');
			$comment->member_id  = $database->escape_value((int) $member_id);
			$comment->article_id = $database->escape_value((int) $article_id);
			$comment->created    = strftime('%Y-%m-%d %H:%M:%S', time());
			$comment->body       = preg_replace([
				'/`(.*?)`/',
				'/\*(.*?)\*/',
				'/(^|\s)@([a-z0-9_]+)/i',
			], [
				'<code>$1</code>',
				'<strong>$1</strong>',
				'$1<a href="/profile?q=$2">@$2</a>',
			], $body);

			return $comment;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param int $article_id
	 * @return array
	 */
	public static function find_comments_for_article($article_id = 0)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name;
		$sql .= ' WHERE article_id=' . $database->escape_value($article_id);
		$sql .= ' ORDER BY created DESC';

		return self::find_by_sql($sql);
	}

	/**
	 * @param int $member_id
	 * @return array
	 */
	public static function find_comments_for_member($member_id = 0)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name;
		$sql .= ' WHERE member_id=' . $database->escape_value($member_id);
		$sql .= ' ORDER BY created DESC';

		return self::find_by_sql($sql);
	}

	/**
	 * @param int $article_id
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public static function find_comments($article_id = 0, $limit = 0, $offset = 0)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name .
			" WHERE article_id = {$database->escape_value($article_id)} ORDER BY created DESC LIMIT {$database->escape_value($limit)} OFFSET {$database->escape_value($offset)}";

		return self::find_by_sql($sql);
	}
} // END of CLASS