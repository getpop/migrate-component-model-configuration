<?php
namespace PoP\ConfigurationComponentModel;
use PoP\Hooks\Facades\HooksAPIFacade;

class VarsHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            '\PoP\ComponentModel\Engine_Vars:dataoutputitems',
            [$this, 'getDataOutputItems'],
            0
        );
        HooksAPIFacade::getInstance()->addFilter(
            '\PoP\ComponentModel\Engine_Vars:default-dataoutputitems',
            [$this, 'getDefaultDataOutputItems'],
            0
        );
    }

    public function getDataOutputItems($dataoutputitems)
    {
        $dataoutputitems[] = GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS;
        return $dataoutputitems;
    }

    public function getDefaultDataOutputItems($dataoutputitems)
    {
        // Replace the DatasetSettings with Settings
        array_splice(
            $dataoutputitems,
            array_search(
                GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS,
                $dataoutputitems
            ),
            1,
            [
                GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS,
            ]
        );
        return $dataoutputitems;
    }
}

/**
 * Initialization
 */
new VarsHooks();
