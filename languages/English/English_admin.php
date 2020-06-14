<?php

// Menu
define("LAN_E2B_MENU_MANAGEBOTS", 	"Manage Bots");
define("LAN_E2B_MENU_CREATEBOT", 	"Create a bot"); 

define("LAN_E2B_MENU_ER_USER_MANAGE", 	"Manage User event rules");
define("LAN_E2B_MENU_ER_USER_CREATE", 	"Create User event rules");

define("LAN_E2B_MENU_ER_NEWS_MANAGE", 	"Manage News event rules");
define("LAN_E2B_MENU_ER_NEWS_CREATE", 	"Create News event rules"); 

define("LAN_E2B_MENU_ER_FORUM_MANAGE", 	"Manage Forum event rules");
define("LAN_E2B_MENU_ER_FORUM_CREATE", 	"Create Forum event rules");

define("LAN_E2B_MENU_ER_CHATBOX_MANAGE", "Manage Chatbox event rules");
define("LAN_E2B_MENU_ER_CHATBOX_CREATE", "Create Chatbox event rules");


// Bots
define("LAN_E2B_BOTS_BOTNAME", 		"Bot name");
define("LAN_E2B_BOTS_BOTAVATAR", 	"Bot avatar");
define("LAN_E2B_BOTS_PROVIDER", 	"Provider");
define("LAN_E2B_BOTS_BOTAPI",		"Bot API data");
define("LAN_E2B_BOTS_BOTAPI_HELP",	"Enter the API data/Webhook URL here");

define("LAN_E2B_BOTS_DELETEERROR", 	"Can not delete bot [x] - it is still in use by an event rule!"); // keep [x] as it is replaced by the bots name

define("LAN_E2B_HELP", 				"Please see the [documentation] website for more information."); // keep the [ ] brackets, it is used to create a clickable link 


// Event rules
define("LAN_E2B_ER_EVENT", 			"Event");
define("LAN_E2B_ER_SECTIONS", 		"Sections");
define("LAN_E2B_ER_SECTIONS_HELP", 	"Select specific sections for this event");

define("LAN_E2B_ER_NOBOTSYET", 		"No bots have been added yet! Please create a bot first before creating an event rule.");

// Events
define("LAN_E2B_EVENT_USER_SIGNUP_SUBMITTED", 		"New user registration");
define("LAN_E2B_EVENT_USER_SIGNUP_ACTIVATED", 		"New user has activated their account");

define("LAN_E2B_EVENT_ADMIN_NEWS_CREATE", 			"New news item posted");
define("LAN_E2B_EVENT_ADMIN_NEWS_UPDATE", 			"Existing news item updated");

define("LAN_E2B_EVENT_USER_FORUM_TOPIC_CREATED", 	"New forum topic created");
define("LAN_E2B_EVENT_USER_FORUM_POST_CREATED", 	"New forum post created");

define("LAN_E2B_EVENT_USER_CHATBOX_POST_CREATED", 	"New chatbox message posted"); 


// Preferences
define("LAN_E2B_PREFS_DEFAULTAVATAR", 		"Default bot avatar");
define("LAN_E2B_PREFS_DEFAULTAVATAR_HELP", 	"The default bot avatar that is shown, when not configured for a specific bot.");

define("LAN_E2B_PREFS_DEFAULTLAN", 			"Default bot language");
define("LAN_E2B_PREFS_DEFAULTLAN_HELP", 	"The default lanuage that the bot's messages are shown in, when not configured for a specific bot.");

define("LAN_E2B_PREFS_DEBUGMODE", 			"Debug mode");
define("LAN_E2B_PREFS_DEBUGMODE_HELP", 		"Debug mode logs debug data which can is useful when troubleshooting or developing.");