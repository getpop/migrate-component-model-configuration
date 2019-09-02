<?php
/*
Plugin Name: PoP Configuration Engine
Version: 0.1
Description: Front-end module for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\ConfigurationComponentModel;
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONFIGURATIONENGINE_VERSION', 0.160);
define('POP_CONFIGURATIONENGINE_DIR', dirname(__FILE__));

class Plugin
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 5);
    }
    public function init()
    {
        // Allow other plug-ins to modify the plugins_url path (eg: pop-aws adding the CDN)
        define('POP_CONFIGURATIONENGINE_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONFIGURATIONENGINE_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoPEngineConfiguration_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new Initialization();;
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new Plugin();
