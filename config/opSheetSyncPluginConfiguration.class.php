<?php

class opSheetSyncPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    sfToolkit::addIncludePath(array(
      OPENPNE3_CONFIG_DIR.'/../plugins/opSheetSyncPlugin/lib/'
    ));

  }
}

