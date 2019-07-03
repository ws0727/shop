<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class Permission extends Common
	{
	    public function list()
	    {
	      return $this->fetch();
	    }
        

         public function addAction()
	    {
         
           $data=Request::post();//接值主要（data传过来的值）
	       $validate = new \app\admin\validate\Permission;//验证器
            if (!$validate->check($data)) {//
          $data=['code'=>'2','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
	     $rbac=new Rbac();
	     $getname=$rbac->getPermission([['name', '=', $data['name']]]);
	     $getpath=$rbac->getPermission([['path', '=', $data['path']]]);
	     if (empty($getname)&&empty($getpath)) {
	     	 $rbac->createPermission([
			    'name' => $data['name'],
			    'description' => $data['description'],
			    'status' => 1,
			    'type' => 1,
			    'category_id' => $data['category_id'],
			    'path' =>  $data['path'],
			]);
	       $arr=['code'=>1,'status'=>'ok','data'=>'添加成功'];
	       echo json_encode($arr);
	     }else{
             $arr=['code'=>0,'status'=>'error','data'=>'名字或者路径不存在'];
             echo json_encode($arr);
	     }
	    
	  }

        public function show()
	    {
	    
	     $rbac= new Rbac();
         $arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");
          echo  json_encode($arr);
	    }

         public function delete()
	    {
          
	       $data=Request::post();
	     $validate = new \app\admin\validate1\Permission;
            if (!$validate->check($data)) {
          $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
	      $id=Request::post('id');
	      $rbac=new Rbac();	
	      $rbac->delPermission($id);
	      $data=['code'=>'0','status'=>'ok', 'data'=>'删除成功'];
	      echo json_encode($data);
	      die;
	    }


	      public function updateAction()
	    {
          
	       $data=Request::post();
	       $validate = new \app\admin\validate\Permission;
            if (!$validate->check($data)) {
           $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
         unset($data['__token__']);
	     $rbac=new Rbac();	
	     $name=$data['name'];
	     $path=$data['path'];
	     $arr=Db::query("select *from permission where name='$name' or path='$path'");
	      if (empty($arr)) {
	      	$arr=Db::table('permission')->update($data);
	      	$arr=['code'=>0,'status'=>'ok','data'=>'修改成功'];
	      	echo json_encode($arr);
	      }else{
	      	 foreach ($arr as $key => $value) {
	      	 	if ($value['id']!=$data['id']) {
	      	 	$arr=['code'=>1,'status'=>'error','data'=>'name或path已经存在'];
	      	    echo json_encode($arr);
	      	    die;
	      	 	}
	      	 }
	      	$arr=Db::table('permission')->update($data);
	      	$arr=['code'=>0,'status'=>'ok','data'=>'修改成功'];
	      	echo json_encode($arr);
	      }
	    }
	    //什么时候可以修改内容
	    //1.取值name or path
	    //2.if（值为空） {直接修改}
	    //3.if(不是空){
	    //    if (自己的id) {
	    // 	     可以修改
	    //    }else{ 
	    //       name 或者path已经存在
	    //      }
	    // }
	   

	   public function deletemore()
	    {
	     $data=Request::post();
	     $validate = new \app\admin\validate1\Permission;
            if (!$validate->check($data)) {
          $arr=['code'=>1,'status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($arr);
			die;
        }
         $id=Request::post('id');
         if (empty($id)) {
         	$arr=['code'=>2,'status'=>'error','data'=>'未选择删除对象'];
         	echo json_encode($arr);
         	die;
         }

         $id=explode(",", $id);
         array_shift($id);
         $rbac = new Rbac();
         $rbac->delPermission($id);
         $arr=['code'=>0,'status'=>'ok','data'=>'删除成功'];
         echo json_encode($arr);
	    }
	    
   }
