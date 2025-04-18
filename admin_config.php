<?php
// Generated e107 Plugin Admin Area
require_once ('../../class2.php');
if (!getperms('P'))
{
    e107::redirect('admin');
    exit;
}

e107::lan('events2bots', true, true);

class events2bots_adminArea extends e_admin_dispatcher
{

    protected $modes = array(

        'main' => array(
            'controller' 	=> 'e2b_bots_ui',
            'path' 			=> null,
            'ui' 			=> 'e2b_bots_form_ui',
            'uipath' 		=> null
        ),

        'user' => array(
            'controller'    => 'e2b_eventrules_ui',
            'path'          => null,
            'ui'            => 'e2b_user_eventrules_form_ui',
            'uipath'        => null
        ),

        'news' => array(
            'controller' 	=> 'e2b_eventrules_ui',
            'path'			=> null,
            'ui' 			=> 'e2b_news_eventrules_form_ui',
            'uipath' 		=> null
        ),

        'forum' => array(
            'controller'    => 'e2b_eventrules_ui',
            'path'          => null,
            'ui'            => 'e2b_forum_eventrules_form_ui',
            'uipath'        => null
        ),

        'chatbox' => array(
            'controller'    => 'e2b_eventrules_ui',
            'path'          => null,
            'ui'            => 'e2b_chatbox_eventrules_form_ui',
            'uipath'        => null
        ),

    );

    protected $adminMenu = array(

        'main/list' => array(
            'caption' 	=> LAN_E2B_MENU_MANAGEBOTS,
            'perm' 		=> 'P'
        ),
        'main/create' => array(
			'caption'	=> LAN_E2B_MENU_CREATEBOT,
			'perm'		=> 'P'
        ),

        'main/div0' => array(
            'divider' => true
        ),

        'user/list' => array(
            'caption' 	=> LAN_E2B_MENU_ER_USER_MANAGE,
            'perm' 		=> 'P'
        ),
        'user/create' => array(
            'caption' 	=> LAN_E2B_MENU_ER_USER_CREATE,
            'perm' 		=> 'P'
        ),

        'main/div1' => array(
            'divider' => true
        ),

        'news/list' => array(
            'caption'   => LAN_E2B_MENU_ER_NEWS_MANAGE,
            'perm'      => 'P'
        ),
        'news/create' => array(
            'caption'   => LAN_E2B_MENU_ER_NEWS_CREATE,
            'perm'      => 'P'
        ),

        'main/div2' => array(
            'divider' => true
        ),

        'forum/list' => array(
            'caption'   => LAN_E2B_MENU_ER_FORUM_MANAGE,
            'perm'      => 'P'
        ),

        'forum/create' => array(
            'caption'   => LAN_E2B_MENU_ER_FORUM_CREATE,
            'perm'      => 'P'
        ),

        'main/div3' => array(
            'divider' => true
        ),

        'chatbox/list' => array(
            'caption'   => LAN_E2B_MENU_ER_CHATBOX_MANAGE,
            'perm'      => 'P'
        ),

        'chatbox/create' => array(
            'caption'   => LAN_E2B_MENU_ER_CHATBOX_CREATE,
            'perm'      => 'P'
        ),

        'main/div4' => array(
            'divider' => true
        ),

        'main/prefs' => array(
            'caption' 	=> LAN_PREFS,
            'perm' 		=> 'P'
        ),
        
    );

    protected $adminMenuAliases = array(
        'main/edit' => 'main/list'
    );

    protected $menuTitle = 'Events2Bots';
}

class e2b_bots_ui extends e_admin_ui
{

    protected $pluginTitle 	= 'Events2Bots';
    protected $pluginName 	= 'events2bots';
    //	protected $eventName		= 'events2bots-e2b_bots'; // remove comment to enable event triggers in admin.
    protected $table 		= 'e2b_bots';
    protected $pid 			= 'bot_id';
    protected $perPage 		= 10;
    protected $batchDelete 	= true;
    protected $batchExport 	= true;
    protected $batchCopy 	= true;

    //	protected $sortField		= 'somefield_order';
    //	protected $sortParent      = 'somefield_parent';
    //	protected $treePrefix      = 'somefield_title';
    //	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.
    //	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
    protected $listOrder = 'bot_id DESC';

    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect',
            'readParms' => array(),
            'writeParms' => array(),
        ),
        'bot_avatar' => array(
            'title' => LAN_E2B_BOTS_BOTAVATAR,
            'type'  => 'image',
            'data' => 'str',
            'width' => 'auto',
            'help' => '',
            'readParms' => 'thumb=60&thumb_urlraw=0&thumb_aw=60',
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
        ),
        'bot_name' => array(
            'title' => LAN_E2B_BOTS_BOTNAME,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'inline' => true,
            'validate' => true,
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'filter' => true,
        ),
        'bot_provider' => array(
            'title' => LAN_E2B_BOTS_PROVIDER,
            'type' => 'dropdown',
            'data' => 'int',
            'width' => 'auto',
            'validate' => true,
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'filter' => false,
            'batch' => false,
        ),
        'bot_language' => array(
            'title' => ADLAN_132,
            'type' => 'dropdown',
            'data' => 'int',
            'width' => 'auto',
            'inline' => true,
            'validate' => true,
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
        ),
        // TODO bot_apidata needs updating for Telegram
        'bot_apidata' => array(
            'title' => LAN_E2B_BOTS_BOTAPI,
            'type' => 'textarea',
            'data' => 'str',
            'width' => 'auto',
            'inline' => false,
            'validate' => true,
            'help' => LAN_E2B_BOTS_BOTAPI_HELP, 
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
        ),
        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true,
            'readParms' => array(),
            'writeParms' => array(),
        ),
    );

    protected $fieldpref = array(
        'bot_name',
        'bot_avatar',
        'bot_provider',
        'bot_language'
    );

    
    //protected $preftabs = array('Bot defaults', 'Advanced');
    
    protected $prefs = array(
        'e2b_default_avatar' => array(
            'title'         => LAN_E2B_PREFS_DEFAULTAVATAR,
            'tab'           => 0,
            'type'          => 'image',
            'data'          => 'str',
            'help'          => LAN_E2B_PREFS_DEFAULTAVATAR_HELP,
            'writeParms'    => array()
        ),
        'e2b_default_language' => array(
            'title'         => LAN_E2B_PREFS_DEFAULTLAN,
            'tab'           => 0,
            'type'          => 'lanlist',
            'data'          => 'str',
            'help'          => LAN_E2B_PREFS_DEFAULTLAN_HELP,
            'writeParms'    => array()
        ),
        'e2b_debug' => array(
            'title'         => LAN_E2B_PREFS_DEBUGMODE,
            'tab'           => 0,
            'type'          => 'boolean',
            'data'          => 'int',
            'help'          => LAN_E2B_PREFS_DEBUGMODE_HELP,
            'writeParms'    => array()
        ),
    );

    public function init()
    {
        // This code may be removed once plugin development is complete.
        if (!e107::isInstalled('events2bots'))
        {
            e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
        }

        // Check debug mode
        if(e107::getPlugPref('events2bots', 'e2b_debug'))
        {
            e107::getMessage()->addWarning("Events2Bots debug mode is <strong>enabled</strong>!"); // DO NOT TRANSLATE
        }

        // Supported providers
        $this->fields['bot_provider']['writeParms']['optArray'] = array(
            1 => "Discord",
            //2 => "Telegram",
        );

        // Supported languages (based on files in the languages folder)
       	$landirs	= scandir(e_PLUGIN."events2bots/languages/");
       	$landirs  	= array_diff($landirs, array('.', '..'));
       	//print_a($landirs);

        $this->fields['bot_language']['writeParms']['optArray'] = $landirs;
    }

    // ------- Customize Create --------
    public function beforeCreate($new_data, $old_data)
    {
        return $new_data;
    }

    public function afterCreate($new_data, $old_data, $id)
    {
        // do something
        
    }

    public function onCreateError($new_data, $old_data)
    {
        // do something
        
    }

    // ------- Customize Update --------
    public function beforeUpdate($new_data, $old_data, $id)
    {
        return $new_data;
    }

    public function afterUpdate($new_data, $old_data, $id)
    {
        // do something
        
    }

    public function onUpdateError($new_data, $old_data, $id)
    {
        // do something
        
    }

    /**
	 * Prevent deletion of bots in use
	 *
	 * @param $data
	 * @param $id
	 * @return bool
	 */
	public function beforeDelete($data, $id)
	{

		if(e107::getDb()->count('e2b_eventrules', '(*)', 'er_botid='.intval($id)))
		{
            $botdelete_error = e107::getParser()->lanVars(LAN_E2B_BOTS_DELETEERROR, $data['bot_name'], true); 
			e107::getMessage()->addError($botdelete_error);
			return false;
		}
		return true;
	}

    // left-panel help menu area. (replaces e_help.php used in old plugins)
    public function renderHelp()
    {
        $caption    = LAN_HELP;
        $help_url   = "https://tijnk.gitbook.io/events2bots/";
        $text  = str_replace(array("[", "]"), array("<a href='https://tijnk.gitbook.io/events2bots/' target='_blank'>", "</a>"), LAN_E2B_HELP);

        return array(
            'caption' 	=> $caption,
            'text' 		=> $text
        );
    }
}

class e2b_bots_form_ui extends e_admin_form_ui
{
}

class e2b_eventrules_ui extends e_admin_ui
{
    protected $pluginTitle 	= 'Events2Bots';
    protected $pluginName 	= 'events2bots';
    //	protected $eventName		= 'events2bots-e2b_eventrules'; // remove comment to enable event triggers in admin.
    protected $table 		= 'e2b_eventrules';
    protected $pid 			= 'er_id';
    protected $perPage 		= 10;
    protected $batchDelete 	= true;
    protected $batchExport 	= true;
    protected $batchCopy 	= false;

    //	protected $sortField		= 'somefield_order';
    //	protected $sortParent      = 'somefield_parent';
    //	protected $treePrefix      = 'somefield_title';
    //	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

    protected $listOrder = 'er_id ASC';

    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect',
            'readParms' => array(),
            'writeParms' => array(),
        ) ,
        'er_id' => array(
            'title' => LAN_ID,
            'type' => 'number',
            'data' => 'int',
            'width' => '5%',
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            //'forced' => '1',
        ),
        'bot_avatar' => array(
            'title' => LAN_E2B_BOTS_BOTAVATAR,
            'type'  => 'image',
            'data' => 'str',
            'width' => 'auto',
            'help' => '',
            'readParms' => 'thumb=60&thumb_urlraw=0&thumb_aw=60',
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'forced' => '1',
            'noedit' => true, 
        ),
        'er_botid' => array(
            'title' => LAN_E2B_BOTS_BOTNAME,
            'type' => 'dropdown',
            'data' => 'str',
            'width' => 'auto',
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'validate' => true,
            'filter' => true,
        ),
        'er_name' => array(
            'title' => LAN_TITLE,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'inline' => true,
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'validate' => true,
        ),
        'er_eventname' => array(
            'title' => LAN_E2B_ER_EVENT,
            'type' => 'radio',
            'data' => 'str',
            'width' => 'auto',
            'help' => '',
            'readParms' => array(),
            'writeParms' => array("sep" => "<br />"),
            'class' => 'left',
            'thclass' => 'left',
            'filter' => true,
            'batch' => false,
            'validate' => true,
        ) ,
        'er_sections' => array(
            'title' => LAN_E2B_ER_SECTIONS,
            'type' => 'hidden',
            'data' => 'str',
            'width' => 'auto',
            'help' => LAN_E2B_ER_SECTIONS_HELP,
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'filter' => false,
            'batch' => false,
        ) ,
        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true,
            'readParms' => array(),
            'writeParms' => array(),
        ) ,
    );

    protected $fieldpref = array(
        'er_id',
        'bot_avatar',
        'er_botid',
        'er_name', 
        'er_selectedevents'
    );

    public function init()
    {
        // This code may be removed once plugin development is complete.
        if(!e107::isInstalled('events2bots'))
        {
            e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
        }

        if(!e107::getDb()->count("e2b_bots"))
        {
            e107::getMessage()->addWarning(LAN_E2B_ER_NOBOTSYET);
        }

        // Bots selection
        $this->er_botid[0] = "Select bot...";
        if(e107::getDb()->select('e2b_bots'))
        {
            while ($row = e107::getDb()->fetch())
            {
                $this->er_botid[$row['bot_id']] = $row['bot_name'];
            }
        }

        $this->fields['er_botid']['writeParms'] = $this->er_botid; 

        
        // Set $listQry and $events array
        switch ($this->getMode()) 
        {
            case 'user':
                    $events = array(
                        "user_signup_submitted" => LAN_E2B_EVENT_USER_SIGNUP_SUBMITTED,
                        "user_signup_activated" => LAN_E2B_EVENT_USER_SIGNUP_ACTIVATED,
                    );

                    $this->listQry = 
                        "SELECT er.*, b.bot_avatar
                        FROM `#e2b_eventrules` as er
                        LEFT JOIN `#e2b_bots` as b
                        ON er.er_botid = b.bot_id
                        WHERE (er.er_eventname = 'user_signup_submitted' OR er.er_eventname = 'user_signup_activated')
                        ";
                break;
            case 'news':
                    $events = array(
                        "admin_news_created" => LAN_E2B_EVENT_ADMIN_NEWS_CREATE,
                        "admin_news_updated" => LAN_E2B_EVENT_ADMIN_NEWS_UPDATE,
                    );

                    $this->listQry = 
                        "SELECT er.*, b.bot_avatar
                        FROM `#e2b_eventrules` as er
                        LEFT JOIN `#e2b_bots` as b
                        ON er.er_botid = b.bot_id
                        WHERE (er.er_eventname = 'admin_news_created' OR er.er_eventname = 'admin_news_updated')
                        ";
                break;
            case 'forum':
                    if(!e107::isInstalled('forum'))
                    {
                        e107::getMessage()->addError("Forum plugin not installed!");
                    }

                    $events = array(
                        "user_forum_topic_created" => LAN_E2B_EVENT_USER_FORUM_TOPIC_CREATED,
                        "user_forum_post_created"  => LAN_E2B_EVENT_USER_FORUM_POST_CREATED,
                    );

                    $this->listQry = 
                        "SELECT er.*, b.bot_avatar
                        FROM `#e2b_eventrules` as er
                        LEFT JOIN `#e2b_bots` as b
                        ON er.er_botid = b.bot_id
                        WHERE (er.er_eventname = 'user_forum_topic_created' OR er.er_eventname = 'user_forum_post_created')
                        ";
                break;
            case 'chatbox':
                    if(!e107::isInstalled('chatbox_menu'))
                    {
                        e107::getMessage()->addError("Chatbox plugin not installed!");
                    }

                    $events = array(
                        "user_chatbox_post_created" => LAN_E2B_EVENT_USER_CHATBOX_POST_CREATED,
                    );

                    $this->listQry = 
                        "SELECT er.*, b.bot_avatar
                        FROM `#e2b_eventrules` as er
                        LEFT JOIN `#e2b_bots` as b
                        ON er.er_botid = b.bot_id
                        WHERE (er.er_eventname = 'user_chatbox_post_created')
                        ";
                break;
            default:
                # code...
                break;
        }
        
        // Set er_eventname column 
        foreach($events as $id => $value)
        {
            $this->er_eventname[$id] = $value; 
        }

        $this->fields['er_eventname']['writeParms']['optArray'] = $this->er_eventname; 


        // Check modes and hide 'er_sections'
        $sectionsEnabled = array("news", "forum");

        if(in_array($this->getMode(), $sectionsEnabled))
        {
            // Enable column display 
            $this->$fieldpref = array_push($this->fieldpref, "er_sections");

            // Set type, readParms and writeParms
            $this->fields['er_sections']['type']        = "dropdown";
            $this->fields['er_sections']['readParms']   = array('type' => 'checkboxes');
            $this->fields['er_sections']['writeParms']  = array('multiple' => '1');
          
            // Fill selection checkboxes with data
            switch ($this->getMode()) {
                case 'forum':
                    $forums = e107::getDb()->retrieve("forum", "*", "forum_parent <> 0", true);

                    foreach($forums as $forum) 
                    {
                        $sections[$forum['forum_id']] = $forum['forum_name'];
                    }
                    break;
                case 'news':
                    $newscats = e107::getDb()->retrieve("news_category", "*", "", true);
                    
                    foreach($newscats as $newscat) 
                    {
                        $sections[$newscat['category_id']] = $newscat['category_name'];
                    }
                    break;
                default:
                    # code...
                    break;
            }

            $this->fields['er_sections']['writeParms']['optArray'] =  $sections;

        }
        
    }

    // ------- Customize Create --------
    public function beforeCreate($new_data, $old_data)
    {
        return $new_data;
    }

    public function afterCreate($new_data, $old_data, $id)
    {
        // do something
        
    }

    public function onCreateError($new_data, $old_data)
    {
        // do something
        
    }

    // ------- Customize Update --------
    public function beforeUpdate($new_data, $old_data, $id)
    {
        return $new_data;
    }

    public function afterUpdate($new_data, $old_data, $id)
    {
        // do something
        
    }

    public function onUpdateError($new_data, $old_data, $id)
    {
        // do something
        
    }

    // left-panel help menu area. (replaces e_help.php used in old plugins)
    public function renderHelp()
    {
        $caption    = LAN_HELP;
        $help_url   = "https://tijnk.gitbook.io/events2bots/";
        $text  = str_replace(array("[", "]"), array("<a href='https://tijnk.gitbook.io/events2bots/' target='_blank'>", "</a>"), LAN_E2B_HELP);

        return array(
            'caption'   => $caption,
            'text'      => $text
        );
    }

}

class e2b_user_eventrules_form_ui extends e_admin_form_ui
{
}


class e2b_news_eventrules_form_ui extends e_admin_form_ui
{
}

class e2b_forum_eventrules_form_ui extends e_admin_form_ui
{
}

class e2b_chatbox_eventrules_form_ui extends e_admin_form_ui
{
}


new events2bots_adminArea();

require_once (e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();

require_once (e_ADMIN . "footer.php");
exit;