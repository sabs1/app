<?php

/**
 * Chat configuration failover
 * 
 * @author Tomek
 *
 */
class ChatfailoverSpecialController extends WikiaSpecialPageController {
	private $businessLogic = null;
	private $controllerData = array();

	public function __construct() {
		// standard SpecialPage constructor call
		parent::__construct( 'Chatfailover', 'chatfailover', false );
	}

	// Controllers can all have an optional init method
	public function init() {
		F::build('JSMessages')->enqueuePackage('Chat', JSMessages::EXTERNAL);
		$this->response->addAsset( 'extensions/wikia/Chat/css/ChatFailover.scss' );
		$this->response->addAsset( 'extensions/wikia/Chat/js/controllers/ChatFailover.js' );
	}

	/**
	 * @brief this is default method, which in this example just redirects to Hello method
	 * @details No parameters
	 *
	 */

	public function index() {
		wfProfileIn(__METHOD__);
		if(!$this->wg->User->isAllowed('chatfailover')) {
			$this->wg->Out->permissionRequired('chatfailover');
			$this->skipRendering();
			wfProfileOut(__METHOD__);
			return true;	
		}
		
		$mode = (bool) ChatHelper::getMode();
		$this->wg->Out->setPageTitle( wfMsg('Chatfailover') );

		if($this->request->wasPosted()) {
			$reason = $this->request->getVal('reason'); 
			if(!empty($reason) && $mode == ChatHelper::getMode()) { //the mode didn't change
				$mode = !$mode;
				StaffLogger::log("chatfo", "switch", $this->wg->User->getID(), $this->wg->User->getName(), $mode, $mode ? 'regular': 'failover', $reason);
				ChatHelper::changeMode($mode);	
			} 
		}
		
		$this->response->setVal("serversList", ChatHelper::getServersList()); 
		
		$this->response->setVal("mode", $mode ? 'regular': 'failover');
		$this->response->setVal("modeBool", $mode );
		wfProfileOut(__METHOD__);
	}
}
