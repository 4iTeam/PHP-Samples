<?php
/**
 * @author 4iTeam https://www.facebook.com/groups/4it.community/
 */
class JsonDataSource implements \ArrayAccess, \IteratorAggregate{
    protected $__data=array();
    protected $__file;
    protected $__autoSave;
    protected $__readOnly;
    const SAVE_ON_EXIT=1;
    const SAVE_EVERY_TIME=2;
    function __construct($file,$autoSave=false,$readOnly=false){
        if(substr($file,-5)!='.json'){
            $file.='.json';
        }
        $this->__file=$file;
        $this->__autoSave=$autoSave;
        $this->setReadOnly($readOnly);
        if(file_exists($file)&&is_file($file)&&is_readable($file)){
            $data=@json_decode(file_get_contents($file),true);
            if($data){
                $this->__data=(array)$data;
            }
        }

    }
    function setReadOnly($flag=true){
        $this->__readOnly=$flag;
        return $this;
    }
    function __destruct(){
        if($this->__autoSave==self::SAVE_ON_EXIT) {
            $this->save();
        }
    }

    function setData($key,$val=''){
        if(is_array($key)){
            $this->__data=$key;
        }else{
            $this->__data[$key]=$val;
        }
        $this->autoSave();
        return $this;
    }
    function getData($key=null){
        if($key===null){
            return $this->__data;
        }
        return $this->offsetGet($key);
    }
    public function offsetGet($offset){
        return isset($this->__data[$offset])?$this->__data[$offset]:null;
    }
    public function offsetSet($offset, $value){
        $this->__data[$offset]=$value;
        $this->autoSave();
    }
    public function offsetExists($offset){
        return isset($this->__data[$offset]);
    }
    public function offsetUnset($offset){
        unset($this->__data[$offset]);
    }
    function __get($name){
        return $this->offsetGet($name);
    }
    function __set($name, $value){
        $this->offsetSet($name,$value);
    }
    function toArray(){
        return $this->getData();
    }
    protected function autoSave(){
        if($this->__autoSave==self::SAVE_EVERY_TIME){
            $this->save();
        }
    }
    function save(){
        if($this->__readOnly){
            return false;
        }
        $dir=dirname($this->__file);
        if(!file_exists($dir)){
        	mkdir($dir,0777,true);
        }
        return @file_put_contents($this->__file, json_encode($this->__data));
    }
    function update($attributes,$value=null){
    	if(!is_array($attributes)){
    		$attributes=[$attributes=>$value];
	    }
	    foreach ($attributes as $key=>$value){
    		$this->offsetSet($key,$value);
	    }
	    return $this->save();
    }
    function delete(){
        @unlink($this->__file);
    }
    function clear(){
        return $this->setData(array());
    }
    function isEmpty(){
        return empty($this->__data);
    }
    function hasData(){
        return !$this->isEmpty();
    }
    function getIterator() {
        return new \ArrayIterator($this->__data);
    }

}