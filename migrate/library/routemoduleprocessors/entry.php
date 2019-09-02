<?php

class PoP_ConfigurationComponentModel_Module_EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    public function getModulesVarsProperties()
    {
        $ret = array();

        $ret[] = [
            'module' => [PoP_ConfigurationComponentModel_Module_Processor_Elements::class, PoP_ConfigurationComponentModel_Module_Processor_Elements::MODULE_EMPTY],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
new PoP_ConfigurationComponentModel_Module_EntryRouteModuleProcessor();
