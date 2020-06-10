<?php
// Generated e107 Plugin Admin Area
require_once ('../../class2.php');
if (!getperms('P'))
{
    e107::redirect('admin');
    exit;
}

// e107::lan('events2bots',true);

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
            'controller'    => 'e2b_user_eventrules_ui',
            'path'          => null,
            'ui'            => 'e2b_user_eventrules_form_ui',
            'uipath'        => null
        ),

        'news' => array(
            'controller' 	=> 'e2b_news_eventrules_ui',
            'path'			=> null,
            'ui' 			=> 'e2b_news_eventrules_form_ui',
            'uipath' 		=> null
        ),

    );

    protected $adminMenu = array(

        'main/list' => array(
            'caption' 	=> "Manage Bots",
            'perm' 		=> 'P'
        ),
        'main/create' => array(
			'caption'	=> "Create a bot",
			'perm'		=> 'P'
        ),

        'main/div0' => array(
            'divider' => true
        ),

        'user/list' => array(
            'caption' 	=> "Manage User event rules",
            'perm' 		=> 'P'
        ),
        'user/create' => array(
            'caption' 	=> "Create User event rules",
            'perm' 		=> 'P'
        ),

        'main/div1' => array(
            'divider' => true
        ),

        'news/list' => array(
            'caption'   => "Manage News event rules",
            'perm'      => 'P'
        ),
        'news/create' => array(
            'caption'   => "Create News event rules",
            'perm'      => 'P'
        ),

        'main/div2' => array(
            'divider' => true
        ),

        'main/prefs' => array(
            'caption' 	=> LAN_PREFS,
            'perm' 		=> 'P'
        ),

        // 'main/div0'      => array('divider'=> true),
        // 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P'),
        
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
            'title' => 'Avatar',
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
            'title' => LAN_NAME,
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
            'title' => 'Provider',
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
            'title' => 'Language',
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
        'bot_apidata' => array(
            'title' => 'Bot API data',
            'type' => 'textarea',
            'data' => 'str',
            'width' => 'auto',
            'inline' => false,
            'validate' => true,
            'help' => '',
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

    //	protected $preftabs        = array('General', 'Other' );
    protected $prefs = array(
        'e2b_default_avatar' => array(
            'title' => 'E2b_default_avatar',
            'tab' => 0,
            'type' => 'image',
            'data' => 'str',
            'help' => 'Default avatar of a bot (if not selected)',
            'writeParms' => array()
        ),
        'e2b_default_language' => array(
            'title' => 'E2b_default_language',
            'tab' => 0,
            'type' => 'language',
            'data' => 'str',
            'help' => 'Default language of a bot (if not selected)',
            'writeParms' => array()
        ),
    );

    public function init()
    {
        // This code may be removed once plugin development is complete.
        if (!e107::isInstalled('events2bots'))
        {
            e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
        }

        // Supported providers
        $this->fields['bot_provider']['writeParms']['optArray'] = array(
            1 => "Discord",
            //2 => "Telegram",
        );


        // Supported languages (based on files in the languages folder)
       	//$supported_languages = glob(e_PLUGIN."events2bots/languages/*" , GLOB_ONLYDIR);
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
			e107::getMessage()->addError("Can't delete <strong>{$data['bot_name']}</strong> - it is still in use!");
			return false;
		}
		return true;
	}

    // left-panel help menu area. (replaces e_help.php used in old plugins)
    public function renderHelp()
    {
        $caption = LAN_HELP;
        $text = 'Some help text';

        return array(
            'caption' 	=> $caption,
            'text' 		=> $text
        );

    }

    /*
    // optional - a custom page.
    public function customPage()
    {
    $text = 'Hello World!';
    $otherField  = $this->getController()->getFieldVar('other_field_name');
    return $text;
    
    }
    
    
    
    
    */

}

class e2b_bots_form_ui extends e_admin_form_ui
{

}

class e2b_user_eventrules_ui extends e_admin_ui
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

    protected $listQry      	= 
    "
        SELECT er.*, b.bot_avatar
        FROM `#e2b_eventrules` as er
        LEFT JOIN `#e2b_bots` as b
        ON er.er_botid = b.bot_id
        WHERE (er.er_eventname = 'user_signup_submitted' OR er.er_eventname = 'user_signup_activated')
    "; 

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
            'title' => "ER ID",
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
            'title' => 'Bot Avatar',
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
            'title' => "Bot",
            'type' => 'dropdown',
            'data' => 'str',
            'width' => 'auto',
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'validate' => true,
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
            'title' => 'Event name',
            'type' => 'radio',
            'data' => 'str',
            'width' => 'auto',
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'filter' => true,
            'batch' => false,
            'validate' => true,
        ) ,
        'er_sections' => array(
            'title' => 'Sections',
            'type' => 'hidden',
            'data' => 'str',
            'width' => 'auto',
            'help' => '',
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
        if (!e107::isInstalled('events2bots'))
        {
            e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
        }

        // Bots
        $this->er_botid[0] = "Select bot...";
        if(e107::getDb()->select('e2b_bots'))
        {
            while ($row = e107::getDb()->fetch())
            {
                $this->er_botid[$row['bot_id']] = $row['bot_name'];
            }
        }

        $this->fields['er_botid']['writeParms'] = $this->er_botid; 

        
        
        // Events
        $user_events = array(
            "user_signup_submitted" => "New user registration",
            "user_signup_activated" => "New user has activated their account",
        );
        
        //print_a($user_events);

        //$this->er_eventname[0] = "Select event...";

        foreach($user_events as $id => $value)
        {
            $this->er_eventname[$id] = $value; 
        }

        $this->fields['er_eventname']['writeParms'] = $this->er_eventname; 

        
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
        $caption = LAN_HELP;
        $text = 'Some help text';

        return array(
            'caption' 	=> $caption,
            'text' 		=> $text
        );

    }

}

class e2b_user_eventrules_form_ui extends e_admin_form_ui
{

    // Custom Method/Function
    function er_eventname($curVal, $mode)
    {
        $user_events = array(
            "user_signup_submitted" => "New user registration",
            "user_signup_activated" => "New user has activated their account",
        );

        switch ($mode)
        {
            case 'read': // List Page
                return $user_events[$curVal]. " (".$curVal.")";
            break;
            case 'write': // Edit Page
                return $this->text('er_selectedevents', $curVal, 255, 'size=large');
            break;
            case 'filter':
            break;
            case 'batch':
            break;
        }

        return null;
    }

    // Custom Method/Function
    function er_botid($curVal, $mode)
    {
        $user_events = array(
            "user_signup_submitted" => "New user registration",
            "user_signup_activated" => "New user has activated their account",
        );

        switch ($mode)
        {
            case 'read': // List Page
                return $curVal;
            break;
            case 'write': // Edit Page
                //return $this->text('er_selectedevents', $curVal, 255, 'size=large');
                return "dropdown here";
            break;
            case 'filter':
            break;
            case 'batch':
            break;
        }

        return null;
    }

}

class e2b_news_eventrules_ui extends e_admin_ui
{

    protected $pluginTitle  = 'Events2Bots';
    protected $pluginName   = 'events2bots';
    //  protected $eventName        = 'events2bots-e2b_eventrules'; // remove comment to enable event triggers in admin.
    protected $table        = 'e2b_eventrules';
    protected $pid          = 'er_id';
    protected $perPage      = 10;
    protected $batchDelete  = true;
    protected $batchExport  = true;
    protected $batchCopy    = true;

    //  protected $sortField        = 'somefield_order';
    //  protected $sortParent      = 'somefield_parent';
    //  protected $treePrefix      = 'somefield_title';
    //  protected $tabs             = array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.
    //  protected $listQry          = "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
    protected $listOrder = 'er_id DESC';

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
            'data' => 'int',
            'width' => '5%',
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
        ) ,
        'er_name' => array(
            'title' => LAN_TITLE,
            'type' => 'text',
            'data' => 'int',
            'width' => 'auto',
            'inline' => true,
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
        ) ,
        'er_selectedevents' => array(
            'title' => 'Selectedevents',
            'type' => 'method',
            'data' => 'int',
            'width' => 'auto',
            'help' => '',
            'readParms' => array(),
            'writeParms' => array(),
            'class' => 'left',
            'thclass' => 'left',
            'filter' => false,
            'batch' => false,
        ) ,
        'er_sections' => array(
            'title' => 'Sections',
            'type' => 'dropdown',
            'data' => 'int',
            'width' => 'auto',
            'help' => '',
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
        'er_name'
    );

    public function init()
    {
        // This code may be removed once plugin development is complete.
        if (!e107::isInstalled('events2bots'))
        {
            e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
        }

        // Set drop-down values (if any).
        $this->fields['er_sections']['writeParms']['optArray'] = array(
            'er_sections_0',
            'er_sections_1',
            'er_sections_2'
        ); // Example Drop-down array.
        
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
        $caption = LAN_HELP;
        $text = 'Some help text';

        return array(
            'caption'   => $caption,
            'text'      => $text
        );

    }

    /*
    // optional - a custom page.
    public function customPage()
    {
    $text = 'Hello World!';
    $otherField  = $this->getController()->getFieldVar('other_field_name');
    return $text;
    
    }
    
    
    
    // Handle batch options as defined in e2b_eventrules_form_ui::er_selectedevents;  'handle' + action + field + 'Batch'
    // @important $fields['er_selectedevents']['batch'] must be true for this method to be detected.
    // @param $selected
    // @param $type
    function handleListErSelectedeventsBatch($selected, $type)
    {
    
    $ids = implode(',', $selected);
    
    switch($type)
    {
    case 'custombatch_1':
    // do something
    e107::getMessage()->addSuccess('Executed custombatch_1');
    break;
    
    case 'custombatch_2':
    // do something
    e107::getMessage()->addSuccess('Executed custombatch_2');
    break;
    
    }
    
    
    }
    
    
    // Handle filter options as defined in e2b_eventrules_form_ui::er_selectedevents;  'handle' + action + field + 'Filter'
    // @important $fields['er_selectedevents']['filter'] must be true for this method to be detected.
    // @param $selected
    // @param $type
    function handleListErSelectedeventsFilter($type)
    {
    
    $this->listOrder = 'er_selectedevents ASC';
    
    switch($type)
    {
    case 'customfilter_1':
    // return ' er_selectedevents != 'something' '; 
    e107::getMessage()->addSuccess('Executed customfilter_1');
    break;
    
    case 'customfilter_2':
    // return ' er_selectedevents != 'something' '; 
    e107::getMessage()->addSuccess('Executed customfilter_2');
    break;
    
    }
    
    
    }
    
    
    
    */

}

class e2b_news_eventrules_form_ui extends e_admin_form_ui
{

    // Custom Method/Function
    function er_selectedevents($curVal, $mode)
    {



        switch ($mode)
        {
            case 'read': // List Page
                return $curVal;
            break;

            case 'write': // Edit Page
                return $this->text('er_selectedevents', $curVal, 255, 'size=large');
            break;

            case 'filter':
                return array(
                    'customfilter_1' => 'Custom Filter 1',
                    'customfilter_2' => 'Custom Filter 2'
                );
            break;

            case 'batch':
                return array(
                    'custombatch_1' => 'Custom Batch 1',
                    'custombatch_2' => 'Custom Batch 2'
                );
            break;
        }

        return null;
    }

}

new events2bots_adminArea();

require_once (e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();

require_once (e_ADMIN . "footer.php");
exit;