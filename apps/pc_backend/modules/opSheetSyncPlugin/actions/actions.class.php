<?php

/**
 * opSheetSyncPlugin actions.
 *
 * @package    OpenPNE
 * @subpackage opSheetSyncPlugin
 * @author     Your name here
 */
class opSheetSyncPluginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new opSheetSyncPluginConfigForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('sheetsync'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('opSheetSyncPlugin/index');
      }
    }

  }
}
