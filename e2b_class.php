<?php
/*
 * Events2Bots 
 *
 * Copyright (C) 2020 - Tijn Kuyper (Moc)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 * 
*/

require_once(e_PLUGIN."events2bots/providers/discord_class.php");

class events2bots
{
	public $supported_events = array();

	public $e2b_debug = true; // TODO: add preference and set to 'false' by default

	public function __construct() 
	{
		$this->supported_events = array(
			"user" => array(
				"user_signup_submitted",
				"user_signup_activated",
				//"user_xup_signup",
			),
			"news" => array(
				"admin_news_create", 
				"admin_news_update", 
			),
			"forum" => array(
				"user_forum_topic_created",
				//"user_forum_topic_updated",
				"user_forum_post_created", 
			),
			"chatbox" => array(
				"user_chatbox_post_created",
			),
		);

		// TODO
		// if pref set debug to true; 
		// if($pref)
		// {
		// 	$this->e2b_debug = true; // TODO
		// }
	}
	

	public function init($event_name, $event_data)
	{
		// Check if event exists in database
		if($this->eventPresent($event_name) == false)
		{
			if($this->e2b_debug)
			{
				error_log("Event not found: ".$event_name);
			}
			
			return; 	
		} 

		// Determine event type (news, user, forum, chatbox) 
		$event_type = $this->isEventType($event_name); 

		if(empty($event_type))
		{
			if($this->e2b_debug)
			{
				error_log("Unknown event type?!"); // TODO ERROR HANDLING
			}
			
			return false; 
		} 

		// Retrieve event rules
		if($this->e2b_debug)
		{
			error_log("Retrieving eventrules data from database");
		}
		
		if($allRows = e107::getDb()->retrieve("e2b_eventrules", "er_id, er_name, er_botid, er_eventname, er_sections", "er_eventname='{$event_name}'", true))
		{
			foreach($allRows as $row)
			{
				// Determine provider
				$provider = $this->isProvider($row['er_botid']); 
				if($this->e2b_debug)
				{ 
					error_log("Provider is: ".$provider);
				}

				// Launch Event with specified provider
				if($this->e2b_debug)
				{
					error_log("Ready to launch!");
				}
				
				$this->launchEvent($provider, $event_name, $event_type, $row, $event_data);
			}
		}
		else
		{
			if($this->e2b_debug)
			{
				error_log("No eventrules data?!"); // TODO ERROR HANDLING
			}	

			return false;
 		}
	}


	private function eventPresent($event_name)
	{
		$count = e107::getDb()->count('e2b_eventrules', '(*)', "er_eventname='{$event_name}'");
		
		if($this->e2b_debug)
		{
			error_log("Event count: ".$count);
		}
		
		return $count;
	}

	private function launchEvent($provider, $event_name, $event_type, $event_rule_data, $event_data)
	{
		switch($provider)
		{
			case 'Discord':
				if($this->e2b_debug)
				{
					error_log("Init Discord");
				}
				$discord = new Discord(); 
				$discord->init($event_type, $event_name, $event_rule_data, $event_data);
				break;
			case "Telegram":
				if($this->e2b_debug)
				{
					error_log("Telegram is not supported yet!");
				}
				break;
			default:
				if($this->e2b_debug)
				{
					error_log("Provider unknown? Provider is: ".$provider); // TODO, error handling, should not happen
				}		
		} 

	}


	private function isProvider($bot_id)
	{
		/*
		Providers:
			1 - Discord
 			2 - Telegram
		*/

 		$bot_provider = e107::getDb()->retrieve("e2b_bots", "bot_provider", "bot_id='{$bot_id}'");

		switch($bot_provider)
		{
			// Discord
			case '1':
				$provider = "Discord";
				break;
			// Telegram
			case '2':
				$provider = "Telegram";
				break;
			// TODO fix fallback - error catching for default
			default:
				if($this->e2b_debug)
				{
					error_log("Error: Provider is not found. Provider ID is: ".$provider_id);
				}
				$provider = "DEFAULT"; 
		}

		return $provider;
	}

	private function isEventType($event_name)
	{
		foreach($this->supported_events as $k => $arr)
		{
		    if(in_array($event_name, $arr))
		    {
		    	if($this->e2b_debug)
		    	{
		    		error_log("Event type is: ".$k);
		    	}
		    	
		        return $k;
		    }
		}
	}

}