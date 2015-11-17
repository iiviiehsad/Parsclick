<?php

/**
 * Class Session to help work with Sessions
 * In our case, primarily to manage logging members in and out
 * Keep in mind when working with sessions that it is generally
 * inadvisable to store DB-related objects in sessions but instead
 * we can store for instance members_id in sessions or database columns
 */
class Session {

	public  $id;
	public  $message;
	private $logged_in        = FALSE;
	private $admin_logged_in  = FALSE;
	private $author_logged_in = FALSE;

	function __construct() {
		session_start();
		$this->check_message();
		$this->check_login();
		if($this->logged_in) {
			$this->allowed_get_params(['subject', 'article', 'category', 'course', 'q']);
			// actions to take right away if member is logged in
		} elseif($this->admin_logged_in) {
			// actions to take right away if admin is logged in
		} elseif($this->author_logged_in) {
			// actions to take right away if author is logged in
		} else {
			// actions to take right away if any user is not logged in
		}
	}

	public function is_logged_in() {
		return (isset($this->logged_in) && $this->logged_in);
	}

	public function confirm_logged_in() {
		if(!$this->is_logged_in()) {
			$this->logout();
			redirect_to("login");
		}
	}

	public function is_admin_logged_in() {
		return (isset($this->admin_logged_in) && $this->admin_logged_in);
	}

	public function confirm_admin_logged_in() {
		if(!$this->is_admin_logged_in()) {
			$this->logout();
			redirect_to("index.php");
		}
	}

	public function is_author_logged_in() {
		return (isset($this->author_logged_in) && $this->author_logged_in);
	}

	public function confirm_author_logged_in() {
		if(!$this->is_author_logged_in()) {
			$this->logout();
			redirect_to("index.php");
		}
	}

	public function login($member) {
		// database should find member based on username/password
		if($member) {
			$this->id        = $_SESSION['id'] = $member->id;
			$this->logged_in = TRUE;
		}
	}

	public function admin_login($admin) {
		if($admin) {
			$this->id              = $_SESSION['admin_id'] = $admin->id;
			$this->admin_logged_in = TRUE;
		}
	}

	public function author_login($author) {
		if($author) {
			$this->id               = $_SESSION['author_id'] = $author->id;
			$this->author_logged_in = TRUE;
		}
	}

	public function logout() {
		session_unset();
		session_destroy();
	}

	public function message($msg = "") {
		if(!empty($msg)) {
			$_SESSION['message'] = $msg;
		} else {
			return $this->message;
		}
	}

	public function csrf_token_tag() {
		$token = $this->create_csrf_token();
		return "<input type=\"hidden\" name=\"csrf_token\" value=\"" . $token . "\">";
	}

	public function csrf_token_is_valid() {
		if(isset($_POST['csrf_token'])) {
			$user_token   = $_POST['csrf_token'];
			$stored_token = $_SESSION['csrf_token'];
			return $user_token === $stored_token;
		} else {
			return FALSE;
		}
	}

	public function die_on_csrf_token_failure() {
		if(!$this->csrf_token_is_valid()) {
			die("CSRF token validation failed.");
		}
	}

	public function csrf_token_is_recent() {
		$max_elapsed = 60 * 60 * 24; // 1 day
		if(isset($_SESSION['csrf_token_time'])) {
			$stored_time = $_SESSION['csrf_token_time'];
			return ($stored_time + $max_elapsed) >= time();
		} else {
			$this->destroy_csrf_token();
			return FALSE;
		}
	}

	private function csrf_token() {
		return md5(uniqid(rand(), TRUE));
	}

	private function create_csrf_token() {
		$token                       = $this->csrf_token();
		$_SESSION['csrf_token']      = $token;
		$_SESSION['csrf_token_time'] = time();
		return $token;
	}

	private function destroy_csrf_token() {
		$_SESSION['csrf_token']      = NULL;
		$_SESSION['csrf_token_time'] = NULL;
		return TRUE;
	}

	private function check_login() {
		if(isset($_SESSION['id'])) {
			$this->id        = $_SESSION['id'];
			$this->logged_in = TRUE;
		} elseif(isset($_SESSION['admin_id'])) {
			$this->id              = $_SESSION['admin_id'];
			$this->admin_logged_in = TRUE;
		} elseif(isset($_SESSION['author_id'])) {
			$this->id               = $_SESSION['author_id'];
			$this->author_logged_in = TRUE;
		} else {
			unset($this->id);
			$this->logged_in        = FALSE;
			$this->admin_logged_in  = FALSE;
			$this->author_logged_in = FALSE;
		}
	}

	private function check_message() {
		// Is there a message stored in the session?
		if(isset($_SESSION['message'])) {
			// Add it as an attribute and erase the stored version
			$this->message = $_SESSION['message'];
			unset($_SESSION['message']);
		} else {
			$this->message = "";
		}
	}

	private function allowed_get_params($allowed_params = []) {
		$allowed_array = [];
		foreach($allowed_params as $param) {
			if(isset($_GET[$param])) {
				$allowed_array[$param] = $_GET[$param];
			} else {
				$allowed_array[$param] = NULL;
			}
		}
		return $allowed_array;
	}

}

$session = new Session();
$message = $session->message();