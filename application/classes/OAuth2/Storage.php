<?php defined('SYSPATH') or die('No direct script access');

/**
 * OAuth2 Storage CRUD
 *
 * License is MIT, to be more compatible with PHP League.
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\OAuth2
 * @copyright  2014 Ushahidi
 * @license    http://mit-license.org/
 * @link       http://github.com/php-loep/oauth2-server
 */

use League\OAuth2\Server\Storage\AbstractStorage;

abstract class OAuth2_Storage extends AbstractStorage {

	protected $db = 'default';

	public function __construct($db = null)
	{
		if ($db)
		{
			$this->db = $db;
		}
	}

	private function applyWhereToQuery(Database_Query $query, array $where)
	{
		foreach ($where as $col => $value)
		{
			$query->where($col, is_array($value) ? 'IN' : '=', $value);
		}
		return $query;
	}

	protected function fetchResults(Database_Query $query)
	{
		$results = $query->execute($this->db);
		return count($results) ? $results->as_array() : FALSE;
	}

	protected function fetchSingleResult(Database_Query $query)
	{
		$results = $query->execute($this->db);
		return count($results) ? $results->current() : FALSE;
	}

	protected function fetchSingleColumn(Database_Query $query, $column)
	{
		$results = $query->execute($this->db);
		return count($results) ? $results->get($column) : FALSE;
	}

	protected function createSelectQuery($table, array $where = NULL)
	{
		$query = DB::select()
			->from($table);
		if ($where)
		{
			$this->applyWhereToQuery($query, $where);
		}
		return $query;
	}

	protected function executeInsert($table, array $data)
	{
		$query = DB::insert($table)
			->columns(array_keys($data))
			->values(array_values($data));
		list($id) = $query->execute($this->db);
		return $id;
	}

	protected function executeUpdate($table, array $data, array $where)
	{
		$query = DB::update($table)
			->set($data);
		$this->applyWhereToQuery($query, $where);
		$count = $query->execute($this->db);
		return $count;
	}
	
	protected function executeDelete($table, array $where)
	{
		$query = DB::delete($table);
		$this->applyWhereToQuery($query, $where);
		$count = $query->execute($this->db);
		return $count;
	}
}
