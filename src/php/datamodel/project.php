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
			->filterProjectRevisionByStatus('Approved')
			->filterByProjectID($projectID)
			->setLimit(1)
			->find();

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
