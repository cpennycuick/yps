<?php

namespace YP\DataModel;

class Project {
	
	use \YP\Addin\DataBase;
	
	public function getProject($projectID) {
		$records = ORM\ProjectQuery::create()
			->addSelect('P.projectid', 'ProjectID')
			->addSelect('PR.title', 'Title')
			->addSelect('PR.description', 'Description')
			->joinProjectRevision()
//			->filterProjectRevisionByStatus('Approved') TODO fix not working
			->filterByProjectID($projectID)
			->setLimit(1)
			->find();
			
		error_log(print_r($records, true));
		
		return (array_pop($records) ?: null);
	}
	
	public function getProjects($offset = null,  $limit = null) {
		return ORM\ProjectQuery::create()
			->addSelect('P.projectid', 'ProjectID')
			->addSelect('PR.title', 'Title')
			->addSelect('PR.description', 'Description')
			->joinProjectRevision()
			->filterProjectRevisionByStatus('Approved')
			->setLimit($limit)
			->setOffset($offset)
			->find();
	}
		
}
