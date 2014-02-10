<?php

/**
* Allows to generate queries
*
*/
class QueryBuilder
{
	/**
	 * store all clauses constants
	 */
	const ALL = 'all';
	const COUNT = 'count';
	const DELETE = 'delete';
	const FROM = 'from';
	const GROUP_BY = 'group_by';
	const INSERT = 'insert';
	const ORDER_BY = 'order_by';
	const JOIN = 'join';
	const LIMIT = 'limit';
	const SELECT = 'select';
	const UPDATE = 'update';
	const WHERE = 'where';


	/**
	 * store all clauses
	 */
	protected $clauses;

	/**
	 * store current query
	 */
	protected $query;

	/**
	 * Indicate if query has changed
	 */
	protected $queryChanged;

	
	/**
	 * Build a new Query_Builder object
	 *
	 */
	public function _construct() {
		
		// ini properties
		$this->clauses = array();
		$this->query = '';
		$this->queryChanged = false;
	}


	/**
	 * Add a clause to the query
	 *
	 * @param String $clause Clause to add
	 * @param String $value Value of the clause
	 */
	protected function clause($clause, $value) {
		
		if(!isset($this->clauses[$clause]) || !is_array($this->clauses[$clause]))
			$this->clauses[$clause] = array();

		$this->clauses[$clause][] = $value;
		$this->queryChanged = true;
	}

	/**
	 * Build the query
	 */
	protected function buildQuery() {

		$query = '';
		$this->queryChanged = false;

		// SELECT
		if($this->hasClause(self::SELECT)) {
			$addFrom = true;
			$select = implode(',', $this->getClause(self::SELECT));

			$count = ' ';

			if($this->hasClause(self::COUNT)) {
				$count_clause = $this->getClause(self::COUNT);
				$count = ', COUNT' . $count_clause[0];
			}

			$query .= 'SELECT ' . $select . $count;
		}
		// INSERT
		else if($this->hasClause(self::INSERT)) {
			$addFrom = false;
			$insert = $this->getClause(self::INSERT);
			$insert = $insert[0];
			$query .= 'INSERT INTO ' . $insert;
		}
		// UPDATE
		else if($this->hasClause(self::UPDATE)) {
			$addFrom = false;
			$update = $this->getClause(self::UPDATE);
			$update = $update[0];
			$query .= 'UPDATE ' . $update;
		}
		// DELETE
		else if($this->hasClause(self::DELETE)) {
			$addFrom = true;
			$query .= 'DELETE';
		}
		// ERROR : No SELECT, INSERT, UPDATE, DELETE
		else {
			print('Trying to build a query that has no SELECT or INSERT or UPDATE or DELETE');
			die();
		}

		// FROM
		if($this->hasClause(self::FROM) && $addFrom) {
			$from = $this->getClause(self::FROM);
			$query .= ' FROM ' . $from[0];
		}
		// ERROR : No FROM
		else if($addFrom){
			print('Trying to build a query that has no FROM');
			die();
		}

		// JOIN
		if($this->hasClause(self::JOIN)) {
			$join = implode(' ', $this->getClause(self::JOIN));
			$query .= ' ' . $join;
		}

		// WHERE
		if($this->hasClause(self::WHERE)) {
			$where = implode(' ', $this->getClause(self::WHERE));
			$query .= ' WHERE ' . $where;
		}

		// GROUP BY
		if($this->hasClause(self::GROUP_BY)) {
			$group_by = $this->getClause(self::GROUP_BY);
			$query .= ' GROUP BY ' . $group_by[0];
		}

		// ORDER BY
		if($this->hasClause(self::ORDER_BY)) {
			$order_by = implode(' ', $this->getClause(self::ORDER_BY));
			$query .= ' ORDER BY ' . $order_by;
		}

		// LIMIT
		if($this->hasClause(self::LIMIT)) {
			$limit = $this->getClause(self::LIMIT);
			$query .= ' LIMIT ' . $limit[0];
		}

		$query .= ';';
		$this->query = $query;
	}

	/**
	 * Get a clause value
	 *
	 * @param String $clause Clause to retrieve
	 * @return Array Return Clause value
	 */
	protected function getClause($clause) {
		
		return $this->clauses[$clause];
	}

	/**
	 * Check if a clause is set
	 *
	 * @param String $clause Clause to check
	 * @return Bool Return true if the clause is set, false otherwise
	 */
	protected function hasClause($clause) {
		
		return (isset($this->clauses[$clause]) && !empty($this->clauses[$clause]));
	}

	/**
	 * Secure a value to insert or update
	 *
	 * @param String $value Value to secure
	 * @return String The value secured
	 */
	protected function secureValue($value) {
        
        return mysqli_real_escape_string($value);
	}

	/**
	 * Set a clause
	 *
	 * @param String $clause Clause to add
	 * @param String $value Value of the clause
	 */
	protected function setClause($clause, $value) {

		$this->clauses[$clause] = array($value);
		$this->queryChanged = true;
	}

	/**
	 * Add COUNT clause to the query
	 *
	 * @param String $col_name Name of the column you want to count
	 * @param String $alias Name of the alias through which you want to retrieve counting result, by default equal to 'nbr'
	 */
	public function count($col_name, $alias = 'nbr') {

		$count = '(`' . $col_name . '`) AS "' . $alias . '"';
		
		$this->setClause(self::COUNT, $count);
	}

	/**
	 * Add DELETE clause to the query
	 *
	 */
	public function delete() {
		
		$this->setClause(self::DELETE, array());
	}

	/**
	 * Add FROM clause to the query
	 *
	 * @param String $from FROM clause
	 */
	public function from($from) {
		
		$this->setClause(self::FROM, $from);
	}

	/**
	 * Add GROUP BY clause to the query
	 *
	 * @param String $cols Col name to group by
	 */
	public function group_by($col) {

		$this->clause(self::GROUP_BY, $col);
	}

	/**
	 * Add INSERT clause to the query
	 *
	 * @param String|Array $data Indexed array that contains data to insert
	 * @param String $table_name Name of the table in which you want to insert data, by default equal to an empty String if you want to pass a String through $data
	 */
	public function insert($data, $table_name) {

		if(is_string($data)) {
			$to_insert = '`' . $table_name . '` ' . $data;
		}
		
		if(is_array($data)) {
			
			$cols = '';
			$values = '';

			// set cols and values
			foreach ($data as $col => $value) {
				$cols   .= '`' . $col . '`,';
				$values .= $this->secureValue($value) . ',';
			}

			// remove last comma
			$cols = substr($cols, 0, -1);
			$values = substr($values, 0, -1);

			$to_insert = '`' . $table_name . '` (' . $cols . ') VALUES (' . $values . ')';
		}
			
		$this->clause(self::INSERT, $to_insert);
	}

	/**
	 * Add JOIN clause to the query
	 *
	 * @param String $type Join type : left, right, full, inner
	 * @param String $master_table Name of the table you want to join on
	 * @param String $master_col col name of the master table to join on
	 * @param String $table_joined Name of the table you want to join
	 * @param String $col_joined Col name of the table you want to join
	 * @param Array $select Array that contains cols name to select in joined table (see self::select() doc)
	 */
	public function join($type, $master_table, $master_col, $table_joined, $col_joined, $select = null) {

		$join = '';

		switch ($type) {
			case 'left':
				$join .= 'LEFT JOIN';
				break;
			case 'right':
				$join .= 'RIGHT JOIN';
				break;
			case 'inner':
				$join .= 'INNER JOIN';
				break;
			case 'full':
				$join .= 'FULL JOIN';
				break;
		}

		$join .= '`' . $table_joined . '` ON `' . $master_table . '`.`' . $master_col . '` = `' . $table_joined . '`.`' . $col_joined . '`';

		if($select != null) {

			$this->select($select, $table_joined);
		}

		$this->clause(self::JOIN, $join);
	}

	/**
	 * Add LIMIT clause to the query
	 *
	 * @param Int $first Number of the first element to get
	 * @param Int $length Number of element to get after the first
	 */
	public function limit($first, $length) {

		$this->clause(self::LIMIT, $first . ', ' . $length);
	}

	/**
	 * Add ORDER BY clause to the query
	 *
	 * @param String $col Col name to order by
	 * @param String $type Type of ordering either ASC or DESC, by default equal to ASC
	 */
	public function order_by($col, $type = 'ASC') {

		$order_by = '`' . $col . '` ' . $type;

		$this->clause(self::ORDER_BY, $order_by);
	}

	/**
	 * Add SELECT clause to the query
	 *
	 * @param String|Array $select SELECT clause data
	 * @param String $table_name Name of the table to select from to avoid conflicts
	 */
	public function select($select, $table_name = '') {

		$to_select = '*';

		if(is_string($select) && $to_select != '*') {
			$to_select = '`' . $select . '`';
		}
		
		if(is_array($select)) {
			$to_select = '';
			foreach ($select as $key => $value) {

				if($table_name != '') {
					$to_select .= '`' . $table_name . '`.';
				}

				if(is_numeric($key)) {
					$to_select .= '`' . $value . '`,';
				}
				else {
					$to_select .= '`' . $key . '` AS `' . $value . '`,';
				}
			}

			// remove last comma
			$to_select = substr($to_select, 0, -1);
		}
			
		$this->clause(self::SELECT, $to_select);
	}

	/**
	 * Add UPDATE clause to the query
	 *
	 * @param String|Array $data Indexed array that contains data to update
	 * @param String $table_name Name of the table in which you want to update data, by default equal to an empty String if you want to pass a String through $data
	 */
	public function update($data, $table_name) {

		if(is_string($data)) {
			$to_update = '`' . $table_name . '` ' . $data;
		}
		
		if(is_array($data)) {
			
			$sets = '';

			// set cols and values
			foreach ($data as $col => $value) {
				$sets   .= '`' . $col . '`=' . $this->secureValue($value) . ',';
			}

			// remove last comma
			$sets = substr($sets, 0, -1);

			$to_update = '`' . $table_name . '` SET ' . $sets;
		}
			
		$this->clause(self::UPDATE, $to_update);
	}

	/**
	 * Add WHERE clause to the query
	 *
	 * @param Array $where WHERE clause data with 4 index :
 	 *							 - col      :  col name in your table
 	 *							 - value    :  value to chack in your table
 	 *							 - operator :  Comparison operator between col and value, by default equal to '='
 	 *							 - logic    :  Logic operator between all WHERE clauses, by default equal to an empty String
 	 *
 	 *		  String $where Full WHERE clause
	 *					
	 */
	public function where($where) {

		$where_clause = '';

		if(is_string($where)) {
			$where_clause = $where;
		}

		else if(is_array($where)) {

			// check for errors
			if(!isset($where['col'])) {
				print('You must set a col index in a where clause in : ' . get_class($this));
				die();
			}

			if(!isset($where['value'])) {
				print('You must set a value index in a where clause in : ' . get_class($this));
				die();
			}

			// set default operator
			if(!isset($where['operator'])) {
				$where['operator'] = '=';
			}

			// set default logic
			if(!isset($where['logic'])) {

				if(!$this->hasClause(self::WHERE)) {
					$where['logic'] = '';
				}
				else {
					$where['logic'] = 'AND';
				}
			}
			else if(!isset($where['logic']) && $this->hasClause(self::WHERE)) {
				$where['logic'] = 'AND';
			}


			$where_clause .= $where['logic'] . ' `' . $where['col'] . '` ' . $where['operator'] . ' ' . $this->secureValue($where['value']);
		}

		$this->clause(self::WHERE, $where_clause);
	}

	/**
	 * Clear one or many clauses
	 *
	 * @param String|Array $clauses Clause to clear, by default equal to Query_Builder::ALL and clear all clauses
	 * @param String|Array $excepts Clauses to keep, by default equal to false and keep none
	 */
	public function clearClauses($clauses = self::ALL, $excepts = false) {
		
		// put all data in arrays
		if(is_string($clauses)) {
			$clauses = array($clauses);
		}
		
		if(!$excepts) {
			$excepts = array();
		}
		else if(is_string($excepts)) {
			$excepts = array($excepts);
		}

		// except if if specified clauses
		if($clauses[0] != self::ALL) {
			foreach ($excepts as $except) {
				if(isset($clauses[$except])) {
					unset($clauses[$except]);
				}
			}
		}

		// except if all clauses
		else {
			// save execpts
			$saved = array();
			foreach ($excepts as $except) {
				if(isset($this->clauses[$except])) {
					$saved[$except] = $this->clauses[$except];
				}
			}

			// restore excepts
			$this->clauses = $saved;
		}

		$this->queryChanged = true;
	}

	/**
	 * Build (if changed) and return the query
	 */
	public function getQuery($clear = true) {

		if($this->queryChanged) {
			$this->buildQuery();
		}
        $query = $this->query;

        if($clear === true){
            $this->clearClauses();
        }
        return $query;
	}
}


?>