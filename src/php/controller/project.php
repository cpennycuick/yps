<?php

namespace YP\Controller;

class Project {
	
	use \YP\Addin\DataBase;
	
	public function getList() {
		$limit = 8;
		$offset = (int)(isset($_POST['LastPage']) ? $_POST['LastPage'] : 0) * $limit;
		
		$dm = new \YP\DataModel\Project();
		$records = $dm->getProjects($offset, $limit);
		
		return [
			'Records' => $records,
		];
	}
	
	public function getView() {
		$projectID = (isset($_POST['ProjectID']) ? $_POST['ProjectID'] : null);
		
		$dm = new \YP\DataModel\Project();
		$record = $dm->getProject($projectID);
		
		return [
			'Record' => $record,
		];
	}
	
}
