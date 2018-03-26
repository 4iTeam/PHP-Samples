<?php
/**
 * @author 4iTeam https://www.facebook.com/groups/4it.community/
 */
require __DIR__.'/FileDataModel.php';
require __DIR__.'/User.php';
$user=new User('4it_community');//User với username là 4it_community

if(!$user->exists()){
    //User chưa tồn tại
    $user->user_name='4it_community';//user name
    $user->email='community@4it.top';
    $user->url='https://community.4it.top';
    $user->facebook='https://www.facebook.com/groups/4it.community/';
    $user->save();
}else{
    //user đã tồn tại
    echo $user->user_name.'<br>';
    echo $user->email.'<br>';
    echo $user->url.'<br>';
    echo $user->facebook.'<br>';

}
