<?php
/**
 * Created by PhpStorm.
 * User: fedde
 * Date: 2017/8/3
 * Time: 19:09
 */



/**
 * 重新定义URL
 */
function createUrl ($route,$controller,$function){
    $function = empty($function)? 'index':$function;
    switch ($controller){
        case 'main':
            return $route.U('main/'.$function);
        case 'forms':
            return $route.U('forms/'.$function);
        case 'invoice':
            return $route.U('invoice/'.$function);
        case 'bankservices':
            return $route.U('bankservices/'.$function);
        case 'setting':
            return $route.U('setting/'.$function);
        default:
            return $route.U('index/'.$function);
    }
}


function getCompany(){
    $company = M('company');
    $data = $company->order('id')->select();
    return $data ? $data : array();
}

function getGroup(){
    $group = M('group');
    $data = $group->order('id')->select();
    return $data ? $data : array();
}