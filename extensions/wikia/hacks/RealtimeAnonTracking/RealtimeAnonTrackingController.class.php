<?php
class RealtimeAnonTrackingController extends WikiaController {
	const RAT_KEY = 'rat_report_v_0.1';

	public function index() {
		$storageModel = new MySQLKeyValueModel();
		$this->report = json_decode( $storageModel->get( RealtimeAnonTrackingController::RAT_KEY ) );
		$this->response->setFormat( WikiaResponse::FORMAT_JSON );

//		$this->test = $storageModel->delete( RealtimeAnonTrackingController::RAT_KEY );
	}

	public function saveReport() {
		$report = $this->request->getVal('report');
		$this->report = stripslashes($report);
		$this->response->setFormat( WikiaResponse::FORMAT_JSON );

		$storageModel = new MySQLKeyValueModel();
		$storageModel->set( RealtimeAnonTrackingController::RAT_KEY, $report );
	}
}
