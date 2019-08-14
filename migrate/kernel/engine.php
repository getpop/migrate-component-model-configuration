<?php
namespace PoP\ConfigurationEngine;
use PoP\Engine\Facades\PersistentCacheFacade;

class Engine extends \PoP\Engine\Engine
{
    protected function processAndGenerateData()
    {
        parent::processAndGenerateData();

        // Validate that the strata includes the required stratum
        $vars = \PoP\Engine\Engine_Vars::getVars();
        if (!in_array(POP_STRATUM_CONFIGURATION, $vars['strata'])) {
            return;
        }

        // Get the entry module based on the application configuration and the nature
        $module = $this->getEntryModule();

        // Externalize logic into function so it can be overridden by PoP Web Platform Engine
        $dataoutputitems = $vars['dataoutputitems'];

        $data = [];
        if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems)) {
            $data = array_merge(
                $data,
                $this->getModuleSettings($module, $this->model_props, $this->props)
            );
        }

        // Do array_replace_recursive because it may already contain data from doing 'extra-uris'
        $this->data = array_replace_recursive(
            $this->data,
            $data
        );
    }

    public function getModuleSettings(array $module, $model_props, array &$props)
    {
        $ret = array();

        $moduleprocessor_manager = \PoP\Engine\ModuleProcessorManagerFactory::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        $cachemanager = PersistentCacheFacade::getInstance();

        // From the state we know if to process static/staful content or both
        $vars = \PoP\Engine\Engine_Vars::getVars();
        $datasources = $vars['datasources'];
        $dataoutputmode = $vars['dataoutputmode'];
        $dataoutputitems = $vars['dataoutputitems'];

        $add_settings = in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems);

        // First check if there's a cache stored
        $useCache = \PoP\Engine\Server\Utils::useCache();
        if ($useCache) {
            $immutable_settings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_IMMUTABLESETTINGS);
            $mutableonmodel_settings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATEFULSETTINGS);
        }

        // If there is no cached one, generate the configuration and cache it
        if (!$immutable_settings) {
            $immutable_settings = $processor->getImmutableSettingsModuletree($module, $model_props);
            $mutableonmodel_settings = $processor->getMutableonmodelSettingsModuletree($module, $model_props);

            if ($useCache) {
                $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_IMMUTABLESETTINGS, $immutable_settings);
                $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATEFULSETTINGS, $mutableonmodel_settings);
            }
        }
        if ($datasources == GD_URLPARAM_DATASOURCES_MODELANDREQUEST) {
            $mutableonrequest_settings = $processor->getMutableonrequestSettingsModuletree($module, $props);
        }

        // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
        list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

        if ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES) {
            // Save the model settings
            if ($immutable_settings) {
                $ret['modulesettings']['immutable'] = $immutable_settings;
            }
            if ($mutableonmodel_settings) {
                $ret['modulesettings']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_settings) : $mutableonmodel_settings;
            }
            if ($mutableonrequest_settings) {
                $ret['modulesettings']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_settings) : $mutableonrequest_settings;
            }
        } elseif ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_COMBINED) {
            // If everything is combined, then it belongs under "mutableonrequest"
            if ($combined_settings = array_merge_recursive(
                $immutable_settings ?? array(),
                $mutableonmodel_settings ?? array(),
                $mutableonrequest_settings ?? array()
            )
            ) {
                // For the API: maybe remove the entry module from the output
                $combined_settings = $this->maybeRemoveEntryModuleFromOutput($combined_settings);
                $ret['modulesettings'] = $has_extra_routes ? array($current_uri => $combined_settings) : $combined_settings;
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
new Engine();
