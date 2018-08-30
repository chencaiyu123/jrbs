<?php

namespace Admin\Controller;
use Think\Controller;

class AcountController extends Controller
{

    private $userInfo;
    private $userDb;

    public function __construct()
    {
        parent::__construct();
        $this->userInfo = session('userInfo');
        if(S('auth') == 'admin'){
            $this->userDb = M('admin');
        }else{
            $this->userDb = M('user');
        }
    }

    /**
     * 用户信息
     */
    public function userInfo(){
        $userInfo = $this->userInfo;
        $dbUserInfo = $this->userDb->where(array('id'=>$userInfo['id']))->find();
        $company = M('company')->where(array('id'=>$userInfo['cid']))->find();
        $department = M('department')->where(array('id'=>$userInfo['did']))->find();
        $group = M('groups')->where(array('id'=>$userInfo['group']))->find();

        $this->assign('company', $company);
        $this->assign('department', $department);
        $this->assign('group', $group);
        $this->assign('userInfo', $dbUserInfo);
        $this->display('userInfo');
    }

    /**
     * 管理员信息
     */
    public function adminInfo()
    {

    }

    public function password(){
        $this->display('password');
    }

    /**
     * 修改密码
     */
    public function ajaxUpdatePassowrd(){
        if(IS_AJAX) {
            $data['password'] = trim(I('password'));
            $data['newpassword'] = trim(I('newpassword'));

            $map['id'] = $this->userInfo['id'];
            $map['password'] = md5($data['password'] . sha1($this->userInfo['username']));
            $result = $this->userDb->where($map)->find();
            if (!$result) {
                $ajaxData = array('code' => 201, 'msg' => '原始密码错误', 'data' => array());
            } else {
                $post['password'] = md5($data['newpassword'].sha1($this->userInfo['username']));
                $res = $this->userDb->where($map)->save($post);

                if ($res) {
                    $ajaxData = array('code' => 200, 'msg' => '密码修改成功', 'data' => $res);
                } else {
                    $ajaxData = array('code' => 201, 'msg' => '密码修改错误', 'data' => $res);
                }
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    /**
     * 修改用户信息
     */
    public function update(){
        if(IS_AJAX) {
            $data['phone'] = trim(I('post.phone'));
            $data['email'] = trim(I('post.email'));

            $result = $this->userDb->where(array('id' => $this->userInfo['id']))->save($data);
            if ($result) {
                $ajaxData = array('code' => 200, 'msg' => '信息更改成功', 'data' => $result);
            } else {
                $ajaxData = array('code' => 201, 'msg' => '信息更改失败', 'data' => $result);
            }
            $this->ajaxreturn($ajaxData);
        }
    }





}