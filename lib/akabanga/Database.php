<?php
/**
 * This file is part of Akabanga.
 *
 * Akabanga is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Akabanga is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Akabanga.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright Rowan Seymour 2010
 * 
 * Purpose: Database class
 */

/**
 * Class to represent a database connection
 */
class Database {
	private $res;
	private static $current = NULL;
	
	/** 
	 * Constructs a database connection using the given login credentials
	 * @param string dbHost the server host name
	 * @param string dbUser the user name
	 * @param string dbPass the password
	 * @param string dbName the database name
	 */
	private function __construct($dbHost, $dbUser, $dbPass, $dbName) {		
		$this->res = mysql_connect($dbHost, $dbUser, $dbPass)
			or die("Unable to connect to MySQL");
		mysql_select_db($dbName, $this->res) 
			or die("Unable to select database");
			
		//mysql_set_charset('utf8');
	
		$this->query("SET NAMES 'utf8' COLLATE 'utf8_bin'");
		$this->query("SET time_zone = '+0:00'");
	} 
	
	/**
	 * Gets a singleton instance of the connection class
	 * @return Database the singleton instance
	 */
	public static function getCurrent() {
		global $aka_config;
	
		if (self::$current == NULL)
			self::$current = new Database($aka_config['DB_HOST'], $aka_config['DB_USER'], $aka_config['DB_PASS'], $aka_config['DB_NAME']);
			
		return self::$current;
	}
	
	/**
	 * Executes the given SQL query and returns a result object
	 * @param string sql the SQL to execute
	 * @param Paging paging the paging object (optional)
	 * @return resource the result
	 */
	public function query($sql, $paging = NULL) {
		if ($paging) {
			$sql .= ' LIMIT '.$paging->getStart().', '.$paging->getSize();
	
			$result = $this->query($sql);
			if ($result === FALSE)
				return FALSE;
			
			$total = $this->scalar('SELECT FOUND_ROWS()');
			$paging->setTotal($total);
			return $result;
		}
	
		$result = mysql_query($sql, $this->res);
		if ($result === FALSE && defined('AKABANGA_DEBUG'))
			die('MYSQL ERROR: '.$sql);

		return $result;
	}
	
	/**
	 * Executes the given SQL query and returns a single row as an associative array or FALSE if there was an error or no rows
	 * @param string sql the SQL to execute
	 * @return array the row as an associative array
	 */
	public function row($sql) {
		$result = $this->query($sql);
		return ($result !== FALSE) ? mysql_fetch_array($result) : FALSE;
	}
	
	/**
	 * Executes the given SQL query and returns an associative array or FALSE if there was an error or no rows
	 * @param mixed query either the SQL to execute or an existing result
	 * @param string keyCol the name of column to use as the key in the returned array
	 * @return array the array of row arrays
	 */
	public function rows($query, $keyCol = NULL) {
		if (is_string($query)) {
			$result = $this->query($query);
			if ($result === FALSE)
				return FALSE;
		}
		else
			$result = $query;
			
		$rows = array();
		while ($row = mysql_fetch_assoc($result)) {
			if ($keyCol != NULL)
				$rows[$row[$keyCol]] = $row;
			else
				$rows[] = $row;
		}
		
		return $rows;
	}
	
	/**
	 * Executes the given SQL query and returns a single scalar value or FALSE if there was an error or no rows
	 * @param string sql the SQL to execute
	 * @return mixed the scalar value
	 */
	public function scalar($sql) {
		$result = $this->query($sql);
		if ($result !== FALSE) {	
			$row = mysql_fetch_array($result);
			return $row !== FALSE ? $row[0] : FALSE;
		}
		else
			return FALSE;
	}
	
	/**
	 * Executes a SQL insert statement and returns the last auto increment value
	 * @param string sql the SQL to execute
	 * @return mixed tne last insert id or FALSE if statement failed
	 */
	public function insert($sql) {
		$result = $this->query($sql);
		return $result === FALSE ? FALSE : mysql_insert_id($this->res);
	}
	
	/**
	 * Escapes a string for use in a SQL statement
	 * @param string str the string to escape
	 * @return string the escaped string
	 */
	public function escape($str) {
		return mysql_real_escape_string($str, $this->res);
	}
	
	/**
	 * Gets the field names of the given result
	 * @param resource result the query result
	 * @return array the field names
	 */
	public function fields($result) {
        $fields = array();
        for ($f = 0; $f < mysql_num_fields($result); $f++)
            $fields[] = mysql_field_name($result, $f);
        
        return $fields;
    
    }
	
	/**
	 * Begins a new SQL transaction
	 */
	public function beginTransaction() {
		$this->query("BEGIN");
	}
	
	/**
	 * Commits the current SQL transaction
	 */
	public function commitTransaction() {
		$this->query("COMMIT");
	}
	
	/**
	 * Rolls back the current SQL transaction
	 */
	public function rollbackTransaction() {
		$this->query("ROLLBACK");
	}
	
	/**
	 * Gets the version of the database server
	 * @return string the version string
	 */
	public function getVersion() {
		return $this->scalar('SELECT VERSION()');	
	}
}

?>
