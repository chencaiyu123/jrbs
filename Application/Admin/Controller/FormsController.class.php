<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class FormsController extends Controller {

    private $userInfo;

    public function __construct(){
        parent::__construct();
        $this->userInfo = session('userInfo');
//        if(!$this->userInfo['is_finance'] && !$this->userInfo['is_salas'] && !$this->userInfo['is_admin']){
//            $this->error('无权访问该页面');
//        }
    }

    public function formsList(){
        $userInfo  = session('userInfo');

        $pagenum  = I('post.pagenum',2);
        if($userInfo['group_leader'] && $userInfo['is_salas'] == 1){ //组长看见该租全部提交的报单信息
            $map['group'] = $userInfo['group'];
            $map['id']    = array('NEQ',$userInfo['id']);
            $groupUser = M('user')->where($map)->select();
            $dataCount = M('baodan')->where(array('salas_id'=>$userInfo['id'], 'status' => 0 ,'use' => 0))->count();
            $pageCount = ceil($dataCount / $pagenum);

            $this->assign('pageCount',$pageCount);
            $this->assign('groupUser',$groupUser);
        }elseif ($userInfo['is_salas'] == 1){ //业务员看见自己报单信息

        }elseif($userInfo['is_finance'] == 1){ //财务看所有的报单信息
            $group     = M('groups')->select();
            $dataCount = M('baodan')->where(array('status' => 0 ,'use' => 0))->count();
            $pageCount = ceil($dataCount / $pagenum);

            $this->assign('pageCount',$pageCount);
            $this->assign('group',$group);
        }


        $this->display('formslist');
    }

    public function dataList(){
            if(IS_AJAX){
                $length     = I('post.length',2);
                $start      = I('post.start',0);
                $draw       = I('post.draw');
                $salas_id   =I('post._user');
                $starttime  =I('post._starttime');
                $endtime    = I('post._endtime');
                $group      = I('post._group');
                $groupUser      = I('post._groupuser');
                $userInfo   = session('userInfo');

                $baodan =  M('baodan');
                $map['status']   = 0;
                $map['use']      = 0;
                if($starttime && $endtime){$map['starttime'] = $starttime? array('BETWEEN', array($starttime,$endtime)):array();}

                if ($userInfo['group_leader'] && $salas_id){
                    $map['salas_id'] = $salas_id;
                    $dataList  = $baodan->where($map)->order('id desc')->limit($start,$length)->select();
                    $sql1       = $baodan->getlastsql();
                    $dataCount = $baodan->where($map)->count();
                }
                elseif($userInfo['is_salas'] == 1){
                    $map['salas_id'] = $userInfo['id'];
                    $dataList = $baodan->where($map)->order('id desc')->limit($start,$length)->select();
                    $sql = $baodan->getlastsql();
                    $dataCount = $baodan->where($map)->count();
                }
                elseif ($userInfo['is_finance'] == 1){
                    if($groupUser)$map['salas_id'] = $groupUser;
                    if($group && !$groupUser){
                        $user = M('user');
                        $groupUser = $user->where(array('group' => $group))->field('id')->select();
                        foreach ($groupUser as $k => $v){
                            $userIdList[] = $v['id'];
                        }
                        $groupUserString = implode(',',$userIdList);
                        $map['salas_id'] = array('in',$groupUserString);
                    }
                    $dataList = $baodan->where($map)->order('id desc')->limit($start,$length)->select();

                    $dataCount = $baodan->where($map)->count();
                    $sql = $baodan->getlastsql();
                }
                $dataList  = empty($dataList)?array():$dataList;
                $this->ajaxreturn(array('debug'=>$sql, 'draw'=> intval($draw),'recordsTotal'=>intval($dataCount), 'recordsFiltered'=>intval($dataCount),'data'=>$dataList ,'code' => 200 , 'msg' => '获取数据成功'));
            }
    }



    public function add(){
        $this->display('add');
    }



    public function postData(){
        if(IS_AJAX){
            if(!$this->userInfo['is_salas']){
                $ajaxData = array('code' =>201,'info' => 'error', 'msg' => '您并无权限提交报单' );
                $this->ajaxreturn($ajaxData);
            }
            $data['bill_number'] = I('post.piaohao');
            $data['accepting_bank'] = I('post.bank');
            $data['amount'] = I('post.amount')*10000;
            $data['starttime'] = I('post.starttime');
            $data['endtime'] = I('post.endtime');
            $data['adjustment_days'] = I('post.day');
            $data['days_remaining'] = I('post.shengyutianshu');
            $data['money_rate'] = floatval(I('post.money_rate'));
            $data['mark_up'] = floatval(I('post.markup'));
            $data['modify_prices'] = floatval(I('post.modify_price'));
            $data['company_quotation'] = I('post.gongsibaojia');
            $data['intermediary_quotation'] = I('post.zhongjiefei');
            $data['pay_subscription_fee_type'] = I('post.selectdakuanfei');
            $data['big_fare'] = I('post.piaokuan');
            $data['small_fare'] = I('post.xiaokuan');
            $data['big_fare_customer_account'] = I('post.dakuanzhanghao');
            $data['big_fare_customer_bank'] = I('post.bankname');
            $data['big_fare_customer_name'] = I('post.dakuankehu');
            $data['big_fare_customer_phone'] = I('post.kehushouji');
            $data['big_fare_bank_number'] = I('post.hanghao');
            $data['small_fare_customer_account'] = I('post.xiaokuanzhanghao');
            $data['small_fare_customer_bank'] = I('post.xiaokuankaihuhang');
            $data['small_fare_customer_name'] = I('post.xiaokuankehuxingming');
            $data['small_fare_customer_phone'] = I('post.xiaokuankehushouji');
            $data['remarks'] = I('post.beizhu');
            $data['other'] = I('post.other');
            $data['creation_date'] = time();
            $data['salas_id'] = session('userInfo.id');
            $data['pay_subscription_fee_type'] == 1? $data['subscription_fee'] = I('post.dapiaodakuanfei'):$data['subscription_fee'] = I('post.xiaopiaodakuanfei');
            $data['big_fare_customer_account'] = str_replace(' ', '', $data['big_fare_customer_account']);
            $data['small_fare_customer_account'] = str_replace(' ', '', $data['small_fare_customer_account']);
            $Model = M('baodan');
            $result = $Model->add($data);
            sleep(1);
            if($result){
                $ajaxData = array('code' =>200,'info' => 'success' , 'msg' => '新增报单成功' );
                $this->ajaxreturn($ajaxData);
            }else{
                $ajaxData = array('code' =>201,'info' => 'error', 'msg' => '新增保单啊失败，请重试' );
                $this->ajaxreturn($ajaxData);
            }
        }
    }


    /**
     *ajax获取组内成员
     */

    public function getGroupUser(){
        if(IS_AJAX) {
            $group = I('post.group');
            $user = M('user');
            $groupUsers = $user->where(array('group' => $group))->field('id,name,group')->select();
            if ($groupUsers) {
                $ajaxData = array('code' => 200, 'data' => $groupUsers, 'msg' => '获取数据成功');
            } else {
                $ajaxData = array('code' => 200, 'data' => array(), 'msg' => '获取数据成功');
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    /**
     * 报单详情
     */

    public function details(){
        $id = I('get.id');
        if(!$id){
            $this->error('无权访问该页面');
        }
        $baodan = M('baodan');
        $user   = M('user');
        $result = $baodan->where(array('id' =>$id))->find();

        $result['amount'] = $result['amount']/10000;
        $salasUser   = $user->where(array('id' => $result['salas_id']))->field('name')->find();
        $financeUser = $user->where(array('id' => $result['finance_id']))->field('name')->find();
        $result['salas_name'] = $salasUser['name'];
        $result['finance_name'] = $financeUser['name'];

        $this->assign('data',$result);
        $this->display('excel');
    }

    public function edit(){
        $id = I('get.id');
        echo '编辑'.$id;
    }

    public function delStatus(){
        if(IS_AJAX){
            $id = I('post.id');
            $salas_id = I('post.salas_id');
            $userInfo = session('userInfo');
            if($salas_id != $userInfo['id']){
                $ajaxData = array('code' => 201 ,'msg' => '无权删除不属于自己的报单', 'data'=>array());
            }
            $data['status'] = 3;
            $baodan = M('baodan');
            $result = $baodan->where(array('id' => $id))->save($data);
            if($result){
                $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '更改数据成功');
            }else{
                $ajaxData = array('code' => 201, 'data' => $result, 'msg' => '更改数据失败');
            }
            $this->ajaxreturn($ajaxData);
        }
    }

    public function updateStatus(){
        if(IS_AJAX) {
            $id = I('post.id');
            $userInfo = session('userInfo');
            if($userInfo['is_salas']){$data['salas_id'] = I('post.userid');}
            elseif($userInfo['	is_finance']){$data['finance_id'] = I('post.userid');}
            $data['status'] = I('post.status');
            $data['finance_id'] = $userInfo['id'];
            if ($id) {
                $baodan = M('baodan');
                $result = $baodan->where(array('id' => $id))->save($data);
                if ($result) {
                    $ajaxData = array('code' => 200, 'data' => $result, 'msg' => '更改数据成功');
                } else {
                    $ajaxData = array('code' => 201, 'data' => $result, 'msg' => '更改数据失败');
                }
            } else {
                $ajaxData = array('code' => 201, 'data' => array(), 'msg' => '没有可用的id');
            }
            $this->ajaxreturn($ajaxData);
        }
    }





}