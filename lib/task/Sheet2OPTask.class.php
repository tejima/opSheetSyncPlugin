<?php
class Sheet2OPTask extends sfBaseTask
{
  protected function configure()
  {
    #set_time_limit(120);
    mb_language("Japanese");
    mb_internal_encoding("utf-8");

    $this->namespace        = 'tjm';
    $this->name             = 'sheet2op';
    $this->aliases          = array('tjm-sheet2op');
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [feed-reader|INFO] task does things.
Call it with:

  [php symfony socialagent:feed-reader [--env="..."] application|INFO]
EOF;
    $this->addOption('application',null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','pc_frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod');
  }
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    echo "sync\n";
    SheetSyncUtil::community_sync();
  }
}

