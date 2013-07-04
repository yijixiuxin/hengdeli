<?php
/**
 *  An abstract of DB operation ;
 *  
 *	Features
 *		1. Multi-DB supports(MySQL , MongoDB ...);
 *      2. NoSQL-style;
 *      3. Result Enhance;
 *      4. More Security : auto escape name and value;
 *
 *	Limitations
 *		1. (for MySQL) : Primary key is required , and auto increasement , and named 'id' ;
 *
 *	Connection
 *		1. Permanent Connection
 *		2. Instance : close connection after DB class
 *		3. Query : close connection after per query
 *
 *	Usage 
 *
 *		$config = 'dbtype://user:password@host:port/?charset=utf8';
 *		$db = DB::Instance($config);
 *		
 *		$db->showDBs($options=NULL);
 *		$db->showTables($dbname,$options=NULL);
 *	    $db->showColumns($dbname,$tablename,$options=NULL);
 *		$db->showIndexes($dbname,$tablename,$options=NULL);
 *		$db->showTriggers($dbname,$tablename,$options=NULL);
 *		$db->showViews($dbname,$options=NULL);
 *		$db->showProcedures($dbname,$options=NULL);
 *		$db->showVariables($dbname,$options=NULL);
 *		
 *		$db->createDB($dbname,$params=NULL);
 *		$db->createTable($dbname,$tablename,$params);
 *		$db->createView($name,$params);
 *		$db->createIndex($name,$params);
 *
 *		$db->dropDB($dbname,$options=NULL);
 *		$db->dropTable($dbname,$tablename,$options=NULL);
 *		$db->dropView($dbname,$viewname,$options=NULL);
 *		$db->dropIndex($dbname,$indexname,$options=NULL);
 *
 *		$result = $db->select($dbname,$tablename,$where = array(),$options=NULL);
 *		$result = $db->count($dbname,$tablename,$where = array());
 *		$result = $db->increase($dbname,$tablename,array('field1'=>1,'field2'=>-1),$where);
 *		$result = $db->insert($dbname,$tablename,$value=array());
 *		$result = $db->insertBatch($dbname,$tablename,$values=array(array));
 *		$result = $db->update($dbname,$tablename,$value,$where);
 *		$result = $db->replace($dbname,$tablename,$value,$where);
 *		$result = $db->delete($dbname,$tablename,$where);
 *		$result = $db->query($sql);
 *
 */


/* Predefined Constants */
define('DB_MODE_LOG_ERROR',1<<0);  // log error
define('DB_MODE_ECHO_ERROR',1<<1); // echo error
define('DB_MODE_LOG_SLOW',1<<2);   // log slow queries
define('DB_MODE_LOG_SQL',1<<3);    // log all sqls
define('DB_MODE_ECHO_SQL',1<<4);    // echo all sqls

/* */
if(!function_exists('is_hash')) {
    function is_hash ($object) {
        if(!is_array($object)) return FALSE;
        $i=0;
        foreach($object as $key=>$value) { if($key!==$i++) return TRUE; }
        return FALSE;
    }
}

/* Procedure-Oriented */
function db ($config) {
	return DB::Instance($config);
}

/* Object-Oriented */
class DB {

    /* DB Instances */
    public static $Pool = array();

    /* Mode */
    public static $Mode = 0 ;

    /* LogPath (log will saved in $LogPath/host.port.datetime.[log|err|slow] ) */
    //public static $Log = '/var/';
    public static $Log = '';

    /* Factory construct */
	public static function Instance ($config = NULL) {
        if (!$config) { return FALSE; }
        else if (!isset(self::$Pool[$config])) {
            $configs = self::Config($config);
            $db = FALSE;
            switch ($configs['type']) {
				case 'mysql' : $db = new MySQLNormal($configs); break;
                case 'mysqli' : $db = new MySQLImprove($configs); break;
				// case 'mongodb' : return new MongoDB( $configs , $log );
			}
            if($db===FALSE) return FALSE;
            self::$Pool[$config] = $db;
        }
        return self::$Pool[$config];
	}

	/* Convert config to standard array */
	public static function Config ($config) {
        $configs = parse_url($config);
        // 'On seriously malformed URLs, parse_url() may return FALSE.'
        if ( $configs === FALSE ) return FALSE; 
        // rename scheme to type
        $configs['type'] = $configs['scheme']; unset($configs['scheme']);
        // make server as host:port
        $configs['server'] = $configs['host']; if(isset($configs['port'])) { $configs['server'] .= ':'.$configs['port'];}
        // no use of path
        unset($configs['path']);
        // make query string to array and merge to config
        if(isset($configs['query'])) { 
            parse_str($configs['query'],$queries); 
            $configs = array_merge($configs,$queries); 
            unset($configs['query']); 
        }
        return $configs;
	}
}

/* MySQLBase (mainly contains sql) */
class MySQLBase {

	protected $_configs ;
    public $error ;

	function __construct ($configs = NULL) {
		$this->_configs = $configs ;
	}

	public function showDBs ($options = NULL) {
		$sql = "SHOW DATABASES";    // SHOW FULL 
		$data = $this->query($sql);
		if($data===FALSE) { return FALSE; }
		$result = array(); foreach($data as $d) { $result[] = $d['Database']; }
		return $result;
	}

	public function showTables ($dbname) {
		$dbname = $this->_escapeName($dbname);
		$sql = "SHOW TABLES FROM {$dbname}"; // SHOW FULL TABLES 
		$data = $this->query($sql);
		if ( $data === FALSE ) { return FALSE ;}
		$result = array();
		foreach( $data as $d ) { $result[] = $d['Tables_in_' .  str_replace('`','',$dbname) ]; }
		return $result ;
	}

	public function select ($db, $table, $where = NULL, $options = array()) {
		$db = $this->_escapeName($db);
        $table = $this->_escapeName($table);
        $fields = $this->_buildFields(isset($options['fields']) ? $options['fields'] : NULL);
        $one = is_int($where) || is_string($where);
        $where = $this->_buildWhere($where);
        $sql = "SELECT {$fields} FROM {$db}.{$table} WHERE {$where}";
        if ( isset( $options['index'] ) ) {
            $index = $options['index'];
            $sql .= ' ' . $index ;
        }
        if ( isset( $options['order'] ) ) {
            // (deprecated) eg: array('id'=>1, 'time'=>-1) => ORDER BY `id` ASC, `time` DESC
            if (is_hash($options['order'])) { 
                $order = array();
                foreach( $options['order'] as $key => $val ) {
                    if ( $val == 1 ) { $order[] = $this->_escapeName($key) . ' ASC'; }
                    elseif ( $val == -1 ) { $order[] = $this->_escapeName($key) . ' DESC'; }
                }
                $order = implode(', ', $order);
            }
            // eg: array('id','-age') => ORDER BY `id` ASC, `name` ASC, `age` DESC
            elseif (is_array($options['order'])) {
                $order = array();
                foreach( $options['order'] as $ord ) {
                    if($ord[0]!='-') { $ord = str_replace('+','',$ord); $order[] = $this->_escapeName($ord) . ' ASC'; }
                    else { $ord = str_replace('-','',$ord); $order[] = $this->_escapeName($ord) . ' DESC'; }
                }
                $order = implode(', ', $order);
            }
            // eg: '-id' => ORDER BY `id` DESC
            elseif (is_string($options['order'])) {
                $order = $options['order'];
                if($order[0]!='-') { $order = str_replace('+','',$order); $order = $this->_escapeName($order) . ' ASC'; }
                else { $order = str_replace('-','',$order); $order = $this->_escapeName($order) . ' DESC'; }
            }
            if(isset($order)) $sql .= ' ORDER BY ' . $order;
        }
        if ( isset( $options['limit'] ) ) {
            if ( is_int($options['limit']) ) {
                $limit = 'LIMIT ' . $options['limit'] ;
            }
            elseif ( is_array($options['limit']) && count($options['limit']) == 2 ) {
                $limit = 'LIMIT ' . $options['limit'][0] . ', ' . $options['limit'][1];
            }
            $sql .= ' ' . $limit;
        }
        $result = $this->query($sql) ;
        if( $result && $one) {
            $result = isset($result[0]) ? $result[0] : NULL;
        }
        return $result;
	}

	public function count ($db, $table, $where) {
		$db = $this->_escapeName($db);
        $table = $this->_escapeName($table);
		$where = $this->_buildWhere($where);
		$sql = "SELECT COUNT(*) AS C FROM {$db}.{$table} WHERE {$where}";
		$result = $this->query($sql);
		if ($result) { $result = $result[0]['C']; }
		return $result ;
	}

	public function insert ($db, $table, $data) {
		$db = $this->_escapeName($db);
        $table = $this->_escapeName($table);
		list($fields, $values) = $this->_buildInsert($data);
		$sql = "INSERT INTO {$db}.{$table} ( {$fields} ) VALUES ( {$values} )";
		return $this->query($sql);
	}
	
	public function update ($db, $table, $data, $where) {
		$db = $this->_escapeName($db);
        $table = $this->_escapeName($table);
		$set = $this->_buildSet($data);
		$where = $this->_buildWhere($where);
		$sql = "UPDATE {$db}.{$table} SET {$set} WHERE {$where}";
		return $this->query($sql);
	}

    public function replace ($db, $table, $data) {
        $db = $this->_escapeName($db);
        $table = $this->_escapeName($table);
		list( $fields , $values ) = $this->_buildInsert( $data );
		$sql = "REPLACE INTO {$db}.{$table} ( {$fields} ) VALUES ( {$values} )";
		return $this->query($sql);
    }

	public function increase ($db, $table, $data, $where) {
		$db = $this->_escapeName($db);
        $table = $this->_escapeName($table);
		$increase = $this->_buildIncrease($data);
		$where = $this->_buildWhere($where);
		$sql = "UPDATE {$db}.{$table} SET {$increase} WHERE {$where}";
		return $this->query($sql);
	}

	public function delete ($db, $table, $where ) {
		$db = $this->_escapeName($db);
        $table = $this->_escapeName($table);
		$where = $this->_buildWhere($where);
		$sql = "DELETE FROM {$db}.{$table} WHERE {$where}";
		return $this->query($sql);
	}

	public function query ($sql) {
        // by sub class
        return FALSE;
	}

	private function _buildFields ( $fields ) {
		if ( is_array($fields) ) {
            $result = array();
            foreach( $fields as $key => $field ) {
                // supports 'AS'
                if ( is_int($key) ) {
                    $result[] = $this->_escapeName($field);
                }
                else {
                    $result[] = $this->_escapeName($key) . ' AS ' . $this->_escapeName($field);
                }
            }
            return implode(',',$result);
        }
        else {
            return '*';
        }
	}

	private function _buildWhere ( $where ) {
		if (empty($where)){
            return '1';
        }
        // id = conditions
        elseif (is_string($where) || is_int($where)) { // todo: supports raw sql
            return $this->_escapeName('id').' = '.$this->_escapeValue($where);
        }
        // map hash to conditions
        elseif (is_hash($where)) {
            $result = array();
            foreach( $where as $key => $val ) {
                if ( is_int( $val ) ) {
                    $result[] = $this->_escapeName($key) . ' = ' . $val ;
                }
                elseif ( is_string( $val ) ) {
                    $result[] = $this->_escapeName($key) . ' = \'' . $this->_escapeValue($val) . '\'' ;
                }
                elseif( is_hash($val) ) {
                    // LIKE
                    if(isset($val['like'])) {
                        $result[] = $this->_escapeName($key) . ' LIKE \'%' . $this->_escapeValue($val['like']) . '%\'';
                    }
                }
                elseif ( is_array( $val ) ) {
                    $_temp = array();
                    foreach( $val as $v ) {
                        if ( is_int( $v ) ) {
                            $_temp[] = $this->_escapeName($key) . ' = ' . $v ;
                        }
                        elseif ( is_string( $v ) ) {
                            $_temp[] = $this->_escapeName($key) . ' = \'' . $this->_escapeValue($v) . '\'' ;
                        }
                    }
                    $result[] = '(' . implode(' OR ', $_temp) . ')';
                }
            }
            return implode(' AND ', $result);
        }
        // id IN (id1, id2)
        elseif (is_array($where)) {
            // escape where values and add slashes
            $_where = array(); 
            foreach($where as $w) {
                $v = $this->_escapeValue($w);
                if(is_string($w)) $v = "'{$v}'";
                $_where[] = $v;
            }
            return $this->_escapeName('id') . ' IN (' . implode(' , ', $_where) . ') ';
        }
        else {
            return '1';
        }
	}

	private function _buildInsert ( $data ) {
		if ( empty($data) ) {
			return array('','');
		}
		$fields = array();
		$values = array();
		foreach( $data as $key => $val ) {
			$fields[] = $this->_escapeName($key);
			$values[] = is_int($val) ? $val : '\'' . $this->_escapeValue($val) . '\'' ;
		}
		return array(implode(' , ' , $fields),implode(' , ' , $values));
	}

	private function _buildSet ( $set ) {
		$result = array();
		foreach( $set as $key => $val ) {
			if ( is_int ($val) ) {
				$result[] = $this->_escapeName($key) . ' = ' . $val ;
			}
			else {
				$result[] = $this->_escapeName($key) . ' = \'' . $this->_escapeValue($val) . '\'' ;
			}
		}
		return implode(',',$result);
	}
	
	private function _buildIncrease ( $data ) {
		if ( empty( $data ) ) {
			return '1=1';
		}
		$result = array();
		foreach($data as $key => $val) {
			if ( !is_int($val) ) { 
				continue; 
			}
			$result[] = $this->_escapeName($key) . ' = ' . $this->_escapeName($key) . ( $val > 0 ? ' + ' : ' - ' ) . abs($val) ;
		}
		return implode(' , ' , $result);
	}

	private function _escapeName ( $name ) {
		return ( strpos($name , '`') === FALSE ) ? "`{$name}`" : $name ;
	}

	private function _escapeValue ( $value ) {
        if(get_magic_quotes_gpc()) return $value;
		return addslashes( $value ) ;
	}

    protected function _logger ($type, $msg) {
        $mode = DB::$Mode ;
        $log = DB::$Log && is_dir(DB::$Log);
        $date = date('Y-m-d');
        $time = date('H:i:s');
        if ($mode & DB_MODE_LOG_ERROR) {
            if ($type=='error'&&$log) {
                file_put_contents(DB::$Log.$date.'.'.'err',$time.'`'.$msg);
            }
        }
        if ($mode & DB_MODE_ECHO_ERROR) {
            if ($type=='error') echo $msg;
        }
        if ($mode & DB_MODE_LOG_SLOW) {
            if ($type=='slow'&&$log) {
                file_put_contents(DB::$Log.$date.'.'.'slow',$time.'`'.$msg);
            }
        }
        if ($mode & DB_MODE_LOG_SQL) {
            if ($type=='error'&&$log) {
                file_put_contents(DB::$Log.$date.'.'.'sql',$time.'`'.$msg);
            }
        }
        if ($mode & DB_MODE_ECHO_SQL) {
            if ($type=='sql') echo $msg;
        }
    }
}

class MySQLNormal extends MySQLBase {

    public function __construct ($config) {
        parent::__construct($config);    
    }

    public function query ($sql, $multi=FALSE) {
		$this->error = NULL ;
        $this->_logger('sql', $sql);

		if ( empty($sql) ) { 
			$this->error = 'MySQL->query($sql) : $sql is empty !'; 
            $this->_logger('error', $this->error);
            return FALSE ;
		}
		if ( empty($this->_configs) ) { 
			$this->error = 'MySQL->query() : $configs is empty !'; 
            $this->_logger('error', $this->error);
            return FALSE ;
		}
		// check sql type : read (select,show) , insert , update (update,delete,replace,create)
		if ( preg_match('/^SELECT|SHOW/i' , $sql) ) {
			$crud = 'read';
		}
		elseif ( preg_match('/^INSERT/i' , $sql) ) {
			$crud = 'insert';
		}
		elseif ( preg_match('/^UPDATE|DELETE|REPLACE|DROP|CREATE/i' , $sql) ) {
			$crud = 'update';
		}
		else {
			$this->error = 'MySQL->query() : $sql is error !' ; 
            $this->_logger('error', $this->error);
            return FALSE;
		}

        $con = mysql_connect($this->_configs['server'], $this->_configs['user'], $this->_configs['pass']) ;
        if ( $con === FALSE ) {
            $this->error = 'MySQL->query() : [' . mysql_errno() . '] : ' . mysql_error() ; 
            $this->_logger('error', $this->error);
            return FALSE ;
        }
        if ( isset( $this->_configs['charset'] ) ) {
            mysql_set_charset($this->_configs['charset'], $con);
        }
        if ( isset($this->_configs['db']) ){
            mysql_select_db($this->_configs['db'], $con);
        }
        if ( !empty($this->_db) ) {
            mysql_select_db($this->_db,$con);
        }
        $data = mysql_query( $sql, $con );
        if ( $data === FALSE ) {
            $this->error = 'MySQL->query() : ' . $sql . ' ' . mysql_errno($con) . ':' . mysql_error($con); 
            $this->_logger('error', $this->error);
            return FALSE ;
        }
        else {
            if ( $crud == 'read' ) { 
                $result =  array();
                if(is_resource($data)) { // why ? when insert with a exist key
                    while ($row = mysql_fetch_assoc($data)) $result[] = $row;
                    mysql_free_result($data);
                }
            } elseif ( $crud == 'insert' ) {
                $result = mysql_insert_id($con);
            } else {
                $result = mysql_affected_rows($con);
            }
        }
        
        return $result ;
	}
}

class MySQLImprove extends MySQLBase {

    public function __construct ($config) {
        parent::__construct($config);    
    }

    public function query ($sql, $multi=FALSE) {
        $this->error = NULL ;
        $this->_logger('sql', $sql);

		if ( empty($sql) ) { 
			$this->error = 'MySQL->query($sql) : $sql is empty !'; 
            $this->_logger('error', $this->error);
            return FALSE ;
		}
		if ( empty($this->_configs) ) { 
			$this->error = 'MySQL->query() : $configs is empty !'; 
            $this->_logger('error', $this->error);
            return FALSE ;
		}
		// check sql type : read (select,show) , insert , update (update,delete,replace,create)
		if ( preg_match('/^SELECT|SHOW/i' , $sql) ) {
			$crud = 'read';
		}
		elseif ( preg_match('/^INSERT/i' , $sql) ) {
			$crud = 'insert';
		}
		elseif ( preg_match('/^UPDATE|DELETE|REPLACE|DROP|CREATE/i' , $sql) ) {
			$crud = 'update';
		}
		else {
			$this->error = 'MySQL->query() : $sql is error !' ; 
            $this->_logger('error', $this->error);
            return FALSE;
		}

        list($_server, $_port) = explode(':',$this->_configs['server']);
        $con = new mysqli($_server, $this->_configs['user'], $this->_configs['pass'], NULL, $_port);
        if ( $con->connect_error ) {
            $this->error = 'MySQLi->query() : [' . $con->connect_errno . '] : ' . $con->connect_error ;
            $this->_logger('error', $this->error);
            return FALSE ;
        }
        if ( isset( $this->_configs['charset'] ) ) {
            $con->set_charset($this->_configs['charset']);
        }
        if ( isset($this->_configs['db']) ){
            $con->select_db($this->_configs['db']);
        }
        if ( !empty($this->_db) ) {
            $con->select_db($this->_db);
        }
        if($multi) return $con->multi_query($sql);
        $data = $con->query($sql);
        if ( $data === FALSE ) {
            $this->error = 'MySQLi->query() : ' . $sql . ' ' . $con->errno . ':' . $con->error; 
            $this->_logger('error', $this->error);
            return FALSE ;
        }
        else {
            if ( $crud == 'read' ) {
                $result = array();
                while($row=$data->fetch_assoc()){
                    $result[] = $row;
                }
                $data->close();
            } elseif ( $crud == 'insert' ) {
                $result = $con->insert_id;
            } else {
                $result = $con->affected_rows;
            }
        }
        
        return $result ;
    }
}