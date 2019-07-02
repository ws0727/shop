<?php
namespace app\admin\controller;
use think\Controller;
use think\captcha\Captcha;
use Request;
use Db;
use think\facade\Session;
use gmars\rbac\Rbac;
class Login extends Controller
{
    public function login()
    {
      return $this->fetch();
    }

      public function loginAction()
    {
     $code=Request::post('code');
     $name=Request::post('name');
     $password=Request::post('password');
     $captcha = new Captcha();
     if( !$captcha->check($code)){
       $arr=['code'=>'1','status'=>'error','message'=>'验证码错误☺'];
       echo json_encode($arr);
       die;
     }else{
        $where=['user_name'=>$name,'password'=>md5($password)];
        $res=Db::table('user')->where($where)->find();
        if (empty($res)) {
         $arr=['code'=>'2','status'=>'error','message'=>'用户名密码错误'];
         echo json_encode($arr);
         die;
        }else{
         $arr=['code'=>'0','status'=>'ok','message'=>'登录成功'];
         Session::set('name',$name);
         //存session
         echo json_encode($arr);
         die;
        }
     }
    }

    public function loginOut()
        {
         Session::delete('name');
         $this->redirect('login/login');
        }
}
