<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
use Session;

	class Permissioncate extends Common
	{
	    public function list()
	    {
          $token=uniqid();
	      Session::set('token',$token);
	      $this->assign('token',$token);
	      return $this->fetch();
	    }


	      public function addAction()
	    {
	     $data=Request::post();//接值主要（data传过来的值）
	     $validate = new \app\admin\validate\Permissioncate;//验证器
            if (!$validate->check($data)) {//
          $data=['code'=>'2','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
	     
	     $rbac=new Rbac();
	     $getarr=$rbac->getPermissionCategory([['name', '=',$data['name']]]);//获取表里边数据
	     if (empty($getarr)) {
	     	$rbac->savePermissionCategory([ //将数据存入表中
		    'name' =>$data['name'],
		    'description' =>$data['description'],
		    'status' => 1
			]);//如果添加的数据表中没有的情况下直接将数据添加到表中
			$data=['code'=>'0','status'=>'ok', 'data'=>'添加成功'];
			echo json_encode($data);
		
			
	     }else{//否则表中已有该数据我们直接在页面显示数据已经存在
		    $data=['code'=>'1','status'=>'error', 'data'=>'分类已存在'];
			echo json_encode($data);//返回一个json字符串
			die;

	     }
		
	 }
	   
	    public function show()
	    {
	      $rbac=new Rbac();
	      $arr=$rbac->getPermissionCategory([['status', '=', 1]]);//查询表中数据
	      echo json_encode($arr);

	    }

       
         public function delete()
	    {

         $data=Request::post();
	     $validate = new \app\admin\validate1\Permissioncate;
            if (!$validate->check($data)) {
          $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
	      $id=Request::post('id');
	      $rbac=new Rbac();	
	      $rbac->delPermissionCategory($id);
	      $data=['code'=>'0','status'=>'ok', 'data'=>'删除成功'];
	      echo json_encode($data);
	      die;
	    }
	  

         public function deletemore()
	    {
     
         
         $data=Request::post();
	     $validate = new \app\admin\validate1\Permissioncate;
            if (!$validate->check($data)) {
          $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
	      $id=Request::post('id');
	      if (empty($id)) {
	        $data=['code'=>'0','status'=>'error', 'data'=>'未选择删除对象'];
	        echo  json_encode($data);
	        die;
	      }
	      $id=explode(",",$id);
	      array_shift($id);
	      $rbac=new Rbac();	
	      $rbac->delPermissionCategory($id);
	      $data=['code'=>'0','status'=>'ok', 'data'=>'删除成功'];
	      echo json_encode($data);
	      die;
	    }
	  


	   public function updateAction()
	    {
	     
         $data=Request::post();//接值主要（data传过来的值）
	     $validate = new \app\admin\validate\Permissioncate;//验证器
            if (!$validate->check($data)) {//
          $data=['code'=>'302','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
	     
	     $rbac=new Rbac();
	     $getarr=$rbac->getPermissionCategory([['name', '=',$data['name']]]);//获取数据表中数据
	     if (empty($getarr)) { 
	     	//x将数据添加到数据库中
	     	Db::table('permission_category')->where('id',$data['id'])->update(['name'=>$data['name'],'description'=>$data['description']]);

			$data=['code'=>'0','status'=>'ok', 'data'=>'修改成功'];
			echo json_encode($data);
			die;
	     }else{
	     	if ($getarr[0]['id']!=$data['id']) {
	     		//当数据库中id不等于修改的id显示分类已经存在
     		    $data=['code'=>'1','status'=>'error', 'data'=>'分类已存在'];
     			echo json_encode($data);
     			die;
	     			     	
	     	}else{

 		    	Db::table('permission_category')->where('id',$data['id'])->update(['name'=>$data['name'],'description'=>$data['description']]);
				$data=['code'=>'0','status'=>'ok', 'data'=>'修改成功'];
				echo json_encode($data);
				die;
	     	}
		    

	     }
	  }
	} 
