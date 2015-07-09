<?php

require_once(LIB_PATH . DS . 'database.php');

/**
 * Class Category inherits from DatabaseObject Class to work with categories database table
 */
class Category extends DatabaseObject {

	protected static $table_name = "categories";
	protected static $db_fields  = array('id', 'name', 'position', 'visible');
	public           $id;
	public           $name;
	public           $position;
	public           $visible;

	/**
	 * @param bool $public TRUE is default and will display the hidden and FALSE will not display the hidden
	 * @return array of categories
	 */
	public static function find_all($public = TRUE) {
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		if($public) {
			$sql .= " WHERE visible = 1 ";
		}
		$sql .= " ORDER BY position ASC ";
		return static::find_by_sql($sql);
	}

	/**
	 * @param int  $category_id gets the category ID
	 * @param bool $public TRUE is default and will display the hidden and FALSE will not display the hidden
	 * @return bool|mixed of category sets
	 */
	public static function find_by_id($category_id = 0, $public = TRUE) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE id = " . $database->escape_value($category_id);
		if($public) {
			$sql .= " AND visible = 1 ";
		}
		$sql .= " LIMIT 1";
		$category_set = static::find_by_sql($sql);
		return !empty($category_set) ? array_shift($category_set) : FALSE;
	}
}