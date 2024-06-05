<?php

namespace DB\Couch;

class Mapper extends \DB\Cursor {

    protected $db;
    protected $fields;
    protected $document = [];
    protected $props = [];

    function __construct(\DB\Couch $db, $class) {
        $this->db = $db;
        $this->document = [self::getPrimaryKey() => 'VARCHAR(255) PRIMARY KEY'] + $class::$getFieldsAndType;
        array_walk($this->document, function (&$v) { $v = null; });
    }

    public static function getPrimaryKey()
    {
        return '_id';
    }

    function dbtype() {
        return 'Couch';
    }

    public function changed($key = null)
    {
        $orig = $this->query[0]->document;
        $new = $this->document;

        if ($key) {
            return $orig[$key] !== $new[$key];
        }

        return serialize($orig) !== serialize($new);
    }

    function exists($key) {
        $db_exists = false;
        try {
            $d = (array) $this->db->getDbInfos();
        }catch(\Exception $e) {
            $d = [];
        }
        if (!isset($d['db_name'])) {
            return false;
        }
        return array_key_exists($key, $this->document);
    }

    function set($key, $val) {
        $this->props[$key] = $val;
        return $this->document[$key] = $val;
    }

    function &get($key) {
        if ($this->exists($key)) {
            return $this->document[$key];
        } elseif (array_key_exists($key, $this->props)) {
            return $this->props[$key];
        }
        user_error(sprintf(self::E_Field, $key), E_USER_ERROR);
    }

    function clear($key) {
        unset($this->document[$key]);
    }

    function fields() {
        return array_keys($this->document);
    }

    function cast($obj = null) {
        if (!$obj) {
            $obj = $this;
        }
        return $obj->document;
    }

    function findAll($selector = [], $limit = false) {
        $pre_docs = $this->db->findAll($limit);
        $docs = [];
        foreach($pre_docs as $p) {
            if (strpos($p->id, '_') !== 0) {
                $p->_id = $p->id;
                $docs[] = $p;
            }
        }
        return $docs;
    }
    function find($filter = null, array $options = null, $ttl = 0) {
        if (!$filter) {
            return $this->findAll();
        }
        return $this->select($this->fields, $filter, $options, $ttl);
    }

    function select($fields=NULL,$filter=NULL,array $options=NULL,$ttl=0) {
        if (! $filter) {
            trigger_error('select function must have filter', E_USER_ERROR);
        }

        if(isset($filter[0]) && isset($filter[1]) && strpos($filter[0], '_id') === 0) {
            $doc = $this->db->getDoc($filter[1]);
            return [$this->factory($doc)];
        }

        if(isset($filter['_id'])) {
            $doc = $this->db->getDoc($filter['_id']);
            return [$this->factory($doc)];
        }

        return $this->db->find($filter);
    }

    function factory($row)
    {
        $mapper = clone($this);
        $mapper->reset();

        if ($row) {
            foreach ($row as $key => $val) {
                $mapper->document[$key] = $val;
            }
        }

        $mapper->query = [clone($mapper)];

        if (isset($mapper->trigger['load'])) {
            \Base::instance()->call($mapper->trigger['load'],$mapper);
        }

        return $mapper;
    }

    public function count($filter = null, array $options = null, $ttl = 0) {
        $fw=\Base::instance();
        $cache=\Cache::instance();
        $tag='';
        if (is_array($ttl)) {
            list($ttl,$tag)=$ttl;
        }
        $hash = $fw->hash($fw->stringify([$filter])).($tag?'.'.$tag:'').'.mongo';
        $cached = $cache->exists($hash, $result);
        if (! $cached || ! $ttl || $cached[0] + $ttl < microtime(TRUE)) {
            $result = count($this->select($this->fields, $filter));
            if ($fw->CACHE && $ttl) {
                // Save to cache backend
                $cache->set($hash,$result,$ttl);
            }
        }
        return $result;
    }

    /**
    *	Return record at specified offset using criteria of previous
    *	load() call and make it active
    *	@return array
    *	@param $ofs int
    **/
    function skip($ofs=1) {
        $this->document=($out=parent::skip($ofs))?$out->document:[];
        if ($this->document && isset($this->trigger['load']))
        \Base::instance()->call($this->trigger['load'],$this);
        return $out;
    }

    function insert() {
        if (isset($this->document['_rev'])) {
            return $this->update();
        }
        if (isset($this->trigger['beforeinsert']) && \Base::instance()->call($this->trigger['beforeinsert'], [$this, ['_id' => $this->document['_id']]]) === false) {
            return $this->document;
        }
        $result = $this->db->saveDoc($this->document);
        $pkey = ['_id' => $result->id];
        if (isset($this->trigger['afterinsert'])) {
            \Base::instance()->call($this->trigger['afterinsert'], [$this, $pkey]);
        }
        $this->load($pkey);
        return $this->document;
    }

    function update() {
        $pkey=['_id' => $this->document['_id']];
        if (isset($this->trigger['beforeupdate']) && \Base::instance()->call($this->trigger['beforeupdate'], [$this, $pkey]) === false) {
            return $this->document;
        }
        $this->db->saveDoc($this->document);
        if (isset($this->trigger['afterupdate'])) {
            \Base::instance()->call($this->trigger['afterupdate'], [$this, $pkey]);
        }
        return $this->document;
    }

    function copyfrom($var, $func = null) {
        if (is_string($var)) {
            $var = \Base::instance()->$var;
        }
        if ($func) {
            $var = call_user_func($func, $var);
        }
        foreach ($var as $key => $val) {
            $this->set($key,$val);
        }
    }

    function copyto($key) {
        $var= &\Base::instance()->ref($key);
        foreach ($this->document as $key => $field) {
            $var[$key] = $field;
        }
    }

    function getiterator() {
        return new \ArrayIterator($this->cast());
    }

    function __call($func,$args) {
        $callable = (array_key_exists($func,$this->props) ? $this->props[$func] : $this->$func);
        return $callable ? call_user_func_array($callable,$args) : null;
    }

}
