<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;
use gmars\rbac\Rbac;
use Request;

    class Common extends Controller
    {
          public function __construct()
          {
           parent:: __construct();
           $name=Session::get('name');

          if (empty($name)) { 
          	  $this->redirect('login/login');
          }else{
          	 $this->assign('name',$name);
          }
           //验证是否有权限
          $module=Request::module();           //当前模块名称
          $class = Request::controller();     //当前控制器名称
          $action=Request::action();       //当前操作名称
          $arr_class=['Permission','Permissioncate','Role','User'];
          $arr_action=['show','delete','addaction','updateaction'];
          //in_array() 函数搜索数组中是否存在指定的值。
          if (in_array($class, $arr_class)) {
              if (in_array($action, $arr_action)) {
                $str="$module/$class/$action";
                $str=strtolower($str);     //全部转为小写
                $rbac = new Rbac();
                $bool=$rbac->can($str); //用户请求时进行验证
                if (!$bool) {
                   header("content-Type:application/json");
                   $arr=['code'=>'10001','status'=>'error','data'=>'没有权限'];
                   echo json_encode($arr);
                   die;
                }
              }
            }
          }

           public function commonToken()
          {
              $token = $this->request->token('__token__', 'sha1');
              $arr=['token'=>$token];
              echo json_encode($arr);
              // $this->assign('token', $token);
              // return $this->fetch();
          }
    
    }
