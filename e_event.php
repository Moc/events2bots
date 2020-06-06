<?php
/*
 * Events2Bots 
 *
 * Copyright (C) 2020 - Tijn Kuyper (Moc)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 * 
*/

if (!defined('e107_INIT')) { exit; }

require_once(e_PLUGIN."events2bots/e2b_class.php");

class events2bots_event 
{

	function config()
	{

		$event = array();

		// User
		$event[] = array(
			'name'		=> "user_signup_submitted", 
			'function'	=> "init_e2b",
		);

		$event[] = array(
			'name'		=> "user_signup_activated", 
			'function'	=> "init_e2b",
		);

		// $event[] = array(
		// 	'name'		=> "user_xup_signup", 
		// 	'function'	=> "init_e2b",
		// );


		// News
		$event[] = array(
			'name'		=> "admin_news_create", 
			'function'	=> "init_e2b",
		);

		$event[] = array(
			'name'		=> "admin_news_update", 
			'function'	=> "init_e2b",
		);


		// Forum
		$event[] = array(
			'name'		=> "user_forum_topic_created", 
			'function'	=> "init_e2b",
		);

		// $event[] = array(
		// 	'name'		=> "user_forum_topic_updated", 
		// 	'function'	=> "init_e2b",
		// );

		$event[] = array(
			'name'		=> "user_forum_post_created", 
			'function'	=> "init_e2b",
		);


		// Chatbox
		$event[] = array(
			'name'		=> "user_chatbox_post_created", 
			'function'	=> "init_e2b",
		);

		return $event;

	}

	// Initialise Events2Bots class 
	function init_e2b($data, $eventname) 
	{
		$events2bots = new events2bots();
		$events2bots->init($eventname, $data);
	}

} 