<?php
class opSheetSyncPluginConfigForm extends sfForm
{
  protected $configs = array(

//s($app['all']['twipne_config']['accesskey'],$app['all']['twipne_config']['secretaccesskey']);
    'gapps_id' => 'opsheetsyncplugin_gapps_id',
    'gapps_password' => 'opsheetsyncplugin_gapps_password',
    'gapps_sheetkey' => 'opsheetsyncplugin_gapps_sheetkey',
    'gapps_sheetid' => 'opsheetsyncplugin_gapps_sheetid',
  );
  public function configure()
  {
    $this->setWidgets(array(
      'gapps_id' => new sfWidgetFormInput(),
      'gapps_password' => new sfWidgetFormInputPassword(),
      'gapps_sheetkey' => new sfWidgetFormInput(),
      'gapps_sheetid' => new sfWidgetFormInput(),
    ));
    $this->setValidators(array(
      'gapps_id' => new sfValidatorString(array(),array()),
      'gapps_password' => new sfValidatorString(array(),array()),
      'gapps_sheetkey' => new sfValidatorString(array(),array()),
      'gapps_sheetid' => new sfValidatorString(array(),array()),
    ));


    $this->widgetSchema->setHelp('gapps_id', 'GAPPS ID');
    $this->widgetSchema->setHelp('gapps_password', 'GAPPS PASSWORD');
    $this->widgetSchema->setHelp('gapps_sheetkey', 'GAPPS SHEETKEY');
    $this->widgetSchema->setHelp('gapps_sheetid', 'GAPPS SHEETID');

    foreach($this->configs as $k => $v)
    {
      $config = Doctrine::getTable('SnsConfig')->retrieveByName($v);
      if($config)
      {
        $this->getWidgetSchema()->setDefault($k,$config->getValue());
      }
    }
    $this->getWidgetSchema()->setNameFormat('sheetsync[%s]');
  }
  public function save()
  {
    foreach($this->getValues() as $k => $v)
    {
      if(!isset($this->configs[$k]))
      {
        continue;
      }
      $config = Doctrine::getTable('SnsConfig')->retrieveByName($this->configs[$k]);
      if(!$config)
      {
        $config = new SnsConfig();
        $config->setName($this->configs[$k]);
      }
      $config->setValue($v);
      $config->save();
    }
  }
  public function validate($validator,$value,$arguments = array())
  {
    return $value;
  }
}

