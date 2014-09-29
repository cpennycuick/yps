<?php

namespace DataBase;

class Query {

	protected $table;
	protected $joins = [];
	protected $select = [];
	protected $filter = [];
	protected $groupBy = [];
	protected $orderBy = [];
	protected $having = [];
	protected $limit = null;
	protected $offset = null;

	protected function __construct() {}

	public static function create() {
		return new static();
	}

	protected function generateSQL() {
		$sql = [];
		$sql[] = 'SELECT '.($this->select ? implode(', ', $this->select) : '*');
		$sql[] = 'FROM '.$this->table;

		if ($this->joins) {
			$sql[] = implode("\n", $this->joins);
		}
		if ($this->filter) {
			$sql[] = 'WHERE '.implode(' AND ', $this->filter);
		}
		if ($this->groupBy) {
			$sql[] = 'GROUP BY '.implode(', ', $this->groupBy);
		}
		if ($this->orderBy) {
			$sql[] = 'ORDER BY '.implode(', ', $this->orderBy);
		}
		if ($this->having) {
			$sql[] = 'HAVING '.implode(' AND ', $this->having);
		}
		if ($this->limit) {
			$sql[] = 'LIMIT '.$this->limit;
		}
		if ($this->offset) {
			$sql[] = 'OFFSET '.$this->offset;
		}

		return implode("\n", $sql);
	}
	
	public function setFrom($table, $alias = null) {
		if ($table instanceof Query) {
			if (!$alias) {
				throw new \Exception('Alias required.');
			}

			$table = "(\n".$table->generateSQL()."\n)";
		}

		$this->table = $table.($alias ? ' AS '.$alias : '');
		return $this;
	}

	public function addJoin($joinType, $table, $join, $alias = null) {
		if ($table instanceof Query) {
			if (!$alias) {
				throw new \Exception('Alias required.');
			}

			$table = "(\n".$table->generateSQL()."\n)";
		}

		$this->joins[] = $joinType.' '.$table.($alias ? ' AS '.$alias : '').' ON ('.$join.')';

		return $this;
	}

	public function addSelect($column, $alias = null) {
		$this->select[] = $column.($alias ? ' AS "'.$alias.'"' : '');
		return $this;
	}

	public function addFilter($filter) {
		$this->filter[] = $filter;
		return $this;
	}

	public function addGroupBy($groupBy) {
		$this->groupBy[] = $groupBy;
		return $this;
	}

	public function addOrderBy($orderBy, $order = 'ASC') {
		$this->orderBy[] = $orderBy.' '.$order;
		return $this;
	}

	public function addHaving($having) {
		$this->having[] = $having;
		return $this;
	}
	
	public function setLimit($limit) {
		$this->limit = (!is_null($limit) ? max((int)$limit, 1) : null);
		return $this;
	}
	
	public function setOffset($offset) {
		$this->offset = (!is_null($offset) ? max((int)$offset, 1) : null);
		return $this;
	}

}
