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

	public $e2b_debug = false; 

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

		// Check debug mode
		if(e107::getPlugPref('events2bots', 'e2b_debug') == true) 
		{
			$this->e2b_debug = true;
		}
	}
	

	public function init($event_name, $event_data)
	{
		// Check if event exists in database
		if($this->eventPresent($event_name) == false)
		{
			if($this->e2b_debug)
			{
				e107::getAdminLog()->addDebug("(".__LINE__.") Event not found: ".$event_name);
				e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
			}
			
			return; 	
		} 

		// Determine event type (news, user, forum, chatbox) 
		$event_type = $this->isEventType($event_name); 

		if(empty($event_type))
		{
			if($this->e2b_debug)
			{
				e107::getAdminLog()->addDebug("(".__LINE__.") Unknown event type: ".$event_name); // TODO ERROR HANDLING
				e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
			}
			
			return false; 
		} 

		// Retrieve event rules
		if($this->e2b_debug)
		{
			e107::getAdminLog()->addDebug("(".__LINE__.") Retrieving eventrules data from database");
			e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
		}
		
		if($allRows = e107::getDb()->retrieve("e2b_eventrules", "er_id, er_name, er_botid, er_eventname, er_sections", "er_eventname='{$event_name}'", true))
		{
			foreach($allRows as $row)
			{
				//Check if bot ID is set and not "0" - TODO CHECK WHY THIS IS NOT WORKING 
				/*
				$row['er_botid'] = (int) $row['er_botid'];

				if($row['er_botid'] == 0);
				{	
					if($this->e2b_debug)
					{ 
						error_log("Bot ID is 0 (".$row['er_botid']."), abort init");
						//error_log(var_dump($row['er_botid']));
					}
					return false;
				}*/

				// Determine provider
				$provider = $this->isProvider($row['er_botid']); 
				
				if($this->e2b_debug)
				{ 
					e107::getAdminLog()->addDebug("(".__LINE__.") Provider is: ".$provider);
					e107::getAdminLog()->addDebug("(".__LINE__.") Ready to launch!");
					
					e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
				}

				// Launch Event with specified provider
				$this->launchEvent($provider, $event_name, $event_type, $row, $event_data);
			}
		}
		else
		{
			if($this->e2b_debug)
			{
				e107::getAdminLog()->addDebug("(".__LINE__.") No eventrules data?!"); 
				e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
			}	

			return false;
 		}
	}


	private function eventPresent($event_name)
	{
		$count = e107::getDb()->count('e2b_eventrules', '(*)', "er_eventname='{$event_name}'");
		
		if($this->e2b_debug)
		{
			e107::getAdminLog()->addDebug("(".__LINE__.") Event count: ".$count);
			e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
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
					e107::getAdminLog()->addDebug("(".__LINE__.") Init Discord");
					e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
				}
				$discord = new Discord(); 
				$discord->init($event_type, $event_name, $event_rule_data, $event_data);
				break;
			case "Telegram":
				if($this->e2b_debug)
				{
					e107::getAdminLog()->addDebug("(".__LINE__.") Telegram is not supported yet!");
					e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
				}
				break;
			default:
				if($this->e2b_debug)
				{
					e107::getAdminLog()->addDebug("(".__LINE__.") Provider unknown? Provider is: ".$provider);
					e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true); 
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
					e107::getAdminLog()->addDebug("(".__LINE__.") Error: Provider is not found. Provider ID is: ".$provider_id);
					e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
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
		    		e107::getAdminLog()->addDebug("(".__LINE__.") Event type is: ".$k);
		    		e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
		    	}
		    	
		        return $k;
		    }
		}
	}

}