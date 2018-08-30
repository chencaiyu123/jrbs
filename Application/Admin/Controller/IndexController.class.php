<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

    /**
     * login登录页面
     */
    public function index(){
        $map['username'] = cookie('user');
       
        if($map['username']){
            $map['password'] = S($map['username']);
            $auth = S('auth');
            if($auth == 'admin'){
                $User = M('admin');
                $result = $User->where($map)->find();
            }else{
                $admin = M('user');
                $result = $admin->where($map)->find();
            }

            if($result){
                $this->redirect(createUrl('/index.php','forms','formsList'));
            }else{
                S($map['username'],null);
                cookie($map['username'],null,-1);
                S('auth',null);
            }
        }
        $this->display('login');
    }

    /**
     * 生成验证码
     */
    public function verify()
    {
        $config =    array(
            'fontSize'    =>    20,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false,
            'useCurve'   => false ,
            'imageW'   => 170,
            'imageH'   => 40,
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    /**
     * 检验验证码
     * @param 输入的验证码
     * @param string $id
     * @return bool
     */
    private function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    /**
     * 异步登录
     */
    public function login(){
        if(IS_AJAX){
            $name = trim(I('name'));
            $password  = trim(I('password'));
            $login     = I('login');
            $verify     = I('verify');
            if(!$name || !$password || !$verify){
                $ajaxData =  array('code'=> 400 , 'info'=> 'error' , 'msg'=>'用户名或密码或验证码不能为空');
            }
            $checkVerify = $this->check_verify($verify);
            if($checkVerify){
                $map['username']    = $name;
                if($login){
                    $admin = M('admin');
                    $map['password'] = md5($password.sha1($name));
                    $resutl  = $admin->where($map)->field('id,username,name,admin_level,created')->find();
                }else{
                    $User = M('user');
                    $map ['password']   = md5($password.sha1($name));
                    $resutl  = $User->where($map)->field('id,username,name,phone,wechat,email,group,group_leader,is_salas,is_finance,is_admin,admin_level,cid,did,created')->find();
                    if(!$resutl['is_finance'] && !$resutl['is_salas'] && !$resutl['is_admin'])
                    {
                        $ajaxData = array('code' => 201 , 'info' => 'success' , 'msg' =>'非销售，非财务人员不得进入');
                    }
                }
                sleep(1);
                if(!$resutl){
                    $ajaxData = array('code' => 202 , 'info' => 'success' , 'msg' =>'账号或密码错误');
                }else{
                    if($login){
                        S('auth', 'admin', time()+3600*72);
                        session('auth', 'admin');
                        $data['logined'] = time();
                        $admin->where($map)->save($data);
                    }else{
                        session('auth', 'user');
                    }
                    cookie('user', $name);
                    S($name,md5($password),time()+3600*72);

                    session('userInfo',$resutl);
                    $ajaxData = array('code' => 200 , 'info' => 'success' , 'msg' =>'登录成功');
                }
            }else{
                $ajaxData = array('code' => 202 , 'info' => 'success' , 'msg' =>'验证码错误');
            }

            $this->ajaxreturn($ajaxData);
        }
    }

    /**
     * 判断是否登录状态
     */
    public function checkLogin(){
        if(IS_AJAX) {
            $user = I('user');
            $userInfo = session('userInfo');
            if (!$userInfo) {
                $ajaxData = array('code' => 201, 'info' => 'error', 'msg' => '用户信息失效');
            }
            if ($user != $userInfo['username']) {
                $ajaxData = array('code' => 201, 'info' => 'error', 'msg' => '用户信息错误');
            } else {
                $ajaxData = array('code' => 200, 'info' => 'success', 'msg' => '用户信息验证通过');
            }
            $this->ajaxreturn($ajaxData);
        }
    }


    /**
     * 退出登录
     */
    public function signOut(){
        if(IS_AJAX){
            $userInfo = session('userInfo');
            cookie($userInfo['user'],null,time()-1);
            unset($_SESSION['userInfo']);
            if(empty(cookie('userInfo')) && empty($_SESSION['userInfo'])){
                $ajaxData = array('code' =>200,'info' => 'success' , 'msg' => '安全退出' );
            }else{
                $ajaxData = array('code' =>201,'info' => 'error' , 'msg' => '退出失败' );
            }
            sleep(1);
            $this->ajaxReturn($ajaxData);
        }
    }

    /**
     * 删除缓存文件
     */
    public function delCache(){
        if (IS_AJAX) {
            $cachePath = realpath(__ROOT__) . '\Application\Runtime\Cache\Admin';

            $result = opendir($cachePath);
            var_dump($result);exit();
            $ajaxData = array();
            while (($file = readdir($result)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (unlink($cachePath . "/" . $file) === false) {
                        $ajaxData['fail'] += 1;
                    } else {
                        $ajaxData['success'] += 1;
                    }
                }
            }
            closedir($result);
            $ajaxData['fail'] = $ajaxData['fail']?$ajaxData['fail']:0;
            $ajaxData['success'] = $ajaxData['success']?$ajaxData['success']:0;
            $ajaxData['code'] = 200;
            $ajaxData['info'] = 'success';
            $ajaxData['msg'] = '清除缓存';
            sleep(2);
            $this->ajaxreturn($ajaxData);
        }
    }


    public function updatePassword(){
        $this->display('password');
    }

    /**
     * 获取邮件名
     */
    public function getEmail()
    {
        $auth = I('post.login');
        $map['username'] = I('post.name');
        if(empty($map['username'])) $this->ajaxreturn(array('code' => 202, 'info' => '参数丢失', 'msg' => '请填写用户账号'));
        $model = $auth == 1 ?  M('admin') : M('user');
        $result = $model->where($map)->find();
        if(empty($result)){
            $this->ajaxreturn(array('code' => 201, 'info' => '未注册', 'msg' => '该账号未注册'));
        }else{
            $this->ajaxreturn(array('code' => 200, 'info' => 'success', 'msg' => '找回密码', 'email' => $result['email'], 'user' => $result['username'], 'auth' => $auth));
        }
    }

    public function forgetPassword()
    {
        $this->assign('email', I('get.email'));
        $this->assign('user', I('get.user'));
        $this->assign('auth', I('get.auth'));
        $this->display('forgetPassword');
    }

    /**
     * 发送邮件找回密码
     */
    public function findPassword()
    {
        $verify = I('post.verify');
        $sendemail = I('post.email');
        $user = I('post.user');
        $auth = I('post.auth');
        $checkVerify = $this->check_verify($verify);
        if($checkVerify){
            if(empty($sendemail)) $this->ajaxreturn(array('code' => 201 , 'msg' =>'email丢失'));
            if(empty($user)) $this->ajaxreturn(array('code' => 201 , 'msg' =>'user丢失'));
            $email = $sendemail;  //PS:这不两会嘛，反映民情给胡主席的邮件~~
            $title = '宝筹金融找回密码';  //标题
            $link = "{$_SERVER['HTTP_ORIGIN']}/Admin/Index/resetPassword?email={$email}&user={$user}&auth={$auth}";
            $content = "您好!用户：{$user}， 请点击下面的链接重置您的密码：<p></p>" . $link;  //邮件内容
            $result =  SendMail($email,$title,$content); //直接调用发送即可
            if($result){
                $this->ajaxreturn(array('code' => 200 , 'msg' =>'邮件已发送'));
            }else{
                $this->ajaxreturn(array('code' => 201, 'msg' =>'邮件发送失败'));
            }
        }else{
            $this->ajaxreturn(array('code' => 201, 'msg' =>'验证码错误'));
        }
    }

    public function resetPassword()
    {
        $this->assign('user',I('get.user'));
        $this->assign('auth',I('get.auth'));
        $this->display('resetPassword');
    }

    /**
     * 重设密码
     */
    public function setPassword()
    {
        $password = I('post.pwd');
        $name = I('post.user');
        $auth = I('post.auth');
        if(empty($password) || empty($name)) $this->ajaxreturn(array('code' => 201  , 'msg' =>'参数丢失'));
        $model = $auth == 1 ?  M('admin') : M('user');
        $data['password'] = md5($password.sha1($name));
        $map['username'] = $name;
        $update = $model->where($map)->save($data);
        if($update){
            $this->ajaxreturn(array('code' => 200 , 'msg' =>'密码设置成功'));
        }else{
            $this->ajaxreturn(array('code' => 201 , 'msg' =>'密码设置失败'));
        }
    }
}