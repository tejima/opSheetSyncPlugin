<?php
class SheetSyncUtil
{
  public static function member2sheet(){
    $member_list = array("tejima@tejimaya,com","mamoru@tejimaya.com","o-hira@tejimaya.com","yamada@tejimaya.com");

    $id = opConfig::get('opsheetsyncplugin_gapps_id',null);
    $pass = opConfig::get('opsheetsyncplugin_gapps_password',null);
    $sheetkey = opConfig::get('opsheetsyncplugin_gapps_sheetkey',null);
    $sheetid = opConfig::get('opsheetsyncplugin_gapps_sheetid',null);

    $client = Zend_Gdata_ClientLogin::getHttpClient($id, $pass, $service);
    $spreadsheetService = new Zend_Gdata_Spreadsheets($client);

    $feed = $spreadsheetService->getSpreadsheetFeed();
    print_r($feed);
    exit;

    $i = 0;
    foreach($member_list as $member){
      $update = $spreadsheetService->updateCell($i+1,
                                               1,
                                               $member,
                                               $sheetkey,
                                               $sheetid);

      $i++;
    }

    return true;
  }
  public static function community2sheet(){

  }


}
