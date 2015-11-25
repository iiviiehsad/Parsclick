<?php
require_once(LIB_PATH . DS . 'database.php');

/**
 * File: php.ini -------------
 * file_upload = on, true, 1
 * upload_tmp_dir = NULL
 * post_max_size = 500M
 * upload_max_filesize = 500M
 * memory_limit = 128M
 * max_execution_time = 30
 * max_input_time = -1
 * ---------------------------
 * File: .htaccess -----------
 * php_value post_max_size 500M
 * php_value upload_max_filesize 500M
 */
class File extends DatabaseObject {

	protected static $table_name     = "files";
	protected static $db_fields      = array('id', 'course_id', 'name', 'type', 'size', 'description');
	public           $id;
	public           $course_id;
	public           $name;
	public           $type;
	public           $size;
	public           $description;
	private          $temp_path;
	public           $errors         = array();
	protected        $upload_dir     = "files";
	public static    $max_file_size  = 524200000; //500MB
	protected        $permittedTypes = array('application/zip');
	protected        $upload_errors  = array(
			UPLOAD_ERR_OK         => "خطایی نیست.",
			UPLOAD_ERR_INI_SIZE   => "بزرگتر از upload_max_filesize.",
			UPLOAD_ERR_FORM_SIZE  => "بزرگتر از MAX_FILE_SIZE.",
			UPLOAD_ERR_PARTIAL    => "مقداری از فایل آپلود شد.",
			UPLOAD_ERR_NO_FILE    => "فایلی نیست.",
			UPLOAD_ERR_NO_TMP_DIR => "پوشه موقت نیست.",
			UPLOAD_ERR_CANT_WRITE => "قادر به نوشتن روی دیسک نیست.",
			UPLOAD_ERR_EXTENSION  => "آپلود فایل بخاطر فرمت فایل جلوگیری شد."
	);

	/**
	 * @param $course_id int gets the course ID
	 * @return int number of files related to the course
	 */
	public static function num_files_for_course($course_id) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE course_id = " . $database->escape_value($course_id);
		$video_set = $database->query($sql);
		return $database->num_rows($video_set);
	}

	/**
	 * @param $course_id int gets the course ID
	 * @return array of files related to the course
	 */
	public static function find_files_for_course($course_id) {
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . self::$table_name;
		$sql .= " WHERE course_id = " . $database->escape_value($course_id);
		return self::find_by_sql($sql);
	}

	/**
	 * @param $filename string gets the file name
	 * @return mixed|string of sanitized file name
	 */
	public function sanitize_file_name($filename) {
		// Remove characters that could alter file path.
		// I disallowed spaces because they cause other headaches.
		// "." is allowed (e.g. "photo.jpg") but ".." is not.
		$filename = preg_replace("/([^A-Za-z0-9_\-\.]|[\.]{2})/", "", $filename);
		// basename() ensures a file name and not a path
		$filename = basename($filename);
		return $filename;
	}

	/**
	 * @param $file array gets the file details from that array
	 * @return bool TRUE if file is attached and FALSE if not
	 */
	public function attach_file($file) {
		if(!$file || empty($file) || !is_array($file)) {
			$this->errors[] = "هیچ فایلی آپلود نشد!";
			return FALSE;
		} elseif($file['error'] != 0) {
			$this->errors[] = $this->upload_errors[$file['error']];
			return FALSE;
		} elseif(!$this->checkType($file)) {
			return FALSE;
		} else {
			$this->temp_path = $file['tmp_name'];
			$this->name      = $this->sanitize_file_name($file['name']);
			$this->type      = $file['type'];
			$this->size      = $file['size'];
			return TRUE;
		}
	}

	/**
	 * @param $file array gets the file details from that array
	 * @return bool TRUE if type is matched to the permitted types property and FALSE if not
	 */
	protected function checkType($file) {
		if(in_array($file['type'], $this->permittedTypes)) {
			return TRUE;
		} else {
			$this->errors[] = $file['name'] . ' نوعی نیست که باید آپلود شود!';
			return FALSE;
		}
	}

	/**
	 * @param $file array gets the file details from that array
	 * @return string of permission to use for every file
	 */
	public function file_permissions($file) {
		// fileperms returns a numeric value
		$numeric_perms = fileperms($file);
		// but we are used to seeing the octal value
		$octal_perms = sprintf('%o', $numeric_perms);
		return substr($octal_perms, -4);
	}

	/**
	 * @override the save function in DatabaseObject Class
	 *           1- Checks the errors
	 *           2- Checks if description characters are no more than 255
	 *           3- Checks the file location whether exists or not
	 *           4- Defines the target path to upload the file
	 *           5- Checks uploaded file name if references the recent uploaded
	 *           6- Checks whether the file exist in the target path directory
	 *           7- Checks if file is moved from temporary to permanent path and sets the file permission to 644
	 * @return bool TRUE if file is moved and FALSE if not and return the error
	 */
	public function save() {
		if(!empty($this->errors)) {
			$this->errors[] = "مشکلی پیش آمد!";
			return FALSE;
		}
		if(strlen($this->description) > 255) {
			$this->errors[] = "خطا! توضیحات بیشتر ار ۲۵۵ حروف نباید باشد.";
			return FALSE;
		}
		if(empty($this->name) || empty($this->temp_path)) {
			$this->errors[] = "خطا! محل قرار دادن فایل موجود نیست.";
			return FALSE;
		}
		if($this->size > self::$max_file_size) {
			$this->errors[] = "خطا! اندازه فایل بیش از حد بزرگ است.";
			return FALSE;
		}
		$target_path = SITE_ROOT . DS . 'public_html' . DS . $this->upload_dir . DS . $this->name;
		if(!is_uploaded_file($this->temp_path)) {
			$this->errors[] = "خطا! فایل {$this->name} همان فایلی نیست که از قبل آپلود شده.";
			return FALSE;
		}
		if(file_exists($target_path)) {
			$this->errors[] = "خطا! فایل {$this->name} در سیستم با همان اسم موجود است.";
			return FALSE;
		}
		if(move_uploaded_file($this->temp_path, $target_path)) {
			if($this->create()) {
				if(chmod($target_path, 0644)) {
					$this->file_permissions($target_path);
				}
				unset($this->temp_path);
				return TRUE;
			}
		} else {
			$this->errors[] = "خطا! آپلود فایل انجام نشد, به احتمال نداشتن اجازه آپلود برای پوشه مورد نظر.";
			return FALSE;
		}
	}

	/**
	 * This function checks to see whether the file is deleted from the database first
	 * @return bool TRUE if file is deleted from the database and directory, FALSE if any of them did not happen
	 */
	public function destroy() {
		if($this->delete()) {
			$target_path = SITE_ROOT . DS . 'public_html' . DS . $this->file_path();
			return unlink($target_path) ? TRUE : FALSE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @return string dynamic uploading directory for ease of use in every View and Controller files
	 */
	public function file_path() {
		return $this->upload_dir . DS . $this->name;
	}

	/**
	 * This function is display the file size which is in bytes into KB or MB
	 * @return string file size in bytes, kilobytes or megabytes
	 */
	public function size_as_text() {
		if($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif($this->size < 1048576) {
			$size_kb = round($this->size / 1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($this->size / 1048576, 1);
			return "{$size_mb} MB";
		}
	}
}