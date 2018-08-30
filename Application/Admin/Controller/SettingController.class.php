<?php

namespace Admin\Controller;
use function PHPSTORM_META\map;
use Think\Controller;
use Think\Model;

class SettingController extends Controller {

    private $userInfo;
    private $userDb;
    private $auth;

    public function __construct(){
        parent::__construct();
        $this->userInfo = session('userInfo');
        $this->auth = session('auth');
        $this->checkAuth();
        $this->userDb = M('user');
    }

    public function addUser(){
        $company = $this->getCompany();
        $group   = $this->getGroup();
        $department = $this->getDepartment();

        $this->assign('department',$department);
        $this->assign('company',$company);
        $this->assign('group',$group);
        $this->display('addUser');
    }

    public function delUser()
    {
        if(IS_AJAX){
            $map['id'] = I('post.id');
            if(empty($map['id']))  $this->ajaxreturn(array('code' => 201, 'msg' => '参数丢失'));
            $del = $this->userDb->where($map)->delete();
            if($del){
                $this->ajaxreturn(array('code' => 200, 'msg' => '删除成功'));
            }else{
                $this->ajaxreturn(array('code' => 201, 'msg' => '删除失败'));
            }
        }
    }

    public function addGroup(){
        $department   = $this->getDepartment();
        $this->assign('department',$department);
        $this->display('addGroup');
    }

    public function delGroup()
    {
        if(IS_AJAX){
            $id = I('post.id');
            if(empty($id))  $this->ajaxreturn(array('code' => 201, 'msg' => '参数丢失'));
            $map['group'] = $id;
            $user = $this->userDb->where($map)->select();
            if(empty($user)){
                $model =  M('groups');
                $tem['id'] = $id;
                $del = $model->where($tem)->delete();
                if($del){
                    $this->ajaxreturn(array('code' => 200, 'msg' => '删除成功'));
                }else{
                    $this->ajaxreturn(array('code' => 201, 'msg' => '删除失败'));
                }
            }else{
                $this->ajaxreturn(array('code' => 201, 'msg' => '删除失败，请先删除对应组下的用户'));
            }
        }
    }

    public function addAdmin(){
        $this->display('addAdmin');
    }

    public function addCompany(){
        $this->display('addCompany');
    }

    public function delCompany()
    {
        if(IS_AJAX){
            $map['id'] = I('post.id');
            if(empty($map['id']))  $this->ajaxreturn(array('code' => 201, 'msg' => '参数丢失'));
            $model =  M('company');
            $del = $model->where($map)->delete();
            if($del){
                $this->ajaxreturn(array('code' => 200, 'msg' => '删除成功'));
            }else{
                $this->ajaxreturn(array('code' => 201, 'msg' => '删除失败'));
            }
        }
    }

    public function addDepartment(){
        $this->display('addDepartment');
    }

    public function delDepartment()
    {
        if(IS_AJAX){
            $id = I('post.id');
            if(empty($id))  $this->ajaxreturn(array('code' => 201, 'msg' => '参数丢失'));
            $map['did'] = $id;
            $gModel = M('groups');
            $group = $gModel->where($map)->select();
            if(empty($group)){
                $model =  M('department');
                $tem['id'] = $id;
                $del = $model->where($tem)->delete();
                if($del){
                    $this->ajaxreturn(array('code' => 200, 'msg' => '删除成功'));
                }else{
                    $this->ajaxreturn(array('code' => 201, 'msg' => '删除失败'));
                }
            }else{
                $this->ajaxreturn(array('code' => 201, 'msg' => '删除失败，请先删除对应部门下的分组'));
            }
        }
    }

    /*添加分类
     * */
    public function addCategory(){
        $this->display('addCategory');
    }

    public function delCategory()
    {
        if(IS_AJAX){
            $map['id'] = I('post.id');
            if(empty($map['id']))  $this->ajaxreturn(array('code' => 201, 'msg' => '参数丢失'));
            $model =  M('category');
            $del = $model->where($map)->delete();
            if($del){
                $this->ajaxreturn(array('code' => 200, 'msg' => '删除成功'));
            }else{
                $this->ajaxreturn(array('code' => 201, 'msg' => '删除失败'));
            }
        }
    }


    public function getCompany(){
        $company = M('company');
        $data = $company->order('id')->select();
        return $data ? $data : array();
    }

    public function getGroup(){
        $group = M('groups');
        $data = $group->order('id')->select();
        return $data ? $data : array();
    }

    public function getUsers($type){
        $user = M('user');
        $map = array();
        if($type == 1){
            $map['group_leader'] = array('EXP','IS NULL');
            $map['is_admin'] = array('EXP','IS  NULL');
            $map['is_finance'] = array('EXP','IS  NULL');
            $map['is_salas'] = 1;
        }elseif ($type == 2){
            $map['is_admin'] = array('EXP','IS  NULL');
        }
        $data = $user->where($map)->field('id,name,group,group_leader')->order('id desc')->select();
        return $data ? $data : array();
    }

    public function getDepartment(){
            $department = M('department');
            $result  =  $department->order('id desc')->select();
            return $result? $result : array();
    }

    public function ajaxPostData(){
        if(IS_AJAX) {
            $data['username'] = I('post.username');
            $data['name'] = I('post.name');
            $data['phone'] = I('post.phone');
            $data['password'] = I('post.password');
            $data['wechat'] = I('post.companywechat');
            $data['email'] = I('post.email');
            $position = I('post.position');
            $data['cid'] = I('post.company');
            $data['did'] = I('post.department');
            $data['created'] = time();

            if ($position['salas']) {
                $data['is_salas'] = 1;
            } elseif ($position['finance']) {
                $data['is_finance'] = 1;
            }
            if (I('post.group')) {
                $data['group'] = I('post.group');
            }

            if ($this->userDb->where(array('username' => $data['username']))->find()) {
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '此用户账号已存在');
            } else {
                $data['password'] = md5($data['password'].sha1($data['username']));
                $result = $this->userDb->add($data);
                if ($result) {
                    $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '添加用户成功');
                } else {
                    $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '添加用户失败，请重试');
                }
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    public function ajaxPostAdmin()
    {
        if(IS_AJAX){
            $data['username'] = trim(I('post.username'));
            $data['name'] = trim(I('post.name'));
            $data['password'] = I('post.password');
            $data['admin_level'] = I('post.level');
            $data['created'] = time();

            $model = M('admin');
            if($model->where(array('username' => $data['username']))->find()){
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '此管理员账号已存在');
            }else{
                $data['password'] = md5($data['password'].sha1($data['username']));
                $result = $model->add($data);
                if ($result) {
                    $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '添加管理员成功');
                } else {
                    $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '添加管理员失败，请重试');
                }
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    public function ajaxPostGroupLeader(){
        if (IS_AJAX){
            $data['group_name'] = trim(I('post.group'));
            $data['did'] = I('post.department');
            $data['created'] = time();
            $group = M('groups');

            $group_name = $group->where(array('group_name'=> $data['group_name']))->find();
            if($group_name){
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '这个组名已经存在');
                $this->ajaxreturn($ajaxData);
            }
            $result = $group->add($data);
            if ($result) {
                $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '添加组成功');
            } else {
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '添加组失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    public function ajaxPostCompany(){
        if (IS_AJAX){
            $data['company'] = trim(I('post.company'));
            $data['tag']     = trim(I('post.tag'));
            $data['created'] = time();
            $company = M('company');
            $company_name = $company->where(array('company' => $data['company']))->find();
            if($company_name){
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '该企业已存在');
                $this->ajaxreturn($ajaxData);
            }
            $result = $company->add($data);
            if ($result) {
                $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '添加企业成功');
            } else {
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '添加企业失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    public function ajaxPostDepartment(){
        if (IS_AJAX){
            $data['department']     = I('post.department');
            $data['created'] = time();
            $department = M('department');
            $result = $department->add($data);
            if ($result) {
                $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '添加部门成功');
            } else {
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '添加部门失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    public function ajaxPostCategory(){
        if (IS_AJAX){
            $data['category']     = I('post.category');
            $data['created'] = time();
            $department = M('category');
            $result = $department->add($data);
            if ($result) {
                $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '添加分类成功');
            } else {
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '添加分类失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    public function checkAuth(){
        if($this->auth !== 'admin'){
            $this->error('无权访问该页面');
        }
    }


    public function usersList(){
        if(IS_AJAX){
            $length     = I('post.length',2);
            $start      = I('post.start',0);
            $draw       = I('post.draw');

            $map['is_admin'] = array('EXP','IS  NULL');
            $dataList   = $this->userDb->where($map)->limit($start,$length)->order('id desc')->select();
            foreach ($dataList as $key => &$value){
                $data  = M('company')->where(array('id' => $value['cid']))->field('company')->find();
                $data1 = M('department')->where(array('id' => $value['did']))->field('department')->find();
                $data2 = M('groups')->where(array('id' => $value['group']))->field('group_name')->find();
                $value['company'] = $data['company'];
                $value['department'] = $data1['department'];
                $value['group_name'] = $data2['group_name'];
                if($value['is_salas']){
                    $value['title'] = '业务员';
                }elseif ($value['is_finance']){
                    $value['title'] = '财务';
                }else{
                    $value['title'] = '';
                }
            }
            $dataCount  = $this->userDb->where($map)->count();
            $this->ajaxreturn(array('debug'=>'', 'draw'=> intval($draw),'recordsTotal'=>intval($dataCount), 'recordsFiltered'=>intval($dataCount),'data'=>$dataList ? $dataList : array() ,'code' => 200 , 'msg' => '获取数据成功'));
        }
        $this->display('userslist');
    }

    /**
     * 管理员列表
     */
    public function adminsList()
    {
        if(IS_AJAX){
            $length     = I('post.length',2);
            $start      = I('post.start',0);
            $draw       = I('post.draw');

            $model = M('admin');
            $dataList   = $model->limit($start,$length)->order('id desc')->select();
            foreach ($dataList as $k => $v){
                if($v['admin_level'] == 3){
                    $dataList[$k]['level'] = '三级管理员';
                }elseif ($v['admin_level'] == 2){
                    $dataList[$k]['level'] = '二级管理员';
                }else{
                    $dataList[$k]['level'] = '一级管理员';
                }
            }
            $dataCount  = $model->count();
            $this->ajaxreturn(array('debug'=>'', 'draw'=> intval($draw),'recordsTotal'=>intval($dataCount), 'recordsFiltered'=>intval($dataCount),'data'=>$dataList ? $dataList : array() ,'code' => 200 , 'msg' => '获取数据成功'));
        }
        $this->display('adminslist');
    }


    public function groupsList(){
        if(IS_AJAX){
            $length     = I('post.length',2);
            $start      = I('post.start',0);
            $draw       = I('post.draw');

            $group = M('groups');
            $department = M('department');
            $dataList = $group->limit($start,$length)->select();
            foreach ($dataList as $key => &$value){
                $group_name = $this->userDb->where(array('id' => $value['group_leader']))->field('name')->find();
                $value['group_leader_name']  = $group_name['name'];
                $department_name  = $department->where(array('id'=>$value['did']))->field('department')->find();
                $value['department_name'] = $department_name['department'];
            }
            $dataCount  = $group->count();
            $this->ajaxreturn(array('debug'=>'', 'draw'=> intval($draw),'recordsTotal'=>intval($dataCount), 'recordsFiltered'=>intval($dataCount),'data'=>$dataList ? $dataList : array() ,'code' => 200 , 'msg' => '获取数据成功'));
        }
        $this->display('groupslist');
    }

    public function companysList(){
        if(IS_AJAX){
            $length     = I('post.length',2);
            $start      = I('post.start',0);
            $draw       = I('post.draw');

            $company  = M('company');
            $dataList = $company->limit($start,$length)->select();
            foreach ($dataList as $key => &$value){
                $group_name = $this->userDb->where(array('id' => $value['uid']))->field('name')->find();
                $value['leader_name']  = $group_name['name'];
            }

            $dataCount  = $company->count();
            $this->ajaxreturn(array('debug'=>'', 'draw'=> intval($draw),'recordsTotal'=>intval($dataCount), 'recordsFiltered'=>intval($dataCount),'data'=>$dataList ? $dataList : array() ,'code' => 200 , 'msg' => '获取数据成功'));
        }

        $this->display('companyslist');
    }

    public function departmentsList(){
        if(IS_AJAX){
            $length     = I('post.length',2);
            $start      = I('post.start',0);
            $draw       = I('post.draw');

            $department  = M('department');
            $dataList = $department->limit($start,$length)->select();
            foreach ($dataList as $key => &$value){
                $group_name = $this->userDb->where(array('id' => $value['uid']))->field('name')->find();
                $value['leader_name']  = $group_name['name'];
            }

            $dataCount  = $department->count();
            $this->ajaxreturn(array('debug'=>'', 'draw'=> intval($draw),'recordsTotal'=>intval($dataCount), 'recordsFiltered'=>intval($dataCount),'data'=>$dataList ? $dataList : array() ,'code' => 200 , 'msg' => '获取数据成功'));
        }
        $this->display('departmentslist');
    }


    public function categorylist(){
        if(IS_AJAX){
            $length     = I('post.length',2);
            $start      = I('post.start',0);
            $draw       = I('post.draw');

            $category  = M('category');
            $dataList = $category->limit($start,$length)->select();
            $dataCount  = $category->count();
            $this->ajaxreturn(array('debug'=>'', 'draw'=> intval($draw),'recordsTotal'=>intval($dataCount), 'recordsFiltered'=>intval($dataCount),'data'=>$dataList ? $dataList : array() ,'code' => 200 , 'msg' => '获取数据成功'));
        }
        $this->display('categorylist');
    }

    //************************************************************编辑模块***************************************************//

    public function editAdmin()
    {
        $id = I('get.id');
        if(!$id) $this->error('缺少参数，请重新尝试');
        $model = M('admin');

        $this->display('editAdmin');
    }

    public function editGroup(){
        $id = I('get.id');
        if(!$id){
            $this->error('缺少参数，请重新尝试');
        }
        $group = M('groups');
        $department  = M('department');
        $groupRes = $group->where(array('id'=>$id))->find();
        $department     = $department->where(array('id'=>$groupRes['did']))->field('department')->find();
        $groupRes['department_name'] = $department['department'];
        $department    = $this->getDepartment();

        foreach ($department as $key => $value){
            if($value['id'] == $groupRes['did']){
                unset($department[$key]);
            }
        }

        $this->assign('department',$department);
        $this->assign('groupRes',$groupRes);
        $this->display('editGroup');
    }

    public function editDepartment(){
        $id = I('get.id');
        if(!$id){
            $this->error('缺少参数，请重新尝试');
        }
        $department = M('department');
        $departmentRes = $department->where(array('id'=>$id))->find();
        $this->assign('departmentRes',$departmentRes);
        $this->display('editdepartment');
    }

    public function editCategory()
    {
        $id = I('get.id');
        if(!$id){
            $this->error('缺少参数，请重新尝试');
        }
        $cate = M('category');
        $cateRes = $cate->where(array('id'=>$id))->find();
        $this->assign('cateRes',$cateRes);
        $this->display('editCategory');
    }

    public function ajaxEditCate(){
        if(IS_AJAX){
            $id                  = I('post.id');

            if(I('post.cate')){
                $data['category']  = I('post.cate');
            }

            $cate = M('category');
            $cate->startTrans();
            $res = $cate->where(array('id'=>$id))->save($data);
            if($res){
                $cate->commit();
                $ajaxData = array('code' => 200, 'data' => $res, 'msg' => '编辑成功');
            }else{
                $cate->rollback();
                $ajaxData = array('code' => 201, 'data' => $res, 'msg' => '编辑失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    public function ajaxEditDepartment(){
        if(IS_AJAX){
            $id                  = I('post.id');

            if(I('post.department')){
                $data['department']  = I('post.department');
            }
            if(I('post.users')){
                $data['uid']         = I('post.users');
            }

            $department = M('department');
            $department->startTrans();
            $res = $department->where(array('id'=>$id))->save($data);
            if($res){
                $department->commit();
                $ajaxData = array('code' => 200, 'data' => $res, 'msg' => '编辑成功');
            }else{
                $department->rollback();
                $ajaxData = array('code' => 201, 'data' => $res, 'msg' => '编辑失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }
    }


    public function ajaxEditGroupLeader(){
        if (IS_AJAX){
            $data['group_name'] = trim(I('post.group'));
            $map['id'] = I('post.id');

            if(I('post.department')){
                $data['did'] =  I('post.department');
            }
            if(I('post.users')){
                $data['group_leader'] = I('post.users');
            }

            $group = M('groups');
            $old_group_leader = I('old_group_leader');

            $group->startTrans();
            $result = $group->where($map)->save($data);

            if($old_group_leader){
                $this->userDb->where(array('id'=>$data['group_leader']))->save(array('group'=>$map['id'],'group_leader'=>1));
                $this->userDb->where(array('id'=>$old_group_leader))->save(array('group'=>null,'group_leader'=>null));
            }else{
                $this->userDb->where(array('id'=>$data['group_leader']))->save(array('group'=>$map['id'],'group_leader'=>1));
            }

            if ($result) {
                $group->commit();
                $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '编辑成功');
            } else {
                $group->rollback();
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '编辑失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }
    }



    public function editUser(){
        $id = I('get.id');
        if(!$id){
            $this->error('缺少参数，请重新尝试');
        }
        $group      = M('groups');
        $company    = M('company');
        $department = M('department');
        $user  = $this->userDb->where(array('id'=>$id))->find();
        $groupRes   = $group->where(array('id'=>$user['group']))->field('group_name')->find();
        $companyRes = $company->where(array('id'=>$user['cid']))->field('company')->find();
        $departmentRes = $department->where(array('id'=>$user['did']))->field('department')->find();



        $user['group_name'] = $groupRes['group_name'];
        $user['company_name'] = $companyRes['company'];
        $user['department_name'] = $departmentRes['department'];


        $company = $this->getCompany();
        $group   = $this->getGroup();
        $department = $this->getDepartment();
        foreach ($group as $key => $value){
            if($value['id'] == $user['group']) unset($group[$key]);
        }
        foreach ($company as $key => $value){
            if($value['id'] == $user['cid'])unset($company[$key]);
        }
        foreach ($department as $key => $value){
            if($value['id'] == $user['did'])unset($department[$key]);
        }

        $this->assign('department',$department);
        $this->assign('company',$company);
        $this->assign('group',$group);
        $this->assign('userInfo',$user);
        $this->display('editUser');
    }

    public function ajaxEditUser(){
        $data['username'] = I('post.username');
        $data['name'] = I('post.name');
        $data['phone'] = I('post.phone');
        $data['wechat'] = I('post.companywechat');
        $data['email'] = I('post.email');
        $position = I('post.position');
        $data['cid'] = I('post.company');
        $data['did'] = I('post.department');
        $map['id'] = I('post.id');

        if ($position['salas']) {
            $data['is_salas'] = 1;
            $data['is_finance'] = null;
        } elseif ($position['finance']) {
            $data['is_finance'] = 1;
            $data['is_salas'] = null;
            $data['group']    = null;
            $data['group_leader']    = null;
            $group_leader = $this->userDb->where($map)->field('group_leader,group')->find();
            $is_leader = $group_leader['group_leader'];
        }
        if (I('post.group')) {
            $data['group'] = I('post.group');
        }

        $this->userDb->startTrans();
        if($is_leader){
            $group = M('groups');
            $group->where(array('id'=>$group_leader['group']))->save(array('group_leader'=> null));
        }
        $result = $this->userDb->where($map)->save($data);
        if ($result) {
            $this->userDb->commit();
            $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '编辑用户成功');
        } else {
            $this->userDb->rollback();
            $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '编辑用户失败，请重试');
        }
        $this->ajaxreturn($ajaxData);
    }

    public function editCompany(){
        $id = I('get.id');
        if(!$id){
            $this->error('缺少参数，请重新尝试');
        }
        $company = M('company');
        $companyRes = $company->where(array('id'=>$id))->find();

        $this->assign('company',$companyRes);
        $this->display('editCompany');
    }


    public function ajaxEditCompany(){
        if(IS_AJAX){
            $id               = I('post.id');
            if(I('post.company')){
                $data['company']  = I('post.company');
            }

            if(I('post.users')){
                $data['uid']      = I('post.users');
            }


            $data['tag']      = I('post.tag');


            $company = M('company');
            $company->startTrans();
            $res = $company->where(array('id'=>$id))->save($data);
            if($res){
                $company->commit();
                $ajaxData = array('code' => 200, 'data' => $res, 'msg' => '编辑成功');
            }else{
                $company->rollback();
                $ajaxData = array('code' => 201, 'data' => $res, 'msg' => '编辑失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }
    }


    /**
     * 指派企业负责人
     */
    public function Principal(){
        $id = I('get.id');

        $company = M('company');
        $company = $company->where(array('id'=>$id))->find();
        $user    = $this->userDb->where(array('id' => $company['uid']))->field('name')->find();
        if($user){
            $company['user_name'] = $user['name'];
        }
        $users = $this->getUsers(2);
        foreach ($users as $k=>$v){
            if ($v['id'] == $company['uid']){unset($users[$k]);}
        }
        $this->assign('users',$users);
        $this->assign('company',$company);
        $this->display('principal');
    }

    /**
     * 指派部门负责人
     */
    public function departmentPrincipal(){
        $id = I('get.id');
        $department = M('department');
        $department = $department->where(array('id'=>$id))->find();
        $user    = $this->userDb->where(array('id' => $department['uid']))->field('name')->find();
        if($user){
            $department['user_name'] = $user['name'];
        }

        $users = $this->getUsers(2);
        foreach ($users as $k=>$v){
            if ($v['id'] == $department['uid']){unset($users[$k]);}
        }

        $this->assign('users',$users);
        $this->assign('department',$department);
        $this->display('departmentPrincipal');
    }


    /**
     * 指派分组负责人
     */
    public function groupPrincipal(){
        $id = I('get.id');
        $group = M('groups');
        $groupRes = $group->where(array('id'=>$id))->find();

        $department = M('department');
        $departmentRes = $department->where(array('id'=>$groupRes['did']))->field('department')->find();
        $groupRes['department'] = $departmentRes['department'];
        $users = $this->getUsers(1);
        foreach ($users as $k=>$v){
            if($v['id'] == $groupRes['group_leader']){
                $groupRes['user_name'] = $v['name'];
                unset($users[$k]);
            }
        }

        $this->assign('users',$users);
        $this->assign('group',$groupRes);
        $this->display('groupPrincipal');

    }

    /**
     * 添加企业关系链
     */
    public function addRelation(){
        $id = I('get.id');
        $company = M('company');
        $map['id']  = array('neq',$id);
        $data = $company->where($map)->order('id desc')->select();
        $map1['id'] = array('eq',$id);
        $res  = $company->where($map1)->find();

        $map2['cid'] = $id;
        $relation   = M('relation');
        $res2       = $relation->where($map2)->field('shangyou,xiayou')->find();


        $shangyou   = explode(',',$res2['shangyou']);
        $xiayou     = explode(',',$res2['xiayou']);

        if(IS_AJAX){
            $shangyou_str = implode(',',I('post.shangyou'));
            $xiayou_str   = implode(',',I('post.xiayou'));
            $Add_data['shangyou'] = $shangyou_str;
            $Add_data['cid']      = I('post.res');
            $Add_data['xiayou']   = $xiayou_str;
            $Add_data['created']  = time();

            $map2['cid'] =   $Add_data['cid'] ;
            $relation = M('relation');
            $res2       = $relation->where($map2)->field('shangyou,xiayou')->find();
            $isUpdata   = $res2?1:0;

            if($isUpdata == 1){
                $res = $relation->where('cid = '.$Add_data['cid'])->save($Add_data);
                if ($res) {
                    $ajaxData = array('code' => 200, 'data' => $res, 'msg' => '企业关系链更新成功');
                } else {
                    $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '企业关系链更新失败，请重试');
                }
            }else{
                $res = $relation->add($Add_data);
                if ($res) {
                    $ajaxData = array('code' => 200, 'data' => $res, 'msg' => '设置关系链成功');
                } else {
                    $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '设置关系链失败，请重试');
                }
            }
            $this->ajaxreturn($ajaxData);
        }

        $this->assign('res',$res);
        $this->assign('shangyou',$shangyou);
        $this->assign('xiayou',$xiayou);
        $this->assign('list',$data);
        $this->display('addRelation');

    }


    /**
     * 关系链报表
     */
    public function Reports(){

        $id = I('GET.id');
        $relation = M('relation');
        $c_res = $relation->where(array('cid'=>$id))->find();
        $shangyou_c = explode(',' ,$c_res['shangyou']);
        $xiayou_c = explode(',' ,$c_res['xiayou']);
        $company = M('company');
        $reports = M('reports');
        $category= M('category');



        foreach ($shangyou_c as $key => $val){
            $shangyou_res[] = $company->where(array('id'=>$val))->field('id,company')->find();
        }

        foreach ($xiayou_c as $key => $val){
            $xiayou_res[] = $company->where(array('id'=>$val))->field('id,company')->find();
        }

        if(count($shangyou_res)>count($xiayou_res)){
            foreach ($shangyou_res as $key => &$val){
                $data[$key]['shangyou_id']   = $val['id'];
                $data[$key]['shangyou_name'] = $val['company'];
                $data[$key]['xiayou_id']     = $xiayou_res[$key]['id'];
                $data[$key]['xiayou_name']   = $xiayou_res[$key]['company'];

                $data[$key]['shangyou_all_amount'] = $reports->where(array('sid'=>$data[$key]['shangyou_id'], 'cid' => $id))->sum('amount');
                $data[$key]['xiayou_all_amount']    = $reports->where(array('xid'=>$data[$key]['xiayou_id'], 'cid' => $id))->sum('amount');

            }
        }else if(count($shangyou_res)<count($xiayou_res)){
            foreach ($xiayou_res as $key => &$val){
                $data[$key]['shangyou_id']   = $shangyou_res[$key]['id'];
                $data[$key]['shangyou_name'] = $shangyou_res[$key]['company'];
                $data[$key]['xiayou_id']     = $val['id'];
                $data[$key]['xiayou_name']   = $val['company'];


                $data[$key]['shangyou_all_amount'] = $reports->where(array('sid'=>$data[$key]['shangyou_id'], 'cid' => $id))->sum('amount');
                $data[$key]['xiayou_all_amount']    = $reports->where(array('xid'=>$data[$key]['xiayou_id'], 'cid' => $id))->sum('amount');
            }
        } else{
            foreach ($shangyou_res as $key => &$val){
                $data[$key]['shangyou_id']   = $val['id'];
                $data[$key]['shangyou_name'] = $val['company'];
                $data[$key]['xiayou_id']     = $xiayou_res[$key]['id'];
                $data[$key]['xiayou_name']   = $xiayou_res[$key]['company'];

                $data[$key]['shangyou_all_amount'] = $reports->where(array('sid'=>$data[$key]['shangyou_id'], 'cid' => $id))->sum('amount');
                $data[$key]['xiayou_all_amount']    = $reports->where(array('xid'=>$data[$key]['xiayou_id'], 'cid' => $id))->sum('amount');

            }
        }

        $c_name = $company->where(array('id'=>$id))->field('company')->find();

        foreach ($data as $key => &$val){
            $val['shangyou_data'] = $reports->where(array('sid'=>$val['shangyou_id'],'cid'=>$id))->field('category_id,amount,time')->select();
            $val['xiayou_data'] = $reports->where(array('xid'=>$val['xiayou_id'],'cid'=>$id))->field('category_id,amount,time')->select();
        }

        foreach ($data as $key => &$val){
            foreach ($val['shangyou_data'] as $k => &$v){
                $category_name =$category->where(array('id'=>$v['category_id']))->field('category')->find();
                $v['category'] = $category_name['category'];
            }

            foreach ($val['xiayou_data'] as $k1 => &$v1){
                $category_name =$category->where(array('id'=>$v1['category_id']))->field('category')->find();
                $v1['category'] = $category_name['category'];
            }
        }


        foreach ($data as  $key => $value){
            $all_mount_shang += $value['shangyou_all_amount'];
            $all_mount_xia   += $value['xiayou_all_amount'];
        }


        $this->assign('c_name',$c_name['company']);
        $this->assign('data',$data);
        $this->assign('all_shang',$all_mount_shang);
        $this->assign('all_xia',$all_mount_xia);
        $this->assign('all_rows',count($data));
        $this->display('reports');
    }

    /**
     * 添加一笔交易
     */
    public function addTransaction(){
        $id  = I('get.id');
        $category = M('category')->select();


        if(IS_AJAX){
            $data = I('post.data');

            if($data['type'] == 1){  //上游公司
                $postData['cid']            = $data['id'];
                $postData['sid']            = $data['company'];
                $postData['amount']         = $data['price'];
                $postData['category_id']    = $data['category'];
                $postData['time']           = strtotime($data['time']);
                $postData['created']        = time();
            }else{
                //下游公司
                $postData['cid']            = $data['id'];
                $postData['xid']            = $data['company'];
                $postData['amount']         = $data['price'];
                $postData['category_id']    = $data['category'];
                $postData['time']           = strtotime($data['time']);
                $postData['created']        = time();
            }

            $res = M('reports')->add($postData);
            if ($res) {
                $ajaxData = array('code' => 200, 'data' => $res, 'msg' => '添加关系链数据成功');
            } else {
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '添加关系链数据失败，请重试');
            }
            $this->ajaxreturn($ajaxData);
        }

        $this->assign('category',$category);
        $this->assign('cid',$id);
        $this->display('addTransaction');

    }

    /**
     * 获取上下游公司 ajax
     */
    public function getRelationList()
    {
        if (IS_AJAX){
            $cid  =  I('post.cid');
            $type =  I('post.type',1);
            $relation = M('relation');
            $company =  M('company');
            $res = $relation->where(array('cid'=>$cid))->find();

            if($type == 1){
                $listData = explode(',',$res['shangyou']);

            }else{
                $listData = explode(',',$res['xiayou']);
            }

            foreach ($listData as $key => $val){
                $result  = $company->where(array('id'=> $val))->field('company')->find();
                $data[$key]['id'] = $val;
                $data[$key]['company'] = $result['company'];
            }



            $ajaxData = array('code' => 200, 'data' => $data);
            $this->ajaxreturn($ajaxData);
        }
    }
}