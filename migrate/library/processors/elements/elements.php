<?php

class PoP_ConfigurationComponentModel_Module_Processor_Elements extends \PoP\ConfigurationComponentModel\ModuleProcessorBase
{
    public const MODULE_EMPTY = 'empty';

    public function getModulesToProcess()
    {
        return array(
            [self::class, self::MODULE_EMPTY],
        );
    }
}
