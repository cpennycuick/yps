<?php

namespace YP\DataModel\ORM;

class ProjectQuery extends \DataBase\Query {
	
	use \YP\Addin\DataBase;
	
	protected $table = 'project AS P';
	
	private $bind = [];
	
	public function find() {
		$sql = $this->generateSQL();
		error_log($sql);
		return $this->getCon()->fetchAllAssoc($sql, $this->bind);
	}
	
	public function joinProjectRevision() {
		$this->addJoin('JOIN', 'projectrevision', 'P.projectid = PR.projectid', 'PR');
		return $this;
	}
	
	public function filterByProjectID($projectID) {
		$this->bind['ProjectID'] = $projectID;
		$this->addFilter('P.projectid = :ProjectID');
		return $this;
	}
	
	public function filterProjectRevisionByStatus($status) {
		$this->bind['ProjectRevisionStatus'] = $status;
		$this->addFilter('PR.status = :ProjectRevisionStatus');
		return $this;
	}
	
}
