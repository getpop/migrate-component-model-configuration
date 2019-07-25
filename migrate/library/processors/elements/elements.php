<?php

class PoP_ConfigurationEngine_Module_Processor_Elements extends \PoP\ConfigurationEngine\ModuleProcessorBase
{
    public const MODULE_EMPTY = 'empty';

    public function getModulesToProcess()
    {
        return array(
            [self::class, self::MODULE_EMPTY],
        );
    }
}
