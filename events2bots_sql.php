/*
 * Events2Bots 
 *
 * Copyright (C) 2020 - Tijn Kuyper (Moc)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 * 
*/


CREATE TABLE e2b_bots (
  `bot_id` int(10) NOT NULL AUTO_INCREMENT,
  `bot_avatar` varchar(255) NOT NULL,
  `bot_name` varchar(50) NOT NULL,
  `bot_provider` int(10) NOT NULL,
  `bot_language` varchar(50) NOT NULL,
  `bot_apidata` varchar(255) NOT NULL,
  PRIMARY KEY (`bot_id`)
) ENGINE=MyISAM;


CREATE TABLE e2b_eventrules (
  `er_id` int(10) NOT NULL AUTO_INCREMENT,
  `er_name` varchar(50) NOT NULL,
  `er_botid` int(10) NOT NULL,
  `er_eventname` varchar(255) NOT NULL,
  `er_sections` varchar(250) NOT NULL default '0',                    
  PRIMARY KEY (`er_id`)
) ENGINE=MyISAM;
