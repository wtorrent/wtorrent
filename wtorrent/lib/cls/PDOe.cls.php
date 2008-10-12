<?php
/**
 * Slightly enhanced PDO adding some "shortcut" functionality
 * Written by tn123
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *   May you do good and not evil.
 *   May you find forgiveness for yourself and forgive others.
 *   May you share freely, never taking more than you give.
 */

class PDOeStatement extends PDOStatement
{
	/**
	 * Bind one or more values to this statement
	 * @param mixed $values,... One or more values to bind. If the first parameter is an array then that array will be bound (supports associative array to named parameters binding)
	 */
	public function bindValues($values = null /*, $values, ...*/)
	{
		if ($values == null)
		{
			$values = array();
		}
		if (!is_array($values))
		{
			$values = func_get_args();
		}

		foreach ($values as $key => $value)
		{
			if (is_string($key))
			{
				$this->bindValue($key, $value);
			}
			else
			{
				$this->bindValue($key + 1, $value);
			}
		}
	}
};

class PDOe extends PDO
{
	/**
	 * Creates a PDOe instance representing a connection to a database.
	 * @see PDO::__construct
	 */
	public function __construct($dsn, $username = null, $password = null, array $driver_options = null)
	{
		parent::__construct($dsn, $username, $password);
		$this->setAttribute(PDO::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('PDOeStatement'));
	}

	/**
	 * Prepares a statements and binds some values to it
	 * @param string $sql The statement to prepare
	 * @param mixed $values,... Values to bind
	 * @return PDOeStatement Statement
	 * @see PDOeStatement::bindValues
	 */
	public function prepareAndBind($sql, $values = null /*, $values, ...*/)
	{
		$stmt = $this->prepare($sql);

		if ($values == null)
		{
			$values = array();
		}
		if (!is_array($values))
		{
			$values = func_get_args();
			// remove $sql
			array_shift($values);
		}
		$stmt->bindValues($values);

		return $stmt;
	}

	/**
	 * Executes a query and returns the first row
	 * @param string $sql The statement to query
	 * @param mixed $values,... Values to bind
	 * @return array Row
	 * @see PDOeStatement::bindValues
	 * @see PDOe::prepareAndBind
	 */
	public function query($sql, $values = null /*, $values, ... */)
	{
		if ($values == null)
		{
			$values = array();
		}
		if (!is_array($values))
		{
			$values = func_get_args();
			// remove $sql
			array_shift($values);
		}
		$stmt = $this->prepareAndBind($sql, $values);
		$stmt->execute();
		$rv = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $rv;
	}

	/**
	 * Executes a query and returns the first column from the first row
	 * @param string $sql The statement to query
	 * @param mixed $values,... Values to bind
	 * @return mixed Value
	 * @see PDOeStatement::bindValues
	 * @see PDOe::prepareAndBind
	 */
	public function queryColumn($sql, $values = null /*, $values, ... */)
	{
		if ($values == null)
		{
			$values = array();
		}
		if (!is_array($values))
		{
			$values = func_get_args();
			// remove $sql
			array_shift($values);
		}
		$stmt = $this->prepareAndBind($sql, $values);
		$stmt->execute();
		$rv = $stmt->fetch(PDO::FETCH_COLUMN);
		return $rv;
	}
	
	/**
	 * Executes a query and returns the first column from the first row
	 * @param string $sql The statement to query
	 * @param mixed $values,... Values to bind
	 * @return array Rows
	 * @see PDOeStatement::bindValues
	 * @see PDOe::prepareAndBind
	 */
	public function queryAll($sql, $values = null /*, $values, ... */)
	{
		if ($values == null)
		{
			$values = array();
		}
		if (!is_array($values))
		{
			$values = func_get_args();
			// remove $sql
			array_shift($values);
		}

		$stmt = $this->prepareAndBind($sql, $values);
		$stmt->execute();
		$rv = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $rv;
	}
	/**
	 * Executes a query and returns the first column from each row
	 * @param string $sql The statement to query
	 * @param mixed $values,... Values to bind
	 * @return array Values
	 * @see PDOeStatement::bindValues
	 * @see PDOe::prepareAndBind
	 */
	public function queryColumnAll($sql, $values = null /*, $values, ... */)
	{
		if ($values == null)
		{
			$values = array();
		}
		if (!is_array($values))
		{
			$values = func_get_args();
			// remove $sql
			array_shift($values);
		}
		$stmt = $this->prepareAndBind($sql, $values);
		$stmt->execute();
		$rv = $stmt->fetchAll(PDO::FETCH_COLUMN);
		return $rv;
	}

	/**
	 * Executes a modifying query.
	 * @param string $sql The statement to query
	 * @param mixed $values,... Values to bind
	 * @see PDOeStatement::bindValues
	 * @see PDOe::prepareAndBind
	 */
	public function modify($sql, $values = null /*, $values, ... */)
	{
		if ($values == null)
		{
			$values = array();
		}
		if (!is_array($values))
		{
			$values = func_get_args();
			// remove $sql
			array_shift($values);
		}
		$stmt = $this->prepareAndBind($sql, $values);
		$stmt->execute();
	}

	/**
	 * Executes modifying queries using different values in an transaction
	 * @param string $sql The statement to query
	 * @param array $values,... Values to bind. Each array item will be bound. Item should be an (associative) array for binding multiple (named) values.
	 * @see PDOeStatement::bindValues
	 * @see PDOe::prepareAndBind
	 */
	public function modifyMany($sql, array $values)
   	{
		$this->beginTransaction();
		$stmt = $this->prepare($sql);
		foreach ($values as $value)
		{
			$stmt->bindValues($value);
			$stmt->execute();
		}
		$this->commit();
	}
};
?>
