<?php
namespace Admin\Controller;
use Think\Controller;


class CreditController extends Controller
{

    private $userInfo;
    private $Db;

    public function __construct()
    {
        parent::__construct();
        $this->userInfo = session('userInfo');
    }


    public function index(){

        $this->error('无权访问该页面');


//        $total=200;//红包总额
//        $num=5;// 分成8个红包，支持8人随机领取
//        $min=0.1;//每个人最少能收到0.01元
//
//        for ($i=1;$i<$num;$i++)
//        {
//            $safe_total=($total-($num-$i)*$min)/($num-$i);//随机安全上限
//            $money=mt_rand($min*100,$safe_total*100)/100;
//            $total=$total-$money;
//            echo '第'.$i.'个红包：'.$money.' 元，余额：'.$total.' 元 <br/>';
//        }
//        echo '第'.$num.'个红包：'.$total.' 元，余额：0 元';
    }
}
