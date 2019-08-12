<?php
namespace PoP\ConfigurationEngine;
use PoP\Engine\ModuleUtils;

abstract class ModuleProcessorBase extends \PoP\Engine\ModuleProcessorBase
{
    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------

    public function getImmutableSettingsModuletree(array $module, array &$props)
    {
        return $this->executeOnSelfAndPropagateToModules('getImmutableSettings', __FUNCTION__, $module, $props);
    }

    public function getImmutableSettings(array $module, array &$props)
    {
        $ret = array();
        
        if ($configuration = $this->getImmutableConfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }

        if ($database_keys = $this->getDatabaseKeys($module, $props)) {
            $ret['dbkeys'] = $database_keys;
        }
        
        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props)
    {
        $ret = array(
            // GD_JS_MODULE => $module,
            // 'fullname' => ModuleUtils::getModuleFullName($module),
        );

        // if ($this->getDataloaderClass($module)) {
        //     $ret[GD_JS_MODULEOUTPUTNAME] = ModuleUtils::getModuleOutputName($module);
        // }

        return $ret;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Model Stateful Settings
    //-------------------------------------------------

    public function getMutableonmodelSettingsModuletree(array $module, array &$props)
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonmodelSettings', __FUNCTION__, $module, $props);
    }

    public function getMutableonmodelSettings(array $module, array &$props)
    {
        $ret = array();
        
        if ($configuration = $this->getMutableonmodelConfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }
        
        return $ret;
    }

    public function getMutableonmodelConfiguration(array $module, array &$props)
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Settings
    //-------------------------------------------------

    public function getMutableonrequestSettingsModuletree(array $module, array &$props)
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonrequestSettings', __FUNCTION__, $module, $props);
    }

    public function getMutableonrequestSettings(array $module, array &$props)
    {
        $ret = array();
        
        if ($configuration = $this->getMutableonrequestConfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }
        
        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props)
    {
        return array();
    }
}