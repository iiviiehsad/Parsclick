<?php

/**
 * File: php.ini -------------
 * file_upload          = on, true, 1
 * upload_tmp_dir       = NULL
 * post_max_size        = 500M
 * upload_max_filesize  = 500M
 * memory_limit         = 128M
 * max_execution_time   = 30
 * max_input_time       = -1
 * File: .htaccess -----------
 * php_value post_max_size 500M
 * php_value upload_max_filesize 500M
 */
class File extends DatabaseObject
{
	public static    $max_file_size  = 33554432;
	protected static $table_name     = 'files';
	protected static $db_fields      = ['id', 'course_id', 'name', 'type', 'size', 'description'];
	public           $id;
	public           $course_id;
	public           $name;
	public           $type;
	public           $size;
	public           $description;
	public           $errors         = [];
	protected        $upload_dir     = 'files';
	protected        $permittedTypes = ['application/zip']; //32MB
	protected        $upload_errors  = [
		UPLOAD_ERR_OK         => 'خطایی نیست.',
		UPLOAD_ERR_INI_SIZE   => 'فایل بسیار بزرگ است.',
		UPLOAD_ERR_FORM_SIZE  => 'فایل بزرگ است.',
		UPLOAD_ERR_PARTIAL    => 'مقداری از فایل آپلود شد.',
		UPLOAD_ERR_NO_FILE    => 'فایلی نیست.',
		UPLOAD_ERR_NO_TMP_DIR => 'پوشه موقت نیست.',
		UPLOAD_ERR_CANT_WRITE => 'قادر به نوشتن روی دیسک نیست.',
		UPLOAD_ERR_EXTENSION  => 'آپلود فایل بخاطر فرمت فایل جلوگیری شد.',
	];
	private          $temp_path;

	/**
	 * @param $course_id
	 * @return int
	 */
	public static function num_files_for_course($course_id)
	{
		global $database;
		$sql       = 'SELECT * ';
		$sql       .= ' FROM ' . self::$table_name;
		$sql       .= ' WHERE course_id = ' . $database->escape_value($course_id);
		$video_set = $database->query($sql);

		return $database->num_rows($video_set);
	}

	/**
	 * @param $course_id
	 * @return array
	 */
	public static function find_files_for_course($course_id)
	{
		global $database;
		$sql = 'SELECT * ';
		$sql .= ' FROM ' . self::$table_name;
		$sql .= ' WHERE course_id = ' . $database->escape_value($course_id);

		return self::find_by_sql($sql);
	}

	/**
	 * @param $file
	 * @return bool
	 */
	public function attach_file($file)
	{
		if ( ! $file || empty($file) || ! is_array($file)) {
			$this->errors[] = 'هیچ فایلی آپلود نشد!';

			return FALSE;
		}

		if ($file['error'] != 0) {
			$this->errors[] = $this->upload_errors[$file['error']];

			return FALSE;
		}

		if ( ! $this->checkType($file)) {
			return FALSE;
		}

		$this->temp_path = $file['tmp_name'];
		$this->name      = $this->sanitize_file_name($file['name']);
		$this->type      = $file['type'];
		$this->size      = $file['size'];

		return TRUE;
	}

	/**
	 * @param $filename
	 * @return mixed|string
	 */
	public function sanitize_file_name($filename)
	{
		// Remove characters that could alter file path.
		// I disallowed spaces because they cause other headaches.
		// "." is allowed (e.g. "photo.jpg") but ".." is not.
		$filename = preg_replace("/([^A-Za-z0-9_\-\.]|[\.]{2})/", '', $filename);
		// basename() ensures a file name and not a path
		$filename = basename($filename);

		return $filename;
	}

	/**
	 * @return bool
	 */
	public function save()
	{
		if ( ! empty($this->errors)) {
			$this->errors[] = 'مشکلی پیش آمد!';

			return FALSE;
		}
		if (strlen($this->description) > 255) {
			$this->errors[] = 'خطا! توضیحات بیشتر ار ۲۵۵ حروف نباید باشد.';

			return FALSE;
		}
		if (empty($this->name) || empty($this->temp_path)) {
			$this->errors[] = 'خطا! محل قرار دادن فایل موجود نیست.';

			return FALSE;
		}
		if ($this->size > self::$max_file_size) {
			$this->errors[] = 'خطا! اندازه فایل بیش از حد بزرگ است.';

			return FALSE;
		}
		$target_path = PUB_PATH . DS . $this->upload_dir . DS . $this->name;
		if ( ! is_uploaded_file($this->temp_path)) {
			$this->errors[] = "خطا! فایل {$this->name} همان فایلی نیست که از قبل آپلود شده.";

			return FALSE;
		}
		if (file_exists($target_path)) {
			$this->errors[] = "خطا! فایل {$this->name} در سیستم با همان اسم موجود است.";

			return FALSE;
		}
		if (move_uploaded_file($this->temp_path, $target_path)) {
			if ($this->create()) {
				if (chmod($target_path, 0644)) {
					$this->file_permissions($target_path);
				}
				unset($this->temp_path);

				return TRUE;
			}
		} else {
			$this->errors[] = 'خطا! آپلود فایل انجام نشد, به احتمال نداشتن اجازه آپلود برای پوشه مورد نظر.';

			return FALSE;
		}
	}

	/**
	 * @param $file
	 * @return string
	 */
	public function file_permissions($file)
	{
		// fileperms returns a numeric value
		$numeric_perms = fileperms($file);
		// but we are used to seeing the octal value
		$octal_perms = sprintf('%o', $numeric_perms);

		return substr($octal_perms, -4);
	}

	/**
	 * @return bool
	 */
	public function destroy()
	{
		if ($this->delete()) {
			$target_path = PUB_PATH . DS . $this->file_path();

			return unlink($target_path) ? TRUE : FALSE;
		}

		return FALSE;
	}

	/**
	 * @return string
	 */
	public function file_path()
	{
		return $this->upload_dir . DS . $this->name;
	}

	/**
	 * @return string
	 */
	public function size_as_text()
	{
		if ($this->size < 1024) {
			return "{$this->size} bytes";
		}

		if ($this->size < 1048576) {
			$size_kb = round($this->size / 1024);

			return "{$size_kb} KB";
		}

		$size_mb = round($this->size / 1048576, 1);

		return "{$size_mb} MB";
	}

	/**
	 * @param $file
	 * @return bool
	 */
	protected function checkType($file)
	{
		if (in_array($file['type'], $this->permittedTypes, FALSE)) {
			return TRUE;
		}

		$this->errors[] = $file['name'] . ' نوعی نیست که باید آپلود شود!';

		return FALSE;
	}
}