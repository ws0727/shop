<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class User extends Common
	{
	    public function list()
	    {
         $rbac= new Rbac();
         $arr=Db::query("select name from role");
	      $this->assign('arr',$arr);
	      return $this->fetch();
	    }

	       public function addAction()
	    {
         
          $data=Request::post();
	       $validate = new \app\admin\validate\User;
            if (!$validate->check($data)) {
           $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
         }

         $data=Request::post();
	     $rbac=new Rbac();
	     $name=$data['name'];
	     $password=md5($data['password']);
	     $mobile=$data['mobile'];
	     $role_id=$data['role_id'];
	     $arr=Db::query("select *from user where user_name='$name' or mobile='$mobile'");
	     if (empty($arr)) {
	     	$datatime=date("Y-m-d H:i:s",time());
           $arr=Db::query("insert into user (`user_name`,`password`,`mobile`,`create_time`) values('$name','$password','$mobile','$datatime')");
            $user_id=Db::query("select id from user where user_name='$name'");
            $user_id=$user_id[0]['id'];
            $arr=$rbac->assignUserRole($user_id,[$role_id]);
             $arr=['code'=>0,'status'=>'ok','data'=>'添加成功'];
	         echo json_encode($arr);
	     }else{
             $arr=['code'=>1,'status'=>'error','data'=>'用户名或密码不能重复'];
             echo json_encode($arr);
	     }
	    
	  }

	    public function show()
	    {
         $rbac= new Rbac();
         $arr=Db::query("select user.id,user.user_name,user.password,user.mobile,user.create_time,user_role.role_id,role.name from user join user_role on user.id=user_role.user_id join role on user_role.role_id=role.id");
            echo  json_encode($arr);
	    }

	    public function updateAction()
	    {
           $data=Request::post();

	       $validate = new \app\admin\validate\User;
            if (!$validate->check($data)) {
           $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
         }  
		    unset($data['__token__']);
			$rbac=new Rbac();	
			$name=$data['name'];
			$password=md5($data['password']);
			$mobile=$data['mobile'];
		    $arr=Db::query("select *from user where user_name='$name' or mobile='$mobile'");
			if (empty($arr)||!empty($arr)&&$arr[0]['id']==$data['id']) {
		     $where=['user_name'=>$data['name'],'password'=>$data['password'],'mobile'=>$data['mobile']];	
			 $arr=Db::name('user')->where('id',$data['id'])->update($where);
			 $arr1=['role_id'=>$data['role_id']];
			 $arr=Db::table('user_role')->where('user_id',$data['id'])->update($arr1);
             $arr=['code'=>1,'status'=>'ok','data'=>'修改成功'];
		      	echo json_encode($arr);      	
	       }else{
	      	$arr=['code'=>3,'status'=>'error','data'=>'修改失败'];
	      	echo json_encode($arr);
	      	 }

	    }      
       

        public function delete()
        {
          $data=Request::post();
	     $validate = new \app\admin\validate\Delete;
            if (!$validate->check($data)) {
          $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
	      // $id=Request::post('id');
	      $arr=Db::table('user')->where('id',$data['id'])->delete();
	      $arr=Db::table('user_role')->where('user_id',$data['id'])->delete();
	      $arr=['code'=>'0','status'=>'ok', 'data'=>'删除成功'];
	      echo json_encode($arr);
	      die;
        }

        public function deletemore()
        {
          $data=Request::post();
          $id=Request::post('id');
 
	     $validate = new \app\admin\validate\Delete;
            if (!$validate->check($data)) {
          $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
         if (empty($id)){
	    	$arr=['code'=>1,'status'=>'error','data'=>'未选择删除对象'];
	    	echo json_encode($arr);
	      }
	      $id=explode(",", $id);
          array_shift($id);
          $id=implode(',', $id);
	      $arr=Db::table('user')->where('id','in',$id)->delete();
	      $arr=Db::table('user_role')->where('id','in',$id)->delete();
	      $arr=['code'=>'0','status'=>'ok', 'data'=>'删除成功'];
	      echo json_encode($arr);
	      die;
        }
     

        
   }
