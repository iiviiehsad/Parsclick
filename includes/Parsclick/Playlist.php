<?php //namespace Parsclick;

class Playlist extends DatabaseObject
{
	protected static $table_name = 'playlist';
	protected static $db_fields  = ['id', 'member_id', 'course_id'];
	public           $id;
	public           $member_id;
	public           $course_id;

	/**
	 * @param $course_id int gets the course ID and pass it in the parameter
	 * @param $member_id int gets the member ID and pass it in the parameter
	 * @return int number of courses for the member
	 */
	public static function courses_playlist_for_member($course_id, $member_id)
	{
		global $database;
		$sql = 'SELECT * ';
		$sql .= ' FROM ' . self::$table_name;
		$sql .= ' WHERE course_id = ' . $database->escape_value($course_id);
		$sql .= ' AND member_id = ' . $database->escape_value($member_id);
		$result_array = self::find_by_sql($sql);

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	/**
	 * @param $member_id int gets the members ID
	 * @return array of playlist for the members
	 */
	public static function find_playlist_for_member($member_id)
	{
		global $database;
		$sql = 'SELECT * ';
		$sql .= ' FROM ' . self::$table_name;
		$sql .= ' WHERE member_id = ' . $database->escape_value($member_id);
		$sql .= ' ORDER BY course_id';

		return self::find_by_sql($sql);
	}

	/**
	 * @param $member_id int gets the members ID
	 * @return mixed number of playlist for every member
	 */
	public static function count_playlist_for_member($member_id)
	{
		global $database;
		$sql        = 'SELECT COUNT(*) FROM ' . self::$table_name . ' WHERE member_id = ' . $member_id;
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

} // END of CLASS