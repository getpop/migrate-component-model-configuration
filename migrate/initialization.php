<?php
namespace PoP\ConfigurationEngine;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-configurationengine', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        require_once 'kernel/load.php';

        /**
         * Load the Library
         */
        require_once 'library/load.php';
    }
}

/**
 * Initialization
 */
new Initialization();
