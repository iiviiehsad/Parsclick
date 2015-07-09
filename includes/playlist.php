<?php

require_once(LIB_PATH . DS . 'database.php');

class Playlist extends DatabaseObject {

	protected static $table_name = "playlist";
	protected static $db_fields  = array('id', 'member_id', 'course_id');
	public           $id;
	public           $member_id;
	public           $course_id;

	/**
	 * @param $course_id int gets the course ID and pass it in the parameter
	 * @return array of playlist for the course
	 */
	public static function find_playlist_for_course($course_id) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE course_id = " . $database->escape_value($course_id);
		return static::find_by_sql($sql);
	}

	/**
	 * @param $course_id int gets the course ID and pass it in the parameter
	 * @return int number of courses for the playlist ID passed
	 */
	public static function num_courses_for_playlist($course_id) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE course_id = " . $database->escape_value($course_id);
		$article_set = $database->query($sql);
		return $database->num_rows($article_set);
	}

	/**
	 * @param $member_id int gets the members ID
	 * @return int number of playlist for the member ID passed and displays it as an integer
	 */
	public static function num_playlist_for_member($member_id) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE member_id = " . $database->escape_value($member_id);
		$article_set = $database->query($sql);
		return $database->num_rows($article_set);
	}

	/**
	 * @param $member_id int gets the members ID
	 * @return array of playlist for the members
	 */
	public static function find_playlist_for_member($member_id) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE member_id = " . $database->escape_value($member_id);
		$sql .= " ORDER BY course_id";
		return static::find_by_sql($sql);
	}
}

?>