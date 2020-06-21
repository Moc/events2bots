<?php
/*
 * Events2Bots 
 *
 * Copyright (C) 2020 - Tijn Kuyper (Moc)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 * 
*/

e107_require_once(e_PLUGIN.'events2bots/vendor/autoload.php');

class Discord
{
    public $webhookurl      = '';  
    public $bot_name        = ''; 
    public $bot_avatar      = '';
    public $bot_language    = "English"; // always default to English for now 
    public $prepared_data   = array();  

    public $e2b_debug       = false; 

    public function __construct()
    {
        // Check debug mode
        if(e107::getPlugPref('events2bots', 'e2b_debug') == true) 
        {
            $this->e2b_debug = true;
        }
    }

    function init($event_type, $event_name, $event_rule_data, $event_data)
    {
        // Define Bot ID
        $bot_id = $event_rule_data['er_botid'];

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") Bot id: ".$bot_id);
            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        // Get bot data based on bot_id and set variables
        if($bot_data = e107::getDb()->retrieve("e2b_bots", "bot_avatar, bot_name, bot_language, bot_apidata", "bot_id='{$bot_id}'"))
        {
            if($this->e2b_debug)
            {
                e107::getAdminLog()->addDebug("(".__LINE__.") Bot data");
                e107::getAdminLog()->addDebug(print_r($bot_data, TRUE));
                e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
            }

            // Set bot name (bot_name)
            $this->bot_name = $bot_data['bot_name'];

            // Set bot language
            //$this->bot_language = $bot_data['bot_language']; // TODO ALLOW LANGUAGES OTHER THAN ENGLISH

            // Check if bot avatar is set for this specific bot
            if(!empty($bot_data["bot_avatar"]))
            {
                $this->bot_avatar = e107::getParser()->replaceConstants($bot_data["bot_avatar"], 'full'); 
            }
            // Use default avatar
            else
            {
                $this->bot_avatar = e107::getParser()->replaceConstants(e107::getPlugPref('events2bots', 'e2b_default_avatar'), 'full'); 
            }

            // Set webhookurl (bot_apidata)
            $this->webhookurl = $bot_data['bot_apidata'];
        }
        else
        {
            if($this->e2b_debug)
            {
                e107::getAdminLog()->addDebug("(".__LINE__.") No bot data?!");
                e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
            }

            return false; 
        }

        switch($event_type)
        {
            case 'user':
                $this->prepareUser($event_name, $event_data);
                break;
            case 'news':
                $this->prepareNews($event_name, $event_data, $event_rule_data);
                break;
            case 'forum':
                $this->prepareForum($event_name, $event_data, $event_rule_data);
                break;
             case 'chatbox':
                $this->prepareChatbox($event_data); 
                break;
            break;
            default:
                if($this->e2b_debug)
                {
                    e107::getAdminLog()->addDebug("(".__LINE__.") Event type not coded yet! Event type: ".$event_type.". Event name: ".$event_name); 
                    e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
                }
                return false;
        }
    }

    function limitChars($input, $length)
    {
        if(strlen($input) <= $length)
        {
            return $input;
        }
        else
        {
            $pos = strpos($input, ' ', $length);
            $y = substr($input, 0, $pos) . '...';
            return $y;
        }
    }

    // USER
    function prepareUser($event_name, $event_data)
    {
        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") User event data: ".$event_name);
            e107::getAdminLog()->addDebug(print_r($event_data, TRUE));

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        /*
        (
            [e-token] => 507b1d36076fc3d3bdea4a4f0d898a29
            [loginname] => TestUsernameAgain
            [email] => again@again.com
            [email2] => 
            [register] => Register
            [hideemail] => 1
            [email_confirm] => again@again.com
            [username] => TestUsernameAgain
        )
        */

        // user_signup_submitted
        if($event_name == "user_signup_submitted")
        {
            $content    = LAN_E2B_USER_SIGNUP_SUBMITTED;
            $username   = $event_data["username"];
            $email      = $event_data["email"];
        }

        if($event_name == "user_signup_activated")
        {
            $content    = LAN_E2B_USER_SIGNUP_ACTIVATED;
            $username   = $event_data["user_name"];
            $email      = $event_data["user_email"];
        }

        $prepared_data = array(
            "content"   => $content, 
            
            // Embeds Array
            "embeds" => [
                [
                    "title"         => $event_data["username"],
                    "description"   => $event_data["email"],
                    
                    //"type"          => "rich",

                    // Footer
                    // "footer" => [
                    //     "text"      => $news_author,
                    //     "icon_url"  => $news_author_avatar,
                    // ],
                ]
            ]
        );

        $this->sendMessage($prepared_data);
    }


    // NEWS
    private function getNewsSection($section_id)
    {
        $section_data = e107::getDb()->retrieve("news_category", "category_name, category_sef", "category_id='{$section_id}'");

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") News section data");
            e107::getAdminLog()->addDebug(print_r($section_data, true));

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        return $section_data; 
    }

    function prepareNews($event_name, $event_data, $event_rule_data)
    {
        $action = '';

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") News event data: ".$event_name);
            e107::getAdminLog()->addDebug(print_r($event_data, true));

            e107::getAdminLog()->addDebug("(".__LINE__.") News event rule data");
            e107::getAdminLog()->addDebug(print_r($event_rule_data, true));

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        // Existing News item updated
        if($event_name == "admin_news_update")
        {
            $news_data              = array_merge($event_data["oldData"], $event_data["newData"]);
            $news_data["news_id"]   = $event_data["oldData"]["news_id"]; // make sure 'news_id' is included in the 'new' news data

            $action = "update"; 
        }
        // New news item created
        else
        {
            $news_data = $event_data["newData"];
            $action = "create";
        }

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") News data");
            e107::getAdminLog()->addDebug(print_r($news_data, true));

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        // Check if specific news categories have been selected, if not, then it is a generic news update message. 
        if(!empty($event_rule_data["er_sections"]))
        {
            // Specific sections are selected. 
            // Create array of sections
            $er_sections = explode(",", $event_rule_data["er_sections"]);

            // Check if the updated news item belongs to one of the selected sections. 
            if(in_array($news_data["news_category"], $er_sections))
            {
                $section_data = $this->getNewsSection($news_data["news_category"]);

                // Set content
                if($action == "update")
                {
                    $content = e107::getParser()->lanVars(LAN_E2B_NEWS_UPDATED_CATEGORY, $section_data["category_name"]);
                }
                else
                {
                    $content = e107::getParser()->lanVars(LAN_E2B_NEWS_NEW_CATEGORY, $section_data["category_name"]);
                }
            }
            // News item does NOT belong to a selected section, so stop the process. No update message is going to be send.
            else
            {
                if($this->e2b_debug)
                {
                    e107::getAdminLog()->addDebug("(".__LINE__.") News item does NOT belong to a selected section");
                    e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
                }   
                              
                return false;   
            }
             
        }
        // No specific section is selected, so display generic message
        else
        {
            $content = ($action == "update") ? LAN_E2B_NEWS_UPDATED : LAN_E2B_NEWS_NEW;   
        }

        // Get user data
        $userdata = e107::user($news_data["news_author"]);

        // Author
        $news_author = $userdata["user_name"];

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") User data");
            e107::getAdminLog()->addDebug(print_r($userdata, true));
            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }   

        // Prepare avatar
        $avatar_options = array(
            'type'      => 'url',
            'w'         => 50,
            'h'         => 50,
            'mode'      => "full", 
        );

        $news_author_avatar = e107::getParser()->toAvatar(array('user_image' => $userdata['user_image']), $avatar_options); 

        // News item titel url 
        $news_url = e107::getUrl()->create('news/view/item', $news_data, array('full' => 1, 'encode' => 0)); 

        //error_log($news_data["news_body"]);

        // Set description (news_body)
        $news_body_notags  = str_replace(array('[html]','[/html]'),'', $news_data["news_body"]); // remove [html] and [/html]
        //error_log($news_body_notags);

        $news_body_notags  = e107::getParser()->replaceConstants($news_body_notags, 'full'); // replace {e_...} constants
        //error_log($news_body_notags);

        $news_body_tohtml  = e107::getParser()->toHTML($news_body_notags, true); // parse bbcodes 
        //error_log($news_body_tohtml);

        $news_body_html = strip_tags($news_body_tohtml, "<a><br><img>");
        //error_log($news_body_html);

        // Convert HTML to Markdown
        $converter  = new League\HTMLToMarkdown\HtmlConverter();
        $news_body  = $converter->convert($news_body_html);
        //error_log($news_body);

        // Limited characters
        $news_body = $this->limitChars($news_body, 1950);
        //error_log($news_body);

        // News (first) thumbnail url
        $news_image_raw = explode(",", $news_data["news_thumbnail"]);
        $news_image_url = e107::getParser()->replaceConstants($news_image_raw[0], 'full'); 
    

        $prepared_data = array(
            "content"   => $content, 
            
            // Embeds Array
            "embeds" => [
                [
                    "title"         => $news_data["news_title"],
                    "description"   => $news_body,
                    
                    //"type"          => "rich",
                    
                    "url"           => $news_url,

                    //"author"        => ["name" => $news_author],

                    // Image to send
                    "image" => [
                        "url" => $news_image_url,
                    ],

                    // Footer
                    "footer" => [
                        "text"      => $news_author,
                        "icon_url"  => $news_author_avatar,
                    ],
                ]
            ]
        );

        $this->sendMessage($prepared_data);
    }


    // FORUM
    private function getForumData($type, $id)
    {
        $sql = e107::getDb();

        if (intval($id) < 1) return false;

        switch($type)
        {
            case 'post':
                $qry = 'SELECT f.forum_id, f.forum_name, f.forum_sef, t.thread_id, t.thread_name, p.post_entry 
                        FROM `#forum_post` AS p
                        LEFT JOIN `#forum_thread` AS t ON (t.thread_id = p.post_thread)
                        LEFT JOIN `#forum` AS f ON (f.forum_id = t.thread_forum_id) 
                        WHERE p.post_id = ' . intval($id);
                break;

            case 'thread':
                $qry = 'SELECT f.forum_id, f.forum_name, f.forum_sef, t.thread_id, t.thread_name
                        FROM `#forum_thread` AS t
                        LEFT JOIN `#forum` AS f ON (f.forum_id = t.thread_forum_id) 
                        WHERE t.thread_id = ' . intval($id);
                break;

            default:
                return false;
        }

        if($sql->gen($qry))
        {
            return $sql->fetch();
        }

        return false;
    }

    /**
     * Checks if post is the initial post which started the topic.
     * Retrieves list of post_id's belonging to one post_thread. When lowest value is equal to input param, return true.
     * Used to prevent deleting of the initial post (so topic shows empty does not get hidden accidently while posts remain in database)
     *
     * @param $postId
     * @return bool true if post is the initial post of the topic (false, if not)
     *
     * @internal param int $postid
     */
    private function isFirstForumPost($postId)
    {
        $sql = e107::getDb();

        $postId = (int)$postId;
        $threadId = $sql->retrieve('forum_post', 'post_thread', 'post_id = '.$postId);

        if($rows = $sql->retrieve('forum_post', 'post_id', 'post_thread = '.$threadId, true))
        {
            $postids = array();

            foreach($rows as $row)
            {
                $postids[] = $row['post_id'];
            }

            if($postId == min($postids))
            {
                return true;
            }
        }
        return false;
    }


    private function getForumSection($section)
    {
        $section_data = e107::getDb()->retrieve("forum", "*", "forum_id='{$section}'");

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") Forum section data");
            e107::getAdminLog()->addDebug(print_r($section_data, true));

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        return $section_data;
    }

    function prepareForum($event_name, $event_data, $event_rule_data)
    {
        // Create an array in which we store all the information we need. 
        $forum_data = array(); 

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") Forum event data: ".$event_name);
            e107::getAdminLog()->addDebug(print_r($event_data, true));

            e107::getAdminLog()->addDebug("(".__LINE__.") Forum event rule data");
            e107::getAdminLog()->addDebug(print_r($event_rule_data, true));

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        // Check for forum topic start. 
        if($event_name == "user_forum_post_created" && $this->isFirstForumPost($event_data["post_id"]) == true)
        {
            if($this->e2b_debug)
            {
                e107::getAdminLog()->addDebug("(".__LINE__.") Forum post appears to be new topic. Let 'user_forum_topic_created' handle this."); 
                e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
            }

            return false; 
        }   



        // user_forum_post_created        
        if($event_name == "user_forum_post_created")
        {
            /*
            (
                [post_user] => 1
                [post_entry] => [html]<p>s33333</p>[/html]
                [post_forum] => 2
                [post_datestamp] => 1591024892
                [post_ip] => 0000:0000:0000:0000:0000:ffff:7f00:0001
                [post_thread] => 21
                [post_id] => 30
            )
            */

            // Put event data in forum_data, so we have everything in one place 
            $forum_data = array_merge($forum_data, $event_data);
            // Get thread data and merge with $forum_data
            $thread_data = $this->getForumData('thread', $event_data["post_thread"]);
            $forum_data = array_merge($thread_data, $forum_data);

            /*
            (
                [forum_name] => Child 1.1
                [forum_sef] => child-1-1
                [thread_id] => 21
                [thread_name] => New topic title
                [post_user] => 1
                [post_entry] => [html]<p>ssef3</p>[/html]
                [post_forum] => 2
                [post_datestamp] => 1591027236
                [post_ip] => 0000:0000:0000:0000:0000:ffff:7f00:0001
                [post_thread] => 21
                [post_id] => 34
            )
            */
        }

        // user_forum_topic_created
        if($event_name == "user_forum_topic_created")
        {
            /*
            (
                [thread_lastuser] => 1
                [thread_user] => 1
                [thread_lastuser_anon] => 
                [thread_lastpost] => 1591011397
                [thread_sticky] => 0
                [thread_name] => ef33
                [thread_forum_id] => 2
                [thread_active] => 1
                [thread_datestamp] => 1591011397
                [thread_options] => 
                [thread_id] => 6
                [thread_sef] => ef33
                [post_id] => 11
            )
            */

             // Put event data in forum_data, so we have everything in one place 
            $forum_data = array_merge($forum_data, $event_data);
            // Get post data and merge with $forum_data
            $thread_data = $this->getForumData('post', $event_data["post_id"]);
            $forum_data = array_merge($thread_data, $forum_data);
        }

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") Forum data");
            e107::getAdminLog()->addDebug(print_r($forum_data, true));

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        // Check for empty forum data. This is likely caused when a 'duplicate post' is created. 
        // Actually is a bug in core that needs fixing (#4201)
        if(empty($forum_data))
        {
            if($this->e2b_debug)
            {
                e107::getAdminLog()->addDebug("(".__LINE__.") No forum data! Probably duplicate post!");
                e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
            }

            return false; 
        }

        // Check if specific news categories have been selected, if not, then it is a generic news update message. 
        if(!empty($event_rule_data["er_sections"]))
        {
            // Specific sections are selected. 
            // Create array of sections
            $er_sections    = explode(",", $event_rule_data["er_sections"]);

            $forum_id       = (isset($forum_data["forum_id"])) ? $forum_data["forum_id"] : $forum_data["thread_forum_id"];
            $section_data   = $this->getForumSection($forum_id);

            // Check if the updated news item belongs to one of the selected sections. 
            if(in_array($forum_data["forum_id"], $er_sections))
            {
                // Set content
                if($event_name == "user_forum_post_created")
                {
                    $content = e107::getParser()->lanVars(LAN_E2B_FORUM_POST_CREATED_CATEGORY, $section_data["forum_name"]);
                }
                else
                {
                    $content = e107::getParser()->lanVars(LAN_E2B_FORUM_TOPIC_CREATED_CATEGORY, $section_data["forum_name"]);
                }
            }
            else
            {
                if($this->e2b_debug)
                {
                    e107::getAdminLog()->addDebug("Forum topic/post does NOT belong to a selected section");
                    e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
                }   
            
                // Forum topic/post does NOT belong to a selected section, so stop the process. No update message is going to be send.  
                return false;   
            }
             
        }
        // No specific section is selected, so display generic message
        else
        {
            $content = ($event_name == "user_forum_post_created") ? LAN_E2B_FORUM_POST_CREATED : LAN_E2B_FORUM_TOPIC_CREATED;   
        }

       
        // Set thread_sef based on thread_name
        $forum_data["thread_sef"] = eHelper::title2sef($forum_data['thread_name']);

        // Set description (post_entry)
        $post_entry_notags = str_replace(array('[html]','[/html]'),'', $forum_data["post_entry"]); // remove [html] and [/html]
        $post_entry_tohtml = e107::getParser()->toHTML($post_entry_notags, true); // parse bbcodes 
        $post_entry_tohtml = strip_tags($post_entry_tohtml, "<a><br><img>"); // remove unwanted tags

        // Convert HTML to Markdown
        $converter          = new League\HTMLToMarkdown\HtmlConverter();
        $forum_post_entry   = $converter->convert($post_entry_tohtml);

        // Limit characters
        $forum_post_entry = $this->limitChars($forum_post_entry, 1950);

        // Set author
        $user_id        = !empty($forum_data["thread_user"]) ? $forum_data["thread_user"] : $forum_data["post_user"];
        $userdata       = e107::user($user_id);

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") User data");
            e107::getAdminLog()->addDebug(print_r($userdata, true));
            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }   

        $forum_author   = $userdata["user_name"];

        // Prepare avatar
        $avatar_options = array(
            'type'      => 'url',
            'w'         => 50,
            'h'         => 50,
            'mode'      => "full", 
        );

        $forum_author_avatar = e107::getParser()->toAvatar(array('user_image' => $userdata['user_image']), $avatar_options); 

        // Set forum URL 
        $forum_url = e107::url("forum", "topic", $forum_data, array("mode" => "full"));
        
        
        // TODO? user_forum_topic_updated 
        

        $prepared_data = array(
            "content"   => $content, 
            
            // Embeds Array
            "embeds" => [
                [
                    "title"         => $forum_data["thread_name"],
                    "description"   => $forum_post_entry,
                    
                    //"type"          => "rich",
                    
                    "url"           => $forum_url,

                    //"author"        => ["name" => $forum_author],

                    // Footer
                    "footer" => [
                        "text"      => $forum_author,
                        "icon_url"  => $forum_author_avatar,
                    ],
                ]
            ]
        );

        $this->sendMessage($prepared_data);

    }


    // CHATBOX
    function prepareChatbox($event_data)
    {
        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") Chatbox event data: ".$event_name);
            e107::getAdminLog()->addDebug(print_r($event_data, true));

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }
        /*
        (
            [id] => 7
            [nick] => 1.Administrator
            [cmessage] => sefsfe
            [datestamp] => 1590849444
            [ip] => 0000:0000:0000:0000:0000:ffff:7f00:0001
        )
        */

        // Prepare username (userid.username)
        $temp = explode('.', $event_data["nick"], 2);
        $chatbox_author = $temp[1];
        
        // Retrieve all userdata
        $userdata = e107::user($temp[0]);

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") User data");
            e107::getAdminLog()->addDebug(print_r($userdata, true));
            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }   

        // Prepare avatar
        $avatar_options = array(
            'type'      => 'url',
            'w'         => 50,
            'h'         => 50,
            'mode'      => "full", 
        );

        $chatbox_author_avatar = e107::getParser()->toAvatar(array('user_image' => $userdata['user_image']), $avatar_options); 

        // Compile prepared data
        $prepared_data = array(
        
            "content" => LAN_E2B_CHATBOX_NEW_POST, 
            
            // Embeds Array
            "embeds" => [
                [
                    "title"         => $chatbox_author,
                    "description"   => $event_data["cmessage"],

                    // Footer
                    "footer" => [
                        "text"      => $chatbox_author,
                        "icon_url"  => $chatbox_author_avatar,
                    ],
                ]
            ]

        );

        // Send the prepared data 
        $this->sendMessage($prepared_data);
    }


    // Method to actually send the message to Discord based on $prepared_data
    function sendMessage($prepared_data)
    {
        // Set bot name
        $prepared_data["username"]      = $this->bot_name;

        // Set bot avatar URL
        $prepared_data["avatar_url"]    = $this->bot_avatar;

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") PRE-JSON");
            e107::getAdminLog()->addDebug(print_r($prepared_data, true));
            
            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }
        
        // JSON encode the prepared message
        $json_data = json_encode($prepared_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") POST-JSON");
            e107::getAdminLog()->addDebug(print_r($json_data, true));
            
            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        // Set cURL options
        $ch = curl_init($this->webhookurl);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        // Send message through cURL
        $response = curl_exec($ch);

        // TODO ERROR HANDLING
        if($this->e2b_debug)
        {
            e107::getAdminLog()->addDebug("(".__LINE__.") JSON response");
            e107::getAdminLog()->addDebug($response);

            e107::getAdminLog()->toFile('events2bots', 'Events2Bots Debug Information', true);
        }

        curl_close($ch);
    }
}