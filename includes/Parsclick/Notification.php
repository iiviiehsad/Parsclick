<?php

class Notification extends DatabaseObject
{
	protected static $table_name = 'notifications';
	protected static $db_fields  = ['id', 'admin_id', 'content', 'button_text', 'button_url', 'created'];
	public           $id;
	public           $admin_id;
	public           $content;
	public           $button_text;
	public           $button_url;
	public           $created;
	
	/**
	 * @param        $admin_id
	 * @param string $content
	 * @param string $button_text
	 * @param string $button_url
	 * @return bool|\Notification
	 */
	public static function make($admin_id, $content = '', $button_text = '', $button_url = '')
	{
		if ( ! empty($content) && ! empty($admin_id)) {
			$notification              = new Notification();
			$notification->id          = (int) '';
			$notification->admin_id    = $admin_id;
			$notification->content     = preg_replace([
				'/`(.*?)`/',
				'/\*(.*?)\*/'
			], [
				'<code>$1</code>',
				'<strong>$1</strong>'
			], $content);
			$notification->button_text = $button_text;
			$notification->button_url  = $button_url;
			$notification->created     = strftime('%Y-%m-%d %H:%M:%S', time());

			return $notification;
		} else {
			return FALSE;
		}
	}
}