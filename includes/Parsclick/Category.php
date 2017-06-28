<?php

class Category extends DatabaseObject
{
	protected static $table_name = 'categories';
	protected static $db_fields  = ['id', 'name', 'position', 'visible'];
	public           $id;
	public           $name;
	public           $position;
	public           $visible;
}