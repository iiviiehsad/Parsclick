<?php //namespace Parsclick;

class Subject extends DatabaseObject
{
	protected static $table_name = "subjects";
	protected static $db_fields  = ['id', 'name', 'position', 'visible'];
	public           $id;
	public           $name;
	public           $position;
	public           $visible;
	
} // END of CLASS