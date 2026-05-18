<?php

#[AllowDynamicProperties]
class WPTC_Replace_DB_Links{

	private $config;

	public function __construct(){
		$this->config = WPTC_Factory::get('config');
		$this->init_db();
	}

	public function init_db(){
		global $wpdb;
		$this->wpdb = $wpdb;
		return $wpdb;
	}

	public function basic_replace_list($replace_list, $old_url, $new_url)
	{
		array_push($replace_list,

			array(
				'search'  => wptc_add_protocal_to_url($old_url, $protocal = 'http', $add_www = false),
				'replace' => wptc_add_protocal_to_url($new_url, $protocal = 'http', $add_www = false)
			),
			array(
				'search'  => wptc_add_protocal_to_url($old_url, $protocal = 'http', $add_www = true),
				'replace' => wptc_add_protocal_to_url($new_url, $protocal = 'http', $add_www = true)
			),
			array(
				'search'  => wptc_add_protocal_to_url($old_url, $protocal = 'https', $add_www = false),
				'replace' => wptc_add_protocal_to_url($new_url, $protocal = 'https', $add_www = false)
			),
			array(
				'search'  => wptc_add_protocal_to_url($old_url, $protocal = 'https', $add_www = true),
				'replace' => wptc_add_protocal_to_url($new_url, $protocal = 'https', $add_www = true)
			),
			
			array(
				'search'  => str_replace('"', "", json_encode(wptc_add_protocal_to_url($old_url, $protocal = 'http', $add_www = false))),
				'replace' => str_replace('"', "", json_encode(wptc_add_protocal_to_url($new_url, $protocal = 'http', $add_www = false)))
			),
			array(
				'search'  => str_replace('"', "", json_encode(wptc_add_protocal_to_url($old_url, $protocal = 'http', $add_www = true))),
				'replace' => str_replace('"', "", json_encode(wptc_add_protocal_to_url($new_url, $protocal = 'http', $add_www = true)))
			),
			array(
				'search'  => str_replace('"', "", json_encode(wptc_add_protocal_to_url($old_url, $protocal = 'https', $add_www = false))),
				'replace' => str_replace('"', "", json_encode(wptc_add_protocal_to_url($new_url, $protocal = 'https', $add_www = false)))
			),
			array(
				'search'  => str_replace('"', "", json_encode(wptc_add_protocal_to_url($old_url, $protocal = 'https', $add_www = true))),
				'replace' => str_replace('"', "", json_encode(wptc_add_protocal_to_url($new_url, $protocal = 'https', $add_www = true)))
			),

			array(
				'search'  => urlencode(wptc_add_protocal_to_url($old_url, $protocal = 'http', $add_www = false)),
				'replace' => urlencode(wptc_add_protocal_to_url($new_url, $protocal = 'http', $add_www = false))
			),
			array(
				'search'  => urlencode(wptc_add_protocal_to_url($old_url, $protocal = 'http', $add_www = true)),
				'replace' => urlencode(wptc_add_protocal_to_url($new_url, $protocal = 'http', $add_www = true))
			),
			array(
				'search'  => urlencode(wptc_add_protocal_to_url($old_url, $protocal = 'https', $add_www = false)),
				'replace' => urlencode(wptc_add_protocal_to_url($new_url, $protocal = 'https', $add_www = false))
			),
			array(
				'search'  => urlencode(wptc_add_protocal_to_url($old_url, $protocal = 'https', $add_www = true)),
				'replace' => urlencode(wptc_add_protocal_to_url($new_url, $protocal = 'https', $add_www = true))
			)
		);
		
		return $replace_list;
	}

	public function basic_replace_path($replace_list, $old_file_path, $new_file_path)
	{
		array_push($replace_list,
			array(
				'search'  => $old_file_path,
				'replace' => $new_file_path
			),

			array(
				'search'  => str_replace('"', "", json_encode($old_file_path)),
				'replace' => str_replace('"', "", json_encode($new_file_path))
			),

			array(
				'search'  => urlencode($old_file_path),
				'replace' => urlencode($new_file_path)
			)
		);

		$normalized_path = rtrim(wp_normalize_path($old_file_path), '\\');

		if($old_file_path != $normalized_path){
			array_push($replace_list,
				array(
					'search'  => rtrim(wp_normalize_path($old_file_path), '\\'),
					'replace' => rtrim($new_file_path, '/')
				)
			);
		}

		return $replace_list;
	}

	public function prepare_replace_list_for_staging($replace_list = array(), $table_prefix = '', $old_url = '', $new_url = '', $type = '')
	{
		wptc_log($table_prefix, "--------prepare_replace_list_for_staging--------");

		$s2l_multisite_sub_domain = $this->config->get_option('s2l_multisite_sub_domain');

		if( is_multisite() && 
			((defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) || $s2l_multisite_sub_domain) ){
			$query = "SELECT domain FROM " . $table_prefix . "blogs";
			$all_other_domains = $this->wpdb->get_results($query, ARRAY_A);

			wptc_log($all_other_domains, "--------all_other_domains-----$table_prefix---");

			foreach ($all_other_domains as $key => $value) {
				$prepared_old_url = $value['domain'];
				$prepared_new_url = $value['domain'] . '/' . $this->config->get_option('same_server_staging_path');

				if( $type == 'staging_to_live' ){
					$prepared_old_url = $value['domain'] . '/' . $this->config->get_option('same_server_staging_path');
					$prepared_new_url = $value['domain'];
				}

				wptc_log($prepared_new_url, "---to-----basic_replace_list--------");

				$replace_list = $this->basic_replace_list($replace_list, $prepared_old_url, $prepared_new_url);
			}
		} else {
			$replace_list = $this->basic_replace_list($replace_list, $old_url, $new_url);
		}

		return $replace_list;
	}

	public function restore_to_staging_replaced_url_fix($replace_list, $old_url, $new_url){
		$live_site_site_url = $this->config->get_option('s2l_site_url');
		$live_site_home_url = $this->config->get_option('s2l_live_url');

		$live_site_site_url_uploads_folder = $live_site_site_url . '/' . basename(WP_CONTENT_DIR) . '/uploads';

		$after_restore_first_upload_url = str_replace($live_site_home_url, $new_url, $live_site_site_url_uploads_folder);

		$new_url_upload_folder = $new_url . '/' . basename(WP_CONTENT_DIR) . '/uploads';

		if($after_restore_first_upload_url != $new_url_upload_folder){
			$replace_list = $this->basic_replace_list($replace_list, $after_restore_first_upload_url, $new_url_upload_folder);
		}

		return $replace_list;
	}

	public function replace_uri($old_url, $new_url, $old_file_path, $new_file_path, $table_prefix, $tables, $new_site_url = '', $type = 'restore'){

		wptc_log(func_get_args(), "--------" . __FUNCTION__ . "--------");

		$replace_list = array();

		if( !empty($new_site_url) 
			&& $type != 'restore_in_staging' ){
			$old_upload_url = $old_url . '/' . basename(WP_CONTENT_DIR) . '/uploads';
			$new_upload_url = $new_site_url . '/' . basename(WP_CONTENT_DIR) . '/uploads';

			$replace_list = $this->basic_replace_list($replace_list, $old_upload_url, $new_upload_url);
		}

		if( $type == 'staging' || $type == 'restore_in_staging' || $type == 'staging_to_live'){
			$replace_list = $this->prepare_replace_list_for_staging($replace_list, $table_prefix, $old_url, $new_url, $type);
		} else {
			$replace_list = $this->basic_replace_list($replace_list, $old_url, $new_url);
		}

		$replace_list = $this->basic_replace_path($replace_list, $old_file_path, $new_file_path);

		if( !empty($new_site_url) 
			&& $type == 'restore_in_staging' ){
			$replace_list = $this->restore_to_staging_replaced_url_fix($replace_list, $old_url, $new_url);
		}

		wptc_log($replace_list,'-----------$replace_list----------------');

		array_walk_recursive($replace_list, 'wptc_dupx_array_rtrim');

		if (empty($tables)) {
			$tables = $this->wpdb->get_results( 'SHOW TABLES LIKE "' . $table_prefix . '%"', ARRAY_N);
			
			if (empty($tables)) {
				$small_letters_table_prefix = strtolower($table_prefix);
				$tables = $this->wpdb->get_results( 'SHOW TABLES LIKE "' . $small_letters_table_prefix . '%"', ARRAY_N);
			}
		}

		wptc_log($tables,'-----------$tables----------------');

		foreach ($tables as $key => $value) {

			wptc_log($value[0], '---------------$value replace_old_url-----------------');

			if (strstr($value[0], 'wptc_') !== false) {
				wptc_log(array(),'-----------skip wptc table----------------');
				continue;
			}

			if (strstr($value[0], 'aiowps_audit_log') !== false) {
				wptc_log(array(),'-----------skip aiowps_audit_log table----------------');
				continue;
			}

			$this->replace_old_url_depth($replace_list, array($value[0]), true, $type);
			unset($tables[$key]);

			if (count($tables) === 0) {
				$this->config->set_option('same_server_replace_old_url', true);
			} else {
				$this->config->set_option('same_server_replace_old_url_data', serialize($tables));
			}

			if($this->is_timedout()){
				$this->close_request(array('status' => 'continue', 'msg' => 'Replacing links.', 'percentage' => 85));
			}
		}
	}

	private function replace_old_url_depth($list = array(), $tables = array(), $fullsearch = false, $type = '') {
		$report = array(
			'scan_tables' => 0,
			'scan_rows'   => 0,
			'scan_cells'  => 0,
			'updt_tables' => 0,
			'updt_rows'   => 0,
			'updt_cells'  => 0,
			'errsql'      => array(),
			'errser'      => array(),
			'errkey'      => array(),
			'errsql_sum'  => 0,
			'errser_sum'  => 0,
			'errkey_sum'  => 0,
			'time'        => '',
			'err_all'     => 0
		);

		$walk_function = function(&$str){$str = "`$str`";};

		if (is_array($tables) && !empty($tables)) {

			foreach ($tables as $table) {
				$report['scan_tables']++;
				$columns = array();
				$fields = $this->wpdb->get_results('DESCRIBE ' . $table); //modified

				foreach ($fields as $key => $column) {
					$columns[$column->Field] = $column->Key == 'PRI' ? true : false;
				}

				$row_count =  $this->wpdb->get_var("SELECT COUNT(*) FROM `{$table}`");

				if ($row_count == 0) {
					continue;
				}

				$page_size = $this->config->get_option('internal_staging_deep_link_limit');

				if (empty($page_size)) {
					$page_size = WPTC_STAGING_DEFAULT_DEEP_LINK_REPLACE_LIMIT; //fallback to default value
				}

				$offset = $page_size;
				if(empty($offset)){
					$offset = ($page_size + 1);
				}
				$pages = ceil($row_count / $page_size);
				$colList = '*';
				$colMsg  = '*';

				if (! $fullsearch) {
					$colList = $this->get_text_columns($table);
					if ($colList != null && is_array($colList)) {
						array_walk($colList, $walk_function);
						$colList = implode(',', $colList);
					}
					$colMsg = (empty($colList)) ? '*' : '~';
				}

				if (empty($colList)) {
					continue;
				}

				$prev_table_data = $this->same_server_deep_link_status($table);

				if (!$prev_table_data) {
					$prev_table_data = 0;
				}

				wptc_log($pages, "--------pages--------");

				//Paged Records
				for ($page = $prev_table_data; $page < $pages; $page++) {

					wptc_log($page, "--------current_page--------");

					$current_row = 0;
					$start = $page * $page_size;
					$end   = $start + $page_size;
					$sql = sprintf("SELECT {$colList} FROM `%s` LIMIT %d, %d", $table, $start, $offset);

					wptc_log($offset, "--------taking pages----from----");
					wptc_log($start, "--------taking pages----to----");
					// wptc_log($sql, "--------sql pages----to----");

					$data  = $this->wpdb->get_results($sql);
					if (empty($data)){
						$scan_count = ($row_count < $end) ? $row_count : $end;
					}

					foreach ($data as $key => $row) {

						wptc_manual_debug('', 'during_replace_old_url_staging_common', 1000);

						$report['scan_rows']++;
						$current_row++;
						$upd_col = array();
						$upd_sql = array();
						$where_sql = array();
						$upd = false;
						$serial_err = 0;

						foreach ($columns as $column => $primary_key) {
							$report['scan_cells']++;
							$edited_data = $data_to_fix = $row->$column;
							$base64coverted = false;
							$txt_found = false;

							if(strlen($edited_data) > (1024 * 100)){

								continue;
							}

							if (!empty($row->$column) && !is_numeric($row->$column)) {
								//Base 64 detection
								$decoded = base64_decode($row->$column, true);
								if ($decoded) {
									if ($this->is_serialized($decoded)) {
										$edited_data = $decoded;
										$base64coverted = true;
									}
								}

								if (is_string($edited_data) && strpos($edited_data, "\0") !== false) {

									continue;
								}

								//Skip table cell if match not found
								foreach ($list as $item) {
									if (strpos($edited_data, $item['search']) !== false) {
										$txt_found = true;
										break;
									}
								}
								if (! $txt_found) {
									continue;
								}

								//Replace logic - level 1: simple check on any string or serlized strings
								foreach ($list as $item) {
									$edited_data = $this->recursive_unserialize_replace($item['search'], $item['replace'], $edited_data);
								}

								//Replace logic - level 2: repair serilized strings that have become broken
								$serial_check = $this->fix_serial_string($edited_data);
								if ($serial_check['fixed']) {
									$edited_data = $serial_check['data'];
								} else if ($serial_check['tried'] && !$serial_check['fixed']) {
									$serial_err++;
								}
							}

							//Change was made
							if ($edited_data != $data_to_fix || $serial_err > 0) {

								//wptc_log($row->$column, "--------row column--------");
								//wptc_log($edited_data, "--------change was made-----to---");
								//wptc_log($data_to_fix, "--------from--------");

								$report['updt_cells']++;
								//Base 64 encode
								if ($base64coverted) {
									$edited_data = base64_encode($edited_data);
								}
								$upd_col[] = $column;
								$upd_sql[] = $column . ' = "' . $this->wpdb->_real_escape($edited_data) . '"';
								$upd = true;
							}

							if ($primary_key) {
								$where_sql[] = $column . ' = "' . $this->wpdb->_real_escape($data_to_fix) . '"';
							}
						}

						if ($upd && !empty($where_sql)) {

							$sql = "UPDATE `{$table}` SET " . implode(', ', $upd_sql) . ' WHERE ' . implode(' AND ', array_filter($where_sql));

							// wptc_log($sql, "--------replacing sql--------");

							$result = $this->wpdb->query($sql);

							if($result === false){
								wptc_log($this->wpdb->last_error, "--------replacing sql--error------");
								wptc_log($this->wpdb->last_query, "--------replacing sql--error------");
							}

							if ($result) {
								if ($serial_err > 0) {
									$report['errser'][] = "SELECT " . implode(', ', $upd_col) . " FROM `{$table}`  WHERE " . implode(' AND ', array_filter($where_sql)) . ';';
								}
								$report['updt_rows']++;
							}
						} elseif ($upd) {
							$report['errkey'][] = sprintf("Row [%s] on Table [%s] requires a manual update.", $current_row, $table);
						}
					}
					if($this->is_timedout()){
						$this->config->set_option('same_server_replace_url_multicall_status', serialize(array($table =>($page+1))));
						$this->close_request(array('status' => 'continue', 'msg' => 'Replacing links - '. $table . '(' . $start . ')' , 'percentage' => 40));
					}

				}

				if ($upd) {
					$report['updt_tables']++;
				}
			}
		}

		$report['errsql_sum'] = empty($report['errsql']) ? 0 : count($report['errsql']);
		$report['errser_sum'] = empty($report['errser']) ? 0 : count($report['errser']);
		$report['errkey_sum'] = empty($report['errkey']) ? 0 : count($report['errkey']);
		$report['err_all']    = $report['errsql_sum'] + $report['errser_sum'] + $report['errkey_sum'];
		return $report;
	}

	private function same_server_deep_link_status($table){

		$data = $this->config->get_option('same_server_replace_url_multicall_status');

		if (empty($data)) {
			return false;
		}

		$unserialized_data = @unserialize($data);

		if (empty($unserialized_data)) {
			return false;
		}

		if(!isset($unserialized_data[$table])){
			return false;
		}

		return $unserialized_data[$table];
	}

	private function get_text_columns($table) {

		$type_where  = "type NOT LIKE 'tinyint%' AND ";
		$type_where .= "type NOT LIKE 'smallint%' AND ";
		$type_where .= "type NOT LIKE 'mediumint%' AND ";
		$type_where .= "type NOT LIKE 'int%' AND ";
		$type_where .= "type NOT LIKE 'bigint%' AND ";
		$type_where .= "type NOT LIKE 'float%' AND ";
		$type_where .= "type NOT LIKE 'double%' AND ";
		$type_where .= "type NOT LIKE 'decimal%' AND ";
		$type_where .= "type NOT LIKE 'numeric%' AND ";
		$type_where .= "type NOT LIKE 'date%' AND ";
		$type_where .= "type NOT LIKE 'time%' AND ";
		$type_where .= "type NOT LIKE 'year%' ";

		$result = $this->wpdb->get_results("SHOW COLUMNS FROM `{$table}` WHERE {$type_where}", ARRAY_N);
		if (empty($result)) {
			return null;
		}
		$fields = array();
		if (count($result) > 0 ) {
			foreach ($result as $key => $row) {
				$fields[] = $row['Field'];
			}
		}

		$result =  $this->wpdb->get_results("SHOW INDEX FROM `{$table}`", ARRAY_N);
		if (count($result) > 0) {
			foreach ($result as $key => $row) {
				$fields[] = $row['Column_name'];
			}
		}

		return (count($fields) > 0) ? $fields : null;
	}

	private function recursive_unserialize_replace($from = '', $to = '', $data = '', $serialised = false) {

		// wptc_log('', "------f--recursive_unserialize_replace--start------");

		try {
			if (is_string($data) && ($unserialized = @unserialize($data)) !== false) {

				// wptc_log('', "------f--string--------");

				$data = $this->recursive_unserialize_replace($from, $to, $unserialized, true);
			} else if (is_array($data)) {
				$_tmp = array();
				foreach ($data as $key => $value) {

					// wptc_log($key, "--------key--------");

					// if (strstr($key, "\0") !== false ) {
					// 	continue;
					// }
					// if (strstr($value, "\0") !== false ) {
					// 	continue;
					// }
					$_tmp[$key] = $this->recursive_unserialize_replace($from, $to, $value, false);
				}
				$data = $_tmp;
				unset($_tmp);
			} else if (is_object($data)) {
				// $this_get_class = get_class($data);
				// wptc_log($this_get_class, "--------this_get_class--------");

				if( get_class($data) != '__PHP_Incomplete_Class' ){

					// wptc_log('', "------before get object vars----------");

					$_tmp = $data;
					$props = get_object_vars( $data );

					// wptc_log('', "------after get object vars----------");

					foreach ($props as $key => $value) {

						// wptc_log($key, "--------key--------");

						//If some objects has \0 in the key it creates the fatal error so skip such contents
						if (is_string($key) && strstr($key, "\0") !== false ) {
							continue;
						}
						if (is_string($value) && strstr($value, "\0") !== false ) {
							continue;
						}
						$_tmp->$key = $this->recursive_unserialize_replace( $from, $to, $value, false );
					}
					$data = $_tmp;
					unset($_tmp);
					
				}
			} else {

				// wptc_log('', "--------else--------");

				if (is_string($data)) {

					// wptc_log($data, "--------on--------");

					$data = str_replace($from, $to, $data);
				}
			}

			// wptc_log('', "------below--------");

			if ($serialised)
				return serialize($data);

		} catch (Exception $error){

			wptc_log($error->getMessage(), "--------recursive replace error--------");

		}
		return $data;
	}

	private function fix_serial_string($data) {
		$result = array('data' => $data, 'fixed' => false, 'tried' => false);
		if (preg_match("/s:[0-9]+:/", $data)) {
			if (!$this->is_serialized($data)) {
				$regex = '!(?<=^|;)s:(\d+)(?=:"(.*?)";(?:}|a:|s:|b:|d:|i:|O:|C:|N;))!s';
				$serial_string = preg_match('/^s:[0-9]+:"(.*$)/s', trim($data), $matches);
				//Nested serial string
				if ($serial_string) {
					$inner = preg_replace_callback($regex, array($this, 'fix_string_callback'), rtrim($matches[1], '";'));
					$serialized_fixed = 's:' . strlen($inner) . ':"' . $inner . '";';
				} else {
					$serialized_fixed = preg_replace_callback($regex, array($this, 'fix_string_callback'), $data);
				}
				if ($this->is_serialized($serialized_fixed)) {
					$result['data'] = $serialized_fixed;
					$result['fixed'] = true;
				}
				$result['tried'] = true;
			}
		}
		return $result;
	}

	public function fix_string_callback($matches) {
		return 's:'.strlen(($matches[2]));
	}

	private function is_serialized($data){
		$test = @unserialize($data);
        return ($test !== false || $test === 'b:0;') ? true : false;
	}

	private function close_request($res){

		$is_restore_to_staging = $this->config->get_option('is_restore_to_staging');
		$same_server_staging_running = $this->config->get_option('same_server_staging_running');
		$migration_running = $this->config->get_option('migration_url');

		if( ( $is_restore_to_staging && !$same_server_staging_running ) || $migration_running){
			$restore_app_functions = new WPTC_Restore_App_Functions();
			$restore_app_functions->die_with_msg("wptcs_callagain_wptce");
		}

		wptc_die_with_json_encode( $res );
	}

	private function is_timedout(){
		$is_restore_to_staging = $this->config->get_option('is_restore_to_staging');
		$same_server_staging_running = $this->config->get_option('same_server_staging_running');

		if($is_restore_to_staging && !$same_server_staging_running){
			$restore_app_functions = new WPTC_Restore_App_Functions();
			return $restore_app_functions->maybe_call_again_tc($return = true);
		}

		return is_wptc_timeout_cut();
	}

	public function create_htaccess($url, $dir, $type = 'normal'){
		$args    = parse_url($url);
		$string  = rtrim($args['path'], "/");

		if ($type === 'multisite') {
			$data = "\nRewriteBase ".$string."/\nRewriteRule ^index\.php$ - [L]\n\n ## add a trailing slash to /wp-admin\nRewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]\n\nRewriteCond %{REQUEST_FILENAME} -f [OR]\nRewriteCond %{REQUEST_FILENAME} -d\nRewriteRule ^ - [L]\nRewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]\nRewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]\nRewriteRule . index.php [L]";
		} else {
			$data = "# BEGIN WordPress\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteBase ".$string."/\nRewriteRule ^index\.php$ - [L]\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule . ".$string."/index.php [L]\n</IfModule>\n# END WordPress";
		}

		@file_put_contents($dir . '.htaccess', $data);
	}

	public function discourage_search_engine($new_prefix, $reset_permalink = false){
		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				'UPDATE ' . $new_prefix . 'options SET option_value = %s WHERE option_name = \'blog_public\'',
				0
			)
		);

		if ($reset_permalink) {
			$this->reset_permalink($new_prefix . 'options');
		}

		if (!is_multisite()) {
			return false;
		}

		$new_prefix = (string) $new_prefix;
		$wp_tables = WPTC_Factory::get('processed-files')->get_all_tables();
		foreach ($wp_tables as $table) {
			if (stripos($table, 'options') === false || stripos($table, $new_prefix) === false) {
				continue;
			}

			$this->wpdb->query(
				$this->wpdb->prepare(
					'UPDATE ' . $table . ' SET option_value = %s WHERE option_name = \'blog_public\'',
					0
				)
			);

			if (!$reset_permalink) {
				continue;
			}

			$this->reset_permalink($table);
		}
	}

	private function reset_permalink($table){
		$this->wpdb->query(
			$this->wpdb->prepare(
				'UPDATE ' . $table . ' SET option_value = %s WHERE option_name = \'permalink_structure\'',
				false
			)
		);
	}

	public function update_site_and_home_url($prefix, $url){
		$prepared_query = $this->wpdb->prepare(
			'UPDATE ' . $prefix . 'options SET option_value = %s WHERE option_name = \'siteurl\' OR option_name = \'home\'',
			$url
		);

		wptc_log($prepared_query, "--------update_site_and_home_url--------");

		$result = $this->wpdb->query( $prepared_query );

		return $result;
	}

	public function rewrite_rules($prefix){
		//Update rewrite_rules in clone options table
		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				'UPDATE ' . $prefix . 'options SET option_value = %s WHERE option_name = \'rewrite_rules\'',
				''
			)
		);

		if (!$result) {
			wptc_log("Updating option[rewrite_rules] not successfull, likely the main site is not using permalinks", '--------FAILED-------------');
			return ;
		}
	}

	public function update_user_roles_for_multi_sites($new_prefix_par, $old_prefix_par)
	{
		$query = "SELECT blog_id FROM " . $table_prefix . "blogs";
		$all_blog_ids = $this->wpdb->get_results($query, ARRAY_A);

		foreach ($all_blog_ids as $key => $value) {
			if($key > 0){
				$new_prefix = $new_prefix_par . $value['blog_id'] . '_';
				$old_prefix = $old_prefix_par . $value['blog_id'] . '_';
			} else {
				$new_prefix = $new_prefix_par;
				$old_prefix = $old_prefix_par;
			}
			$query = "UPDATE  ". $new_prefix . "options SET option_name = '" . $new_prefix . "user_roles' WHERE option_name = '" . $old_prefix . "user_roles' LIMIT 1";
			$result = $this->wpdb->query($query);
		}

		return $result;
	}

	public function update_user_roles($new_prefix, $old_prefix){
		if( is_multisite() ){
			$this->update_user_roles_for_multi_sites($new_prefix, $old_prefix);
		} else {
			$result = $this->wpdb->query(
				"UPDATE  ". $new_prefix . "options SET option_name = '" . $new_prefix . "user_roles' WHERE option_name = '" . $old_prefix . "user_roles' LIMIT 1"
			);
		}

		if ($result === false) {
			$error = isset($this->wpdb->error) ? $this->wpdb->error : '';
			wptc_log("User roles modification has been failed", $error , '--------FAILED-------------');
			return ;
		}
	}

	public function replace_prefix_extra_for_multisite($new_prefix_par, $old_prefix_par)
	{
		$query = "SELECT blog_id FROM " . $new_prefix_par . "blogs";
		$all_blog_ids = $this->wpdb->get_results($query, ARRAY_A);

		foreach ($all_blog_ids as $key => $value) {
			if($key > 0){
				$new_prefix = $new_prefix_par . $value['blog_id'] . '_';
				$old_prefix = $old_prefix_par . $value['blog_id'] . '_';
			} else {
				$new_prefix = $new_prefix_par;
				$old_prefix = $old_prefix_par;
			}

			$options_sql = 'UPDATE ' . $new_prefix . "options SET option_name = REPLACE(option_name, '$old_prefix', '$new_prefix') WHERE option_name LIKE '" . $old_prefix . "%'";

			$result_options = $this->wpdb->query( $options_sql );
		}

		return $result_options;
	}

	//replace table prefix in meta_keys
	public function replace_prefix($new_prefix, $old_prefix){
		$usermeta_sql = $this->wpdb->prepare(
				'UPDATE ' . $new_prefix . 'usermeta SET meta_key = REPLACE(meta_key, %s, %s) WHERE meta_key LIKE %s',
				$old_prefix,
				$new_prefix,
				$old_prefix . '_%'
			);

		$result_usermeta = $this->wpdb->query( $usermeta_sql );

		if( is_multisite() ){
			$result_options = $this->replace_prefix_extra_for_multisite($new_prefix, $old_prefix);
		} else {
			$options_sql = $this->wpdb->prepare(
				'UPDATE ' . $new_prefix . 'options SET option_name = REPLACE(option_name, %s, %s) WHERE option_name LIKE %s',
				$old_prefix,
				$new_prefix,
				$old_prefix . '_%'
			);

			$result_options = $this->wpdb->query( $options_sql );
		}


		if ($result_options === false || $result_usermeta === false) {
			wptc_log("Updating db prefix $new_prefix has been failed.". $this->wpdb->last_error, '-----------FAILED----------');
			return ;
		}

	}

	public function update_wp_blogs_path_multisite($new_prefix, $live_path, $staging_path){
		$this_query = 'SELECT `blog_id`, `path` from `' . $new_prefix . 'blogs` WHERE 1=1';
		$all_paths = $this->wpdb->get_results($this_query, ARRAY_A);

		if(empty($all_paths)){

			wptc_log($this_query, '--------all_paths--false---------');

			return;
		}

		foreach($all_paths as $k => $v){
			if(empty($v['path'])){

				continue;
			}
			$exploded = explode($live_path, $v['path']);
			if(empty($exploded)){

				continue;
			}
			$exploded[0] = $staging_path;

			$this_new_path = implode('', $exploded);

			wptc_log($this_new_path, '------update_wp_blogs_path_multisite--this_new_path---------');

			$this_query_2 = "UPDATE `" . $new_prefix . "blogs` SET `path`='$this_new_path' WHERE `blog_id`='" . $v['blog_id'] . "';";
			$result2 = $this->wpdb->query($this_query_2);

			if($result2 === false){
				wptc_log($this_query_2, '------update_wp_blogs_path_multisite--result2 failed---------');
			}
		}
	}

	public function multi_site_db_changes($new_prefix, $new_site_url, $old_url){

		$staging_args = parse_url($new_site_url);
		$staging_path = rtrim($staging_args['path'], "/"). "/";
		$live_args    = parse_url($old_url);
		$live_path    = rtrim($live_args['path'], "/")."/";

		//update site table
		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				'UPDATE ' . $new_prefix . 'site SET path = %s',
				$staging_path
			)
		);

		if ($result === false ) {
			$error = isset($this->wpdb->error) ? $this->wpdb->error : '';
			wptc_log('modifying site table is failed. ' . $error, '--------FAILED----------');
		} else {
			wptc_log('modifying site table is successfully done.', '--------SUCCESS----------');
		}

		//update blogs table
		$this->update_wp_blogs_path_multisite($new_prefix, $live_path, $staging_path);

		if ( $result === false ) {
			$error = isset($this->wpdb->error) ? $this->wpdb->error : '';
			wptc_log('modifying blogs table is failed. ' . $error, '--------FAILED----------');
		} else {
			wptc_log('modifying blogs table is successfully done.', '--------SUCCESS----------');
		}

		if(defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL){
			$this->config->set_option('s2l_multisite_sub_domain', true);
		}

	}

	public function modify_wp_config($meta, $type = false){
		wptc_log(func_get_args(), "--------" . __FUNCTION__ . "--------");

		$lines = @file($meta['new_path'] . '/wp-config.php');

		$is_outside_config = $this->is_outside_config($lines, $type);

		if(empty($lines) || $is_outside_config){
			$lines = @file($meta['new_path'] . '/wp-config-sample.php');
		}

		if (empty($lines) && $is_outside_config) {
			wptc_log(array(), '--------preparing self config sample file------------');
			
			$config_sample_file_contents_enc = 'PD9waHANCi8qKg0KICogVGhlIGJhc2UgY29uZmlndXJhdGlvbiBmb3IgV29yZFByZXNzDQogKg0KICogVGhlIHdwLWNvbmZpZy5waHAgY3JlYXRpb24gc2NyaXB0IHVzZXMgdGhpcyBmaWxlIGR1cmluZyB0aGUgaW5zdGFsbGF0aW9uLg0KICogWW91IGRvbid0IGhhdmUgdG8gdXNlIHRoZSB3ZWIgc2l0ZSwgeW91IGNhbiBjb3B5IHRoaXMgZmlsZSB0byAid3AtY29uZmlnLnBocCINCiAqIGFuZCBmaWxsIGluIHRoZSB2YWx1ZXMuDQogKg0KICogVGhpcyBmaWxlIGNvbnRhaW5zIHRoZSBmb2xsb3dpbmcgY29uZmlndXJhdGlvbnM6DQogKg0KICogKiBEYXRhYmFzZSBzZXR0aW5ncw0KICogKiBTZWNyZXQga2V5cw0KICogKiBEYXRhYmFzZSB0YWJsZSBwcmVmaXgNCiAqICogQUJTUEFUSA0KICoNCiAqIEBsaW5rIGh0dHBzOi8vd29yZHByZXNzLm9yZy9kb2N1bWVudGF0aW9uL2FydGljbGUvZWRpdGluZy13cC1jb25maWctcGhwLw0KICoNCiAqIEBwYWNrYWdlIFdvcmRQcmVzcw0KICovDQoNCi8vICoqIERhdGFiYXNlIHNldHRpbmdzIC0gWW91IGNhbiBnZXQgdGhpcyBpbmZvIGZyb20geW91ciB3ZWIgaG9zdCAqKiAvLw0KLyoqIFRoZSBuYW1lIG9mIHRoZSBkYXRhYmFzZSBmb3IgV29yZFByZXNzICovDQpkZWZpbmUoICdEQl9OQU1FJywgJ2RhdGFiYXNlX25hbWVfaGVyZScgKTsNCg0KLyoqIERhdGFiYXNlIHVzZXJuYW1lICovDQpkZWZpbmUoICdEQl9VU0VSJywgJ3VzZXJuYW1lX2hlcmUnICk7DQoNCi8qKiBEYXRhYmFzZSBwYXNzd29yZCAqLw0KZGVmaW5lKCAnREJfUEFTU1dPUkQnLCAncGFzc3dvcmRfaGVyZScgKTsNCg0KLyoqIERhdGFiYXNlIGhvc3RuYW1lICovDQpkZWZpbmUoICdEQl9IT1NUJywgJ2xvY2FsaG9zdCcgKTsNCg0KLyoqIERhdGFiYXNlIGNoYXJzZXQgdG8gdXNlIGluIGNyZWF0aW5nIGRhdGFiYXNlIHRhYmxlcy4gKi8NCmRlZmluZSggJ0RCX0NIQVJTRVQnLCAndXRmOCcgKTsNCg0KLyoqIFRoZSBkYXRhYmFzZSBjb2xsYXRlIHR5cGUuIERvbid0IGNoYW5nZSB0aGlzIGlmIGluIGRvdWJ0LiAqLw0KZGVmaW5lKCAnREJfQ09MTEFURScsICcnICk7DQoNCi8qKiNAKw0KICogQXV0aGVudGljYXRpb24gdW5pcXVlIGtleXMgYW5kIHNhbHRzLg0KICoNCiAqIENoYW5nZSB0aGVzZSB0byBkaWZmZXJlbnQgdW5pcXVlIHBocmFzZXMhIFlvdSBjYW4gZ2VuZXJhdGUgdGhlc2UgdXNpbmcNCiAqIHRoZSB7QGxpbmsgaHR0cHM6Ly9hcGkud29yZHByZXNzLm9yZy9zZWNyZXQta2V5LzEuMS9zYWx0LyBXb3JkUHJlc3Mub3JnIHNlY3JldC1rZXkgc2VydmljZX0uDQogKg0KICogWW91IGNhbiBjaGFuZ2UgdGhlc2UgYXQgYW55IHBvaW50IGluIHRpbWUgdG8gaW52YWxpZGF0ZSBhbGwgZXhpc3RpbmcgY29va2llcy4NCiAqIFRoaXMgd2lsbCBmb3JjZSBhbGwgdXNlcnMgdG8gaGF2ZSB0byBsb2cgaW4gYWdhaW4uDQogKg0KICogQHNpbmNlIDIuNi4wDQogKi8NCmRlZmluZSggJ0FVVEhfS0VZJywgICAgICAgICAnc2pkYWpzYmRoYWpiZHNoamFiMzIzaGpiamgnICk7DQpkZWZpbmUoICdTRUNVUkVfQVVUSF9LRVknLCAgJ21udm5qc25ha2o3ODc4eTIzeWdlcXV3ZGhxJyApOw0KZGVmaW5lKCAnTE9HR0VEX0lOX0tFWScsICAgICduY21ua2FqbnNqdTI4OTc4Z2Ric2FqYmhpcycgKTsNCmRlZmluZSggJ05PTkNFX0tFWScsICAgICAgICAnY2poYWl1c2hmaXUzODdkaGJjaGJzamlpMmgnICk7DQpkZWZpbmUoICdBVVRIX1NBTFQnLCAgICAgICAgJ29pd3VqZGJkamFoc2ppandpdWl1dTczNzNoJyApOw0KZGVmaW5lKCAnU0VDVVJFX0FVVEhfU0FMVCcsICdobmJjdWl3OHliY2JzYWhpODJianNoYmloaScgKTsNCmRlZmluZSggJ0xPR0dFRF9JTl9TQUxUJywgICAneXdndnNjc2Fqc2tqaGFpbmNzamJ1Mjh1NzMnICk7DQpkZWZpbmUoICdOT05DRV9TQUxUJywgICAgICAgJ2Jja2l1MjgyNjdoc2hiY2Jqc2lhODI3YmJiJyApOw0KDQovKiojQC0qLw0KDQovKioNCiAqIFdvcmRQcmVzcyBkYXRhYmFzZSB0YWJsZSBwcmVmaXguDQogKg0KICogWW91IGNhbiBoYXZlIG11bHRpcGxlIGluc3RhbGxhdGlvbnMgaW4gb25lIGRhdGFiYXNlIGlmIHlvdSBnaXZlIGVhY2gNCiAqIGEgdW5pcXVlIHByZWZpeC4gT25seSBudW1iZXJzLCBsZXR0ZXJzLCBhbmQgdW5kZXJzY29yZXMgcGxlYXNlIQ0KICovDQokdGFibGVfcHJlZml4ID0gJ3dwXyc7DQoNCi8qKg0KICogRm9yIGRldmVsb3BlcnM6IFdvcmRQcmVzcyBkZWJ1Z2dpbmcgbW9kZS4NCiAqDQogKiBDaGFuZ2UgdGhpcyB0byB0cnVlIHRvIGVuYWJsZSB0aGUgZGlzcGxheSBvZiBub3RpY2VzIGR1cmluZyBkZXZlbG9wbWVudC4NCiAqIEl0IGlzIHN0cm9uZ2x5IHJlY29tbWVuZGVkIHRoYXQgcGx1Z2luIGFuZCB0aGVtZSBkZXZlbG9wZXJzIHVzZSBXUF9ERUJVRw0KICogaW4gdGhlaXIgZGV2ZWxvcG1lbnQgZW52aXJvbm1lbnRzLg0KICoNCiAqIEZvciBpbmZvcm1hdGlvbiBvbiBvdGhlciBjb25zdGFudHMgdGhhdCBjYW4gYmUgdXNlZCBmb3IgZGVidWdnaW5nLA0KICogdmlzaXQgdGhlIGRvY3VtZW50YXRpb24uDQogKg0KICogQGxpbmsgaHR0cHM6Ly93b3JkcHJlc3Mub3JnL2RvY3VtZW50YXRpb24vYXJ0aWNsZS9kZWJ1Z2dpbmctaW4td29yZHByZXNzLw0KICovDQpkZWZpbmUoICdXUF9ERUJVRycsIGZhbHNlICk7DQoNCi8qIEFkZCBhbnkgY3VzdG9tIHZhbHVlcyBiZXR3ZWVuIHRoaXMgbGluZSBhbmQgdGhlICJzdG9wIGVkaXRpbmciIGxpbmUuICovDQoNCg0KDQovKiBUaGF0J3MgYWxsLCBzdG9wIGVkaXRpbmchIEhhcHB5IHB1Ymxpc2hpbmcuICovDQoNCi8qKiBBYnNvbHV0ZSBwYXRoIHRvIHRoZSBXb3JkUHJlc3MgZGlyZWN0b3J5LiAqLw0KaWYgKCAhIGRlZmluZWQoICdBQlNQQVRIJyApICkgew0KCWRlZmluZSggJ0FCU1BBVEgnLCBfX0RJUl9fIC4gJy8nICk7DQp9DQoNCi8qKiBTZXRzIHVwIFdvcmRQcmVzcyB2YXJzIGFuZCBpbmNsdWRlZCBmaWxlcy4gKi8NCnJlcXVpcmVfb25jZSBBQlNQQVRIIC4gJ3dwLXNldHRpbmdzLnBocCc7DQo';
			
			$file_contents = base64_decode($config_sample_file_contents_enc);

			wptc_log($file_contents, '--------file contents to write------------');

			if(file_put_contents($meta['new_path'] . '/wp-config-sample.php', $file_contents) === FALSE){
				wptc_log(array(), '---------WP CONFIG SAMPLE NOT WRITABLE------------');
			}

			$lines = @file($meta['new_path'] . '/wp-config-sample.php');
		}

		@unlink($meta['new_path'] . '/wp-config.php'); // Unlink if a config already exists

		if (empty($lines)) {
			wptc_log($meta['new_path'] . '/wp-config.php' . ' is not readable.', '---------FAILED------------');
			return ;
		}

		foreach ($lines as $line) {

			if (strstr($line, 'DB_NAME')){
				$line = "define('DB_NAME', '" . $this->wpdb->dbname . "');\n";
			}

			if (strstr($line, 'DB_USER')){
				$line = "define('DB_USER', '" . $this->wpdb->dbuser . "');\n";
			}

			if (strstr($line, 'DB_PASSWORD')){
				$line = "define('DB_PASSWORD', '" . $this->wpdb->dbpassword . "');\n";
			}

			if (strstr($line, 'DB_HOST')){
				$line = "define('DB_HOST', '" . $this->wpdb->dbhost . "');\n";
			}

			if (strstr($line, '$table_prefix')){
				$line = "\$table_prefix = '" . $meta['new_prefix'] . "';\n";
			}

			if (strstr($line, 'WP_HOME') || strstr($line, 'WP_SITEURL')){
				$line = "";
			}

			if (strstr($line, 'WP_CACHE')){
				if($type != 'STAGING_TO_LIVE'){
					$line = "define('WP_CACHE', false);\n";
				}
			}

			if (strstr($line, 'PATH_CURRENT_SITE')){
				if (is_multisite()) {
					continue;
				}

				$staging_args    = parse_url( $meta['new_url'] );
				$line = "define('PATH_CURRENT_SITE', '" . rtrim( $staging_args['path'], "/" ) . "/');\n";
			}

			$line = $this->replace_old_cache_path($line, $meta);

			$line = $this->extra_tinkering($line, $type, $meta);

			if(file_put_contents($meta['new_path'] . '/wp-config.php', $line, FILE_APPEND) === FALSE){
				wptc_log(array(), '---------WP CONFIG NOT WRITABLE------------');
			}
		}

		$this->reset_Wordfence_config($meta);

	}

	private function is_outside_config($lines, $type){
		//outside config fix

		if($type == 'STAGING_TO_LIVE'){
			return false;
		}

		$got_db_name = false;

		foreach ($lines as $line) {

			if (strstr($line, 'DB_NAME')){
				$line = "define('DB_NAME', '" . $this->wpdb->dbname . "');\n";
				$got_db_name = true;
			}

		}

		return !$got_db_name;
	}

	private function extra_tinkering($line, $type, $meta){

		if (empty($type)) {
			return $line;
		}

		if ($type === 'LIVE_TO_STAGING' || $type === 'RESTORE_TO_STAGING') {

			if (strstr($line, 'WP_CONTENT_DIR')){
				return  "define('WP_CONTENT_DIR', '" . wptc_remove_trailing_slash( $meta['new_path'] ) . "/" . WPTC_WP_CONTENT_BASENAME . "');//ADDED_BY_WPTC\n//DISABLED_BY_WPTC " . $line;
			}

			if (strstr($line, 'WP_CONTENT_URL')){
				return  "define('WP_CONTENT_URL', '" . wptc_remove_trailing_slash( $meta['new_url'] ) . "/" . WPTC_WP_CONTENT_BASENAME . "');//ADDED_BY_WPTC\n//DISABLED_BY_WPTC " . $line;
			}
		}

		if ($type === 'STAGING_TO_LIVE') {
			if (strstr($line, '//DISABLED_BY_WPTC')){
				return str_replace('//DISABLED_BY_WPTC ', '', $line);
			}

			if (strstr($line, '//ADDED_BY_WPTC')){
				return '';
			}
		}

		return $line;
	}

	private function replace_old_cache_path($content, $meta){
		return str_replace($meta['old_path'], $meta['new_path'], $content);
	}

	private function reset_Wordfence_config($meta){
		if (file_exists($meta['new_path'] . '.user.ini')) {
			$file = @file_get_contents($meta['new_path'] . '.user.ini');

			if ($file && strlen($file)) {
				$file    = str_replace($meta['old_path'], $meta['new_path'], $file);
				$file = @file_put_contents($meta['new_path'] . '.user.ini', $file);
			} else {
				wptc_log(array(),'----------user.ini update failed-----------------');
			}
		}


		if (!file_exists($meta['new_path'] . 'wordfence-waf.php')) {
			return ;
		}

		$file = @file_get_contents($meta['new_path'] . 'wordfence-waf.php');

		if ($file && strlen($file)) {
			$file    = str_replace($meta['old_path'], $meta['new_path'], $file);
			$file = @file_put_contents($meta['new_path'] . 'wordfence-waf.php', $file);
		} else {
			wptc_log(array(),'----------wordfence-waf.php update failed-----------------');
		}
	}

	public function remove_unwanted_comment_lines($line, $is_wp_config = false){

		if ($is_wp_config) {
			$remove_comment_lines = array('DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST', 'PATH_CURRENT_SITE', 'table_prefix');
		} else {
			$remove_comment_lines = array('Changed by WP Time Capsule');
		}

		foreach ($remove_comment_lines as $comment_lines) {
			if(strpos($line, $comment_lines) !== false){
				return substr($line, 0, strpos($line, "//"));
			}
		}

		return $line;
	}

	public function replace_htaccess($meta = array()){
		wptc_log(func_get_args(), "--------" . __FUNCTION__ . "--------");
		if (empty($meta)) {
			return ;
		}

		$meta['new_path'] = wptc_add_fullpath($meta['new_path']);
		$meta['old_path'] = wptc_add_fullpath($meta['old_path']);

		wptc_log($meta, "--------" . __FUNCTION__ . " after fullpath added--------");
		$file = @file_get_contents($meta['new_path'] . '/.htaccess');

		wptc_log($file,'-----------$file----------------');

		if ($file && strlen($file)) {

			$args    = parse_url($meta['new_url']);
			wptc_log($args,'-----------$args----------------');
			$string  = rtrim($args['path'], "/");
			wptc_log($string,'-----------$string----------------');
			if (is_multisite()) {
				$regex   = "/RewriteBase(.*?)\n(.*?)/sm";
				$replace = "RewriteBase " . $string . "/\n";
			} else {
				$regex   = "/BEGIN WordPress(.*?)RewriteBase(.*?)\n(.*?)RewriteRule \.(.*?)index\.php(.*?)END WordPress/sm";
				$replace = "BEGIN WordPress$1RewriteBase " . $string . "/ \n$3RewriteRule . " . $string . "/index.php$5END WordPress";
			}
			wptc_log($regex,'-----------$regex----------------');
			wptc_log($replace,'-----------$replace----------------');
			$file    = preg_replace($regex, $replace, $file);
			wptc_log($file,'-----------$file----------------');
			$file    = str_replace($meta['old_path'], $meta['new_path'], $file);
			wptc_log($file,'-----------$file----------------');

			if(stripos($file, 'WPTC_MODIFIED_FOR_STAGING')){
				$file = str_replace('#WPTC_MODIFIED_FOR_STAGING', 'RewriteCond %{HTTP_USER_AGENT} "^$" [NC,OR]', $file);
			} else {
				$file = str_replace('RewriteCond %{HTTP_USER_AGENT} "^$" [NC,OR]', '#WPTC_MODIFIED_FOR_STAGING', $file);
			}

			// WP Fastest Cache fix

			$p_quote_old_url = preg_quote($meta['old_url'], '/');
			$p_quote_new_url = preg_quote($meta['new_url'], '/');

			$file    = str_replace($p_quote_old_url, $p_quote_new_url, $file);

			$mu_oldUrlPath = parse_url($meta['old_url'], PHP_URL_PATH);
			$mu_oldUrlPath = ltrim($mu_oldUrlPath, '/');
			$mu_newUrlPath = parse_url($meta['new_url'], PHP_URL_PATH);
			$mu_newUrlPath = ltrim($mu_newUrlPath, '/');

			if(!empty($mu_newUrlPath) && $mu_newUrlPath != '/'){
				$file    = str_replace('/' . $mu_oldUrlPath . '/wp-content', '/' .$mu_newUrlPath . '/wp-content', $file);
				$file    = str_replace('/' . $mu_oldUrlPath . '/$1', '/' .$mu_newUrlPath . '/$1', $file);
			}

			// WP Fastest Cache fix

			wptc_log(".htaccess content modified",'----htaccess content modified-----------------------');

			@file_put_contents($meta['new_path'] . '/.htaccess', $file);
		}

		$this->replace_old_cache_in_htaccess_path($meta['old_path']);

		wptc_log(array(),'-----------.htaccess file changed----------------');

	}

	private function replace_old_cache_in_htaccess_path($meta = array()){
		if ( empty($meta) || !is_array($meta) ) {
			return ;
		}

		if(!file_exists($meta['new_path'] . '/.htaccess')){
			return;
		}

		wptc_log(func_get_args(), "--------" . __FUNCTION__ . "--------");
		$file = @file_get_contents($meta['new_path'] . '/.htaccess');

		wptc_log($file,'-----------$file----------------');

		if ($file && strlen($file)) {
			$file    = str_replace($meta['old_path'], $meta['new_path'], $file);
			@file_put_contents($meta['new_path'] . '/.htaccess', $file);
			wptc_log(array(),'-------old cache path replaced in .htaccess--------------------');
		}
	}

	public function make_cpu_idle(){
		@usleep(10000);
	}
}
