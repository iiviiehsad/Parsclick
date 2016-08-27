<?php // namespace Parsclick;
/**
 * Class Session to help work with Sessions
 * In our case, primarily to manage logging members in and out
 * Keep in mind when working with sessions that it is generally
 * inadvisable to store DB-related objects in sessions but instead
 * we can store for instance members_id in sessions or database columns
 */
class Session
{
	public  $id;
	public  $message;
	private $logged_in        = FALSE;
	private $admin_logged_in  = FALSE;
	private $author_logged_in = FALSE;

	/**
	 * Session Initialization.
	 */
	public function __construct()
	{
		session_start();
		$this->check_message();
		$this->check_login();
		if ($this->logged_in) {
			# actions to take right away if member is logged in
		} elseif ($this->admin_logged_in) {
			# actions to take right away if admin is logged in
		} elseif ($this->author_logged_in) {
			# actions to take right away if author is logged in
		} else {
			# actions to take right away if any user is not logged in
		}
	}

	/**
	 * Logs out and redirects if member isn't logged in
	 */
	public function confirm_logged_in()
	{
		if ( ! $this->is_logged_in() || ! $this->is_session_valid()) {
			$this->logout();
			redirect_to('/login');
		}
	}

	/**
	 * Confirms if member is logged in
	 *
	 * @return bool
	 */
	public function is_logged_in()
	{
		return (isset($this->logged_in) && $this->logged_in);
	}

	/**
	 * Destroys the session
	 */
	public function logout()
	{
		session_unset();
		session_destroy();
	}

	/**
	 * Logs out and redirects if admin isn't logged in
	 */
	public function confirm_admin_logged_in()
	{
		if ( ! $this->is_admin_logged_in() || ! $this->is_session_valid()) {
			$this->logout();
			redirect_to('/admin');
		}
	}

	/**
	 * Confirms if admin is logged in
	 *
	 * @return bool
	 */
	public function is_admin_logged_in()
	{
		return (isset($this->admin_logged_in) && $this->admin_logged_in);
	}

	/**
	 * Logs out and redirects if author isn't logged in
	 */
	public function confirm_author_logged_in()
	{
		if ( ! $this->is_author_logged_in() || ! $this->is_session_valid()) {
			$this->logout();
			redirect_to('/admin/');
		}
	}

	/**
	 * Confirms if author is logged in
	 *
	 * @return bool
	 */
	public function is_author_logged_in()
	{
		return (isset($this->author_logged_in) && $this->author_logged_in);
	}

	/**
	 * Logs in the member and assigns some sessions
	 *
	 * @param $member
	 */
	public function login($member)
	{
		if ($member) {
			session_regenerate_id();
			$this->id               = $_SESSION['id'] = $member->id;
			$this->logged_in        = TRUE;
			$_SESSION['ip']         = $_SERVER['REMOTE_ADDR'];
			$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['last_login'] = time();
		}
	}

	/**
	 * Logs in the admin and assigns some sessions
	 *
	 * @param $admin
	 */
	public function admin_login($admin)
	{
		if ($admin) {
			session_regenerate_id();
			$this->id               = $_SESSION['admin_id'] = $admin->id;
			$this->admin_logged_in  = TRUE;
			$_SESSION['ip']         = $_SERVER['REMOTE_ADDR'];
			$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['last_login'] = time();
		}
	}

	/**
	 * Logs in the author and assigns some sessions
	 *
	 * @param $author
	 */
	public function author_login($author)
	{
		if ($author) {
			session_regenerate_id();
			$this->id               = $_SESSION['author_id'] = $author->id;
			$this->author_logged_in = TRUE;
			$_SESSION['ip']         = $_SERVER['REMOTE_ADDR'];
			$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['last_login'] = time();
		}
	}

	/**
	 * Checks the message and assigns it to the session
	 *
	 * @param string $msg
	 * @return mixed
	 */
	public function message($msg = '')
	{
		if ( ! empty($msg)) {
			$_SESSION['message'] = $msg;
		}

		return $this->message;
	}

	/**
	 * Creates a HTML input tag to use it for CSRF attacks
	 *
	 * @return string
	 */
	public function csrf_token_tag()
	{
		return '<input type="hidden" name="csrf_token" value="' . $this->create_csrf_token() . '">';
	}

	/**
	 * Checks to see if CSRF token is recent
	 *
	 * @return bool
	 */
	public function csrf_token_is_recent()
	{
		$max_elapsed = 60 * 60 * 24 * 3; # 3 days
		if (isset($_SESSION['csrf_token_time'])) {
			$stored_time = $_SESSION['csrf_token_time'];

			return ($stored_time + $max_elapsed) >= time();
		} else {
			$this->destroy_csrf_token();

			return FALSE;
		}
	}

	/**
	 * Checks to see if CSRF token is valid
	 *
	 * @return bool
	 */
	public function csrf_token_is_valid()
	{
		if (isset($_POST['csrf_token'])) {
			$user_token   = $_POST['csrf_token'];
			$stored_token = $_SESSION['csrf_token'];

			return $user_token === $stored_token;
		} else {
			return FALSE;
		}
	}

	/**
	 * Shuts down the communication if CSRF token isn't valid
	 */
	public function die_on_csrf_token_failure()
	{
		if ( ! $this->csrf_token_is_valid()) {
			$output1 = 'خطای درخواست جعلی!';
			$output2 = 'شناسه درخواست میان وب گاهی معتبر نیست! برگردید و رفرش کنید.';
			$output  = warning($output1, $output2);
			die($output);
		}
	}

	/**
	 * Checks to see if HTTP request is from the same domain
	 *
	 * @return bool
	 */
	public function request_is_same_domain()
	{
		if ( ! isset($_SERVER['HTTP_REFERER'])) {
			return FALSE;
		} else {
			$referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
			$server_host  = $_SERVER['HTTP_HOST'];

			return $referer_host == $server_host;
		}
	}

	/**
	 * Checks to see if there's any message to show
	 */
	private function check_message()
	{
		// Is there a message stored in the session?
		if (isset($_SESSION['message'])) {
			// Add it as an attribute and erase the stored version
			$this->message = $_SESSION['message'];
			unset($_SESSION['message']);
		} else {
			$this->message = '';
		}
	}

	/**
	 * Checks to see who is logged in
	 * Admin
	 * Author
	 * Member
	 */
	private function check_login()
	{
		if (isset($_SESSION['id'])) {
			$this->id        = $_SESSION['id'];
			$this->logged_in = TRUE;
		} elseif (isset($_SESSION['admin_id'])) {
			$this->id              = $_SESSION['admin_id'];
			$this->admin_logged_in = TRUE;
		} elseif (isset($_SESSION['author_id'])) {
			$this->id               = $_SESSION['author_id'];
			$this->author_logged_in = TRUE;
		} else {
			unset($this->id);
			$this->logged_in        = FALSE;
			$this->admin_logged_in  = FALSE;
			$this->author_logged_in = FALSE;
		}
	}

	/**
	 * Checks to see if the session is valid
	 *
	 * @return bool
	 */
	private function is_session_valid()
	{
		$check_ip         = FALSE; # FALSE because everybody uses proxy
		$check_user_agent = TRUE;
		$check_last_login = TRUE;
		if ($check_ip && ! $this->request_ip_matches_session()) {
			return FALSE;
		}
		if ($check_user_agent && ! $this->request_user_agent_matches_session()) {
			return FALSE;
		}
		if ($check_last_login && ! $this->last_login_is_recent()) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Checks to see if IP matches the IP stored in the session
	 *
	 * @return bool
	 */
	private function request_ip_matches_session()
	{
		// return false if either value is not set
		if ( ! isset($_SESSION['ip'], $_SERVER['REMOTE_ADDR'])) {
			return FALSE;
		}

		return $_SESSION['ip'] === $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Checks to see if user agent matches the
	 * agent matches stored in the session
	 *
	 * @return bool
	 */
	private function request_user_agent_matches_session()
	{
		# return false if either value is not set
		if ( ! isset($_SESSION['user_agent'], $_SERVER['HTTP_USER_AGENT'])) {
			return FALSE;
		}

		return $_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * Checks to see if last login is recent
	 *
	 * @return bool
	 */
	private function last_login_is_recent()
	{
		$max_elapsed = 60 * 60 * 24 * 3; # 3 days
		# return false if value is not set
		if ( ! isset($_SESSION['last_login'])) {
			return FALSE;
		}
		if (($_SESSION['last_login'] + $max_elapsed) >= time()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Assigns the CSRF token to the session
	 *
	 * @return string
	 */
	private function create_csrf_token()
	{
		# Note: Do not try to inline $token !
		$token                       = $this->csrf_token();
		$_SESSION['csrf_token']      = $token;
		$_SESSION['csrf_token_time'] = time();

		return $token;
	}

	/**
	 * Creates a CSRF token
	 *
	 * @return string
	 */
	private function csrf_token()
	{
		return md5(uniqid(mt_rand(), TRUE));
	}

	/**
	 * Destroys the CSRF token
	 *
	 * @return bool
	 */
	private function destroy_csrf_token()
	{
		$_SESSION['csrf_token']      = NULL;
		$_SESSION['csrf_token_time'] = NULL;

		return TRUE;
	}
} # END of CLASS