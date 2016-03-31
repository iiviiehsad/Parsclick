<?php require_once(LIB_PATH . DS . 'database.php');

class Subject extends DatabaseObject
{
	protected static $table_name = "subjects";
	protected static $db_fields  = ['id', 'name', 'position', 'visible'];
	public           $id;
	public           $name;
	public           $position;
	public           $visible;

	/**
	 * @param bool $public TRUE by default means we don't want hidden subject to be visible to the users and FALSE the
	 *                     otherwise
	 * @return array of all subjects
	 */
	public static function find_all($public = TRUE)
	{
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		if($public) {
			$sql .= " WHERE visible = 1 ";
		}
		$sql .= " ORDER BY position ASC ";

		return self::find_by_sql($sql);
	}

	/**
	 * @param int  $subject_id taken form the user or any function
	 * @param bool $public     TRUE by default means we don't want hidden subject to be visible to the users and FALSE
	 *                         the otherwise
	 * @return bool|mixed TRUE and one subject only from database and FALSE if found nothing
	 */
	public static function find_by_id($subject_id = 0, $public = TRUE)
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE id = " . $database->escape_value($subject_id);
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$sql .= " LIMIT 1";
		$subject_set = self::find_by_sql($sql);

		return ! empty($subject_set) ? array_shift($subject_set) : FALSE;
	}

} // END of CLASS