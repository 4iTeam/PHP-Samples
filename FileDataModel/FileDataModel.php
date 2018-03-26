<?php
/**
 * @author 4iTeam https://www.facebook.com/groups/4it.community/
 */
class FileDataModel{
    protected static $dataDir;
    protected static $indexFile;
    protected static $keyField;
    protected static $indexData=array();
    /**
     * @var array
     */
    protected $_data;

    function __construct($id_or_name=null){
        static::loadIndex();
        if($id_or_name!==null){
            $this->load($id_or_name);
        }else{
            $this->create();
        }

    }

    function load($id_or_name){
        $this->_data=null;
        if(!is_int($id_or_name)){
            $id_or_name=static::getIndexData($id_or_name);
        }
        if(is_int($id_or_name)){
            $id_or_name=abs($id_or_name);
            if($id_or_name) {
                $file = $this->getDataFile($id_or_name . '.json');
                $data = @json_decode(file_get_contents($file));
                if($data){
                    $this->_data=(array)$data;
                }

            }

        }
    }
    function exists(){
        return !empty($this->_data);
    }
    function create(){
        //$this->id=$this->nextId();
    }
    function nextId(){
        $idata=static::getIndexData();
        return end($idata)+1;
    }
    static function setIndexData($k,$v=null){
        $index=abs(intval($v));
        if($v===null){
            unset(static::$indexData[$k]);
        }else {
            if ($index && is_string($k)) {
                static::$indexData[$k] = $index;
            }
        }
    }
    static function getIndexData($k=null){
        if($k===null) {
            return static::$indexData;
        }
        return isset(static::$indexData[$k])?static::$indexData[$k]:null;
    }
    static function getDataFile($file=null){
        if($file){
            return rtrim(static::$dataDir,'/').'/'.$file;
        }
        return static::$dataDir;
    }
    static function getIndexFile(){
        if(!isset(static::$indexFile)){
            static::$indexFile=static::getDataFile('index.json');
        }
        return static::$indexFile;
    }
    static function loadIndex(){
        static::$indexData=@json_decode(file_get_contents(static::getIndexFile()));
        if(is_object(static::$indexData)){
            static::$indexData=get_object_vars(static::$indexData);
        }

        if(!is_array(static::$indexData)){
            static::$indexData=array();
            static::saveIndex();
        }
    }
    static function saveIndex(){
        file_put_contents(static::getIndexFile(),json_encode(static::$indexData));
    }

    function __get($name){
        if(isset($this->_data[$name])){
            return $this->_data[$name];
        }
        return null;
    }
    function __set($name, $value){
        if($name==static::$keyField){
            if(isset($this->_data[$name])){
                throw new \Exception($name. ' cannot be changed',12);
            }
            if(empty($value)){
                throw new \Exception($name. ' cannot be empty',12);
            }
            $this->load($value);
            if($this->exists()){
                throw new \Exception($name.'='.$value.' already exists',11);
            }
        }
        $this->_data[$name]=$value;
    }
    function save(){
        if(empty($this->_data[static::$keyField])){
            throw new \Exception(static::$keyField.' is not set',10);
        }
        if(!$this->id) {
            $this->id=$this->nextId();
        }
        $this->setIndexData($this->_data[static::$keyField],$this->id);
        static::saveIndex();
        @file_put_contents(static::getDataFile($this->id . '.json'), json_encode($this->_data));

        return true;
    }
    function delete(){
        if(!$this->exists()){
            return false;
        }
        $this->setIndexData($this->_data[static::$keyField],null);
        static::saveIndex();
        @unlink(static::getDataFile($this->id.'.json'));
        return true;
    }
}