<?php
class SheetSyncUtil
{
  public static function member2sheet(){
    //$member_list = array("tejima@tejimaya,com","mamoru@tejimaya.com","o-hira@tejimaya.com","yamada@tejimaya.com");
    $member_list = Doctrine::getTable('Member')->findAll();


    $id = opConfig::get('opsheetsyncplugin_gapps_id',null);
    $pass = opConfig::get('opsheetsyncplugin_gapps_password',null);
    $sheetkey = opConfig::get('opsheetsyncplugin_gapps_sheetkey',null);
    $sheetid = opConfig::get('opsheetsyncplugin_gapps_sheetid',null);
    $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
    $client = Zend_Gdata_ClientLogin::getHttpClient($id, $pass, $service);
    $spreadsheetService = new Zend_Gdata_Spreadsheets($client);
    foreach($member_list as $member){
      $update = $spreadsheetService->updateCell($i+2,
                                               1,
                                               $member->getId(),
                                               $sheetkey,
                                               $sheetid);
      $i++;
    }

    return true;
  }
  public static function community2sheet(){
    $community_list = Doctrine::getTable('Community')->findAll();
    $id = opConfig::get('opsheetsyncplugin_gapps_id',null);
    $pass = opConfig::get('opsheetsyncplugin_gapps_password',null);
    $sheetkey = opConfig::get('opsheetsyncplugin_gapps_sheetkey',null);
    $sheetid = opConfig::get('opsheetsyncplugin_gapps_sheetid',null);
    $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
    $client = Zend_Gdata_ClientLogin::getHttpClient($id, $pass, $service);
    $spreadsheetService = new Zend_Gdata_Spreadsheets($client);

    foreach($community_list as $community){
      $update = $spreadsheetService->updateCell(1,
                                               $i+2,
                                               $community->getId(),
                                               $sheetkey,
                                               $sheetid);
      $i++;
    }
    return true;
  }
  public static function community_member2sheet(){


    $id = opConfig::get('opsheetsyncplugin_gapps_id',null);
    $pass = opConfig::get('opsheetsyncplugin_gapps_password',null);
    $sheetkey = opConfig::get('opsheetsyncplugin_gapps_sheetkey',null);
    $sheetid = opConfig::get('opsheetsyncplugin_gapps_sheetid',null);
    $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
    $client = Zend_Gdata_ClientLogin::getHttpClient($id, $pass, $service);
    $spreadsheetService = new Zend_Gdata_Spreadsheets($client);


    $community_list = Doctrine::getTable('Community')->findAll();
    foreach($community_list as $community){

      $cm_list = Doctrine::getTable('CommunityMember')->findByCommunityId($community->getId());
      foreach($cm_list as $cm){
        $update = $spreadsheetService->updateCell(
                                               $cm->getMemberId()+1,
                                               $cm->getCommunityId()+1,
                                               'T',
                                               $sheetkey,
                                               $sheetid
                                               );
      }
    }
  }
  public static function community_sync(){    
    $id = opConfig::get('opsheetsyncplugin_gapps_id',null);
    $pass = opConfig::get('opsheetsyncplugin_gapps_password',null);
    $sheetkey = opConfig::get('opsheetsyncplugin_gapps_sheetkey',null);
    $sheetid = opConfig::get('opsheetsyncplugin_gapps_sheetid',null);
    $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
    $client = Zend_Gdata_ClientLogin::getHttpClient($id, $pass, $service);
    $spreadsheetService = new Zend_Gdata_Spreadsheets($client);
    $query = new Zend_Gdata_Spreadsheets_ListQuery();

    $query->setSpreadsheetKey($sheetkey);
    $query->setWorksheetId($sheetid);

    $listfeed = $spreadsheetService->getListFeed($query);

    $member_list = Doctrine::getTable('Member')->findAll();

    foreach($member_list as $member){
      $member_id = $member->getId();
      $community_id = 0;
      $line_list = $listfeed->entries[$member_id - 1]->getCustom();
      foreach($line_list as $line){
      if($line->getText() == (string)$member_id){
        //skip first column
      }else if($line->getText() == "T"){
        echo "Member.id = " . $member_id . " is a member of Community.id = ". $community_id ."\n";
        $q = Doctrine::getTable('CommunityMember')->createQuery('cm')->where('cm.member_id = ?',$member_id)->andWhere('cm.community_id = ?',$community_id);
        $count = $q->count();
        echo "count " . $count;
        if($count == 1){
          //skip
        }else{
          $obj = new CommunityMember();
          $obj->setMemberId($member_id);
          $obj->setCommunityId($community_id);
          $obj->save();
          echo "save it " . $count;
        }
      }else{//brank remove from commu
         echo "Member.id = " . $member_id . " is NOT a member of Community.id = ". $community_id ."\n";
        $q = Doctrine::getTable('CommunityMember')->createQuery('cm')->where('cm.member_id = ?',$member_id)->andWhere('cm.community_id = ?',$community_id);
        $count = $q->count();
        if($count == 1){
          //remove it
          $deleted = Doctrine_Query::create()
            ->delete()
            ->from('CommunityMember cm')
            ->where('cm.member_id = ?', $member_id)
            ->andWhere('cm.community_id = ?', $community_id)
            ->execute();
        }
      }
      $community_id++;
      }
    }
    

  }
}
