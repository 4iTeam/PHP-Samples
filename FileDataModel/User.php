<?php
/***
 *
 */
/**
 * Class User
 * @package Admin\Model
 * @property string $user_name
 * @property string $email
 * @property string $name
 * @property string $token
 * @property string $pass
 */

define('USER_DATA_DIR',__DIR__);
class User extends FileDataModel{
    protected static $keyField='user_name';
    protected static $dataDir=USER_DATA_DIR;
    protected static $indexFile;
    protected static $indexData=array();

    function __set($a,$b){
        if($a=='pass'){
            throw new \Exception("Can't set \"pass\" directly. Use setPassword method instead");
        }
        parent::__set($a,$b);
    }
    function setPassword($password){
        $hash=md5($password);
        parent::__set('pass',$hash);
        $this->save();
    }

    /**
     * Make sure user have pass
     */
    function ensurePassword(){
        if(!$this->pass){
            $this->setPassword($this->generatePassword());
        }
    }
    protected function generatePassword( $length = 12, $special_chars = true, $extra_special_chars = false ) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        if ( $special_chars )
            $chars .= '!@#$%^&*()';
        if ( $extra_special_chars )
            $chars .= '-_ []{}<>~`+=,.;:/?|';

        $password = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $password .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }
    }

    function getID(){
        return $this->id;
    }

}
