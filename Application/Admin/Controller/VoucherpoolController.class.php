<?php

namespace Admin\Controller;
use Think\Controller;


class VoucherpoolController extends Controller
{

    private $userInfo;
    private $Db;

    public function __construct()
    {
        parent::__construct();
        $this->userInfo = session('userInfo');
//        if (!$this->userInfo['is_finance']) {
//            $this->error('无权访问该页面');
//        }
        $this->Db = M('baodan');
    }


    public function lists(){
        $this->display('lists');
    }

    public function getLists(){
        if(IS_AJAX){
            $length     = I('post.length',2);
            $start      = I('post.start',0);
            $draw       = I('post.draw');

            $map['status']   = 1;
            $dataList  = $this->Db->where($map)->limit($start,$length)->order('id desc')->select();
            $dataCount = $this->Db->where($map)->count();

            $this->ajaxreturn(array('debug'=>'', 'draw'=> intval($draw),'recordsTotal'=>intval($dataCount), 'recordsFiltered'=>intval($dataCount),'data'=> $dataList  = empty($dataList)?array():$dataList ,'code' => 200 , 'msg' => '获取数据成功'));
        }
    }



}
