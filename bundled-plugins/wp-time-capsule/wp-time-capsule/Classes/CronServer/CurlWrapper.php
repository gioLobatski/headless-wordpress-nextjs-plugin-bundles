<?php

#[AllowDynamicProperties]
class Wptc_Cron_Server_Curl_Wrapper extends Wptc_Base_Curl_Wrapper {
	protected $domain_url;
	protected $post_type;
	protected $cron_posts_always_needed;
	protected $config;

	public function __construct() {
		$this->init();
	}

	private function init() {
		$this->config = WPTC_Base_Factory::get('Wptc_Cron_Server_Config');

		$this->set_defaults();
	}

	protected function set_defaults() {
		$this->domain_url = WPTC_CRSERVER_URL;
		$this->post_type = 'POST';

		$app_id = $this->config->get_option('appID');

		$main_account_email = $this->config->get_option('main_account_email', true) ?? '';
		$email = trim($main_account_email);
		$email_encoded = base64_encode($email);

		$main_account_pwd = $this->config->get_option('main_account_pwd', true) ?? '';
		$pwd = trim($main_account_pwd);
		$pwd_encoded = base64_encode($pwd);

		$this->cron_posts_always_needed = array(
			'app_id' => $app_id,
			'email' => $email_encoded,
			'pwd' => $pwd_encoded,
			'site_url' => WPTC_Factory::get('config')->get_option('site_url_wptc'),
		);

	}
}