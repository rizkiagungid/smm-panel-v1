<?php
/**
 * Penulis Kode - SMM Panel script
 * Domain: http://penuliskode.com/
 * Documentation: http://penuliskode.com/smm/script/version-n1/documentation.html
 *
 */

class Model {
	public function db_query($db, $select, $table, $where = null, $order = 'id DESC', $limit = '') {
		$query = mysqli_query($db, "SELECT ".$select." FROM ".$table." ORDER BY ".$order." ".$limit);
		if ($where <> null) {
			$query = mysqli_query($db, "SELECT ".$select." FROM ".$table." WHERE ".$where." ORDER BY ".$order." ".$limit);
		}
		if (mysqli_error($db)) {
			return false;
		} else {
			if (mysqli_num_rows($query) == 1) {
				$rows = mysqli_fetch_assoc($query);
			} else {
				$rows = [];
				while ($row = mysqli_fetch_assoc($query)) {
					$rows[] = $row;
				}
			}
			$result = array('query' => $query, 'rows' => $rows, 'count' => mysqli_num_rows($query));
			return $result;
		}
	}
	public function db_query_join($db, $select, $table, $join, $where = null, $order = 'id DESC', $limit = '') {
		$query = mysqli_query($db, "SELECT ".$select." FROM ".$table." ".$join." ORDER BY ".$order." ".$limit);
		if ($where <> null) {
			$query = mysqli_query($db, "SELECT ".$select." FROM ".$table." ".$join." WHERE ".$where." ORDER BY ".$order." ".$limit);
		}
		if (mysqli_error($db)) {
			return false;
		} else {
			if (mysqli_num_rows($query) == 1) {
				$rows = mysqli_fetch_assoc($query);
			} else {
				$rows = [];
				while ($row = mysqli_fetch_assoc($query)) {
					$rows[] = $row;
				}
			}
			$result = array('query' => $query, 'rows' => $rows, 'count' => mysqli_num_rows($query));
			return $result;
		}
	}
	public function db_insert($db, $table, $data) {
		if (!is_array($data)) {
			return false;
		} else {
			$query = mysqli_query($db, "INSERT INTO $table (".implode(', ', array_keys($data)).") VALUES ('".implode('\', \'', $data)."')");
			if (mysqli_error($db)) {
				return false;
			} else {
				return mysqli_insert_id($db);
			}
		}
	}
	public function db_update($db, $table, $data, $where) {
		if (!is_array($data)) {
			return false;
		} else {
			$update = "";
			$count = count($data);
			$i = 1;
			foreach ($data as $k => $v) {
				if ($i == $count) {
					$update .= $k." = '".$v."'";
				} else {
					$update .= $k." = '".$v."', ";
				}
				$i++;
			}
			$query = mysqli_query($db, "UPDATE $table SET $update WHERE $where");
			if (mysqli_error($db)) {
				return false;
			} else {
				return true;
			}
		}
	}
	public function db_delete($db, $table, $where) {
		$query = mysqli_query($db, "DELETE FROM $table WHERE $where");
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}