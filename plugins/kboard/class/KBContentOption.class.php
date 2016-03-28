<?php
/**
 * KBoard 게시글 옵션
 * @link www.cosmosfarm.com
 * @copyright Copyright 2013 Cosmosfarm. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.html
 */
class KBContentOption {
	
	private $content_uid;
	private $row;
	
	public function __construct($content_uid=''){
		$this->row = new stdClass();
		if($content_uid) $this->initWithContentUID($content_uid);
	}
	
	public function __get($key){
		$key = esc_sql($key);
		if(isset($this->row->{$key}) && $this->row->{$key}){
			return stripslashes($this->row->{$key});
		}
		return '';
	}
	
	public function __set($key, $value){
		global $wpdb;
		if($this->content_uid){
			$key = esc_sql($key);
			$value = esc_sql($value);
			if($value){
				$wpdb->query("INSERT INTO `{$wpdb->prefix}kboard_board_option` (`content_uid`, `option_key`, `option_value`) VALUE ('$this->content_uid', '$key', '$value') ON DUPLICATE KEY UPDATE `option_value`='$value'");
			}
			else{
				$wpdb->query("DELETE FROM `{$wpdb->prefix}kboard_board_option` WHERE `content_uid`='$this->content_uid' AND `option_key`='$key'");
			}
			$this->row->{$key} = $value;
		}
	}
	
	public function initWithContentUID($content_uid){
		global $wpdb;
		$this->row = new stdClass();
		$this->content_uid = intval($content_uid);
		$results = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}kboard_board_option` WHERE `content_uid`='$this->content_uid'");
		foreach($results as $row){
			$this->row->{$row->option_key} = $row->option_value;
		}
	}
}
?>