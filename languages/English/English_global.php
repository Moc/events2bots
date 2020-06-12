<?php

// PLUGIN.XML

define("LAN_PLUGIN_E2B_NAME", "Events2Bots");
define("LAN_PLUGIN_E2B_DIZ",  "This plugin allows to send messages to bots (such as Discord and Telegram) based on events in e107 such as the posting of a new news item or a new forum post."); 


// EVENT MESSAGES SENT BY THE BOT

// User
define("LAN_E2B_USER_SIGNUP_SUBMITTED", "A new user has signed up."); 
define("LAN_E2B_USER_SIGNUP_ACTIVATED", "A new user has been activated."); 
define("LAN_E2B_USER_SIGNUP_XUP",		"A new user has signed up using social social login."); 


// News
define("LAN_E2B_NEWS_NEW", 				"A new news item has been posted.");
define("LAN_E2B_NEWS_NEW_CATEGORY", 	"A new news item has been posted in category '[x]'."); 

define("LAN_E2B_NEWS_UPDATED", 			"An existing news item has been updated."); 
define("LAN_E2B_NEWS_UPDATED_CATEGORY", "An existing news item has been updated in category '[x]'."); 


// Forum
define("LAN_E2B_FORUM_TOPIC_CREATED", 			"A new forum topic has been created."); 
define("LAN_E2B_FORUM_TOPIC_CREATED_CATEGORY", 	"A new forum topic has been created in category '[x]'."); 

define("LAN_E2B_FORUM_TOPIC_UPDATED", 			"An existing topic has been updated.");
define("LAN_E2B_FORUM_TOPIC_UPDATED_CATEGORY", 	"An existing topic has been updated in category '[x]'.");

define("LAN_E2B_FORUM_POST_CREATED", 			"A new forum post has been created.");
define("LAN_E2B_FORUM_POST_CREATED_CATEGORY", 	"A new forum post has been created in category '[x]'.");


// Chatbox
define("LAN_E2B_CHATBOX_NEW_POST", 		"A new chatbox message has been posted."); 