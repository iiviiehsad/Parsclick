<?php //namespace Parsclick;
class Comment extends DatabaseObject
{
	protected static $table_name = 'comments';
	protected static $db_fields  = ['id', 'member_id', 'course_id', 'created', 'body'];
	public           $id;
	public           $member_id;
	public           $course_id;
	public           $created;
	public           $body;

	/**
	 * @param int $course_id
	 * @return mixed
	 */
	public static function count_comments_for_course($course_id = 0)
	{
		global $database;
		$sql = 'SELECT COUNT(*) FROM ' . self::$table_name;
		$sql .= ' WHERE course_id = ' . $database->escape_value($course_id);
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
	 * @param $course_id
	 * @param string $body
	 * @return bool|\Comment
	 */
	public static function make($member_id, $course_id, $body = '')
	{
		global $database;
		if ( ! empty($member_id) && ! empty($course_id) && ! empty($body)) {
			$comment            = new Comment();
			$comment->id        = (int) '';
			$comment->member_id = $database->escape_value((int) $member_id);
			$comment->course_id = $database->escape_value((int) $course_id);
			$comment->created   = strftime('%Y-%m-%d %H:%M:%S', time());
			$comment->body      = preg_replace([
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
	 * @param int $course_id
	 * @return array
	 */
	public static function find_comments_for_course($course_id = 0)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name;
		$sql .= ' WHERE course_id = ' . $database->escape_value($course_id);
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
		$sql .= ' WHERE member_id = ' . $database->escape_value($member_id);
		$sql .= ' ORDER BY created DESC';

		return self::find_by_sql($sql);
	}

	/**
	 * @param int $course_id
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public static function find_comments($course_id = 0, $limit = 0, $offset = 0)
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name .
			" WHERE course_id = {$database->escape_value($course_id)} ORDER BY created DESC LIMIT {$database->escape_value($limit)} OFFSET {$database->escape_value($offset)}";

		return self::find_by_sql($sql);
	}

	/**
	 * @param string $search
	 * @return array|null
	 */
	public static function search($search = '')
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name . ' WHERE ';
		$sql .= "body LIKE '%{$database->escape_value($search)}%'";
		$result_set = self::find_by_sql($sql);

		return ! empty($result_set) ? $result_set : NULL;
	}
} // END of CLASS