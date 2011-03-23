<?php
class OP2SheetTask extends sfBaseTask
{
  protected function configure()
  {
    #set_time_limit(120);
    mb_language("Japanese");
    mb_internal_encoding("utf-8");

    $this->namespace        = 'zuniv.us';
    $this->name             = 'op2sheet';
    $this->briefDescription = 'OpenPNE Member & Community data 2 Google SpreadSheet';
    $this->detailedDescription = <<<EOF
The [feed-reader|INFO] task does things.
Call it with:

  [php symfony socialagent:feed-reader [--env="..."] application|INFO]
EOF;
    $this->addOption('application',null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','pc_frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod');
    $this->addOption('member', null, sfCommandOption::PARAMETER_NONE, 'member');
  }
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    if($options['member']){
      echo "write slash\n";
      SheetSyncUtil::write_slash();
      echo "member2sheet\n";
      SheetSyncUtil::member2sheet(true,2);
      echo "member2sheet\n";
      SheetSyncUtil::member2sheet(false,2);
    }else{
      echo "writeindex\n";
      SheetSyncUtil::writeindex();
      echo "member2sheet\n";
      SheetSyncUtil::member2sheet();
      echo "community2sheet\n";
      SheetSyncUtil::community2sheet();
      echo "community_member2sheet\n";
      SheetSyncUtil::community_member2sheet();
    }
  }
}
