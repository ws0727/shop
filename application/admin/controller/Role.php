<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class Role extends Common
	{
	    public function list()
	    {


	      return $this->fetch();
	    }


	      public function add()
	    {


	      return $this->fetch();
	    }
        

            public function updateAction()
	    {
          
	       $data=Request::post();
	       $validate = new \app\admin\validate\Role;
            if (!$validate->check($data)) {
           $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
        }
            unset($data['__token__']);
		     $rbac=new Rbac();	
		     $name=$data['name'];
		     $id=$data['id'];
		     $permission_id=$data['permission_id'];
		     $up_data=$data;
		     unset($up_data['permission_id']);
		     $arr=Db::query("select *from role where name='$name'");
		      if (empty($arr)||!empty($arr)&&$arr[0]['id']==$data['id']) {
		      	$arr=Db::table('role')->update($up_data);
		      	$arr=Db::query("delete from role_permission where role_id='$id'");
                $pid_arr=explode(',', $permission_id);
		      	array_shift($pid_arr);
		      	
	      		foreach ($pid_arr as $key => $value) {
	      		$arr=Db::query("insert into role_permission (`role_id`,`permission_id`) values('$id','$value')");
	      		}
               $arr=['code'=>1,'status'=>'ok','data'=>'修改成功'];
		      	echo json_encode($arr);
 	      	    // 删除 重新入库      	
	      }else{
	      	$arr=['code'=>3,'status'=>'error','data'=>'修改失败重名'];
	      	echo json_encode($arr);
	      	 }
	     
	    }
	         

        public function show()
	    {
	    
	     $rbac= new Rbac();
         $arr=Db::query("select id,name,description from role");
          echo  json_encode($arr);
	    }


	     public function permissionshow()
        {
    
			$rbac= new Rbac();
			$arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");
      
            $newarr=[];
            foreach ($arr as $key => $value) {
            	$newarr[$value['p_c_name']][]=$value;
            }
             $json=['code'=>'0','status'=>'ok','data'=>$newarr];
             return json($json);
         }


           public function mypermissionshow()
       {
         $id=Request::get('id');
	     $rbac= new Rbac();
         $arr=Db::query("select permission_id from role_permission where role_id='$id'");
         $json=['code'=>'0','status'=>'ok','data'=>$arr];
         return json($json);
         }


	    
	      public function delete()
	    {

        $data=Request::post();
	       $validate = new \app\admin\validate\Delete;
            if (!$validate->check($data)) {
           $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);	
	    }
	    $id=Request::post('id');
	    $rbac= new Rbac();
	    $rbac->delRole($id);
	    $arr=['code'=>0,'status'=>'ok','data'=>'删除成功'];
	    echo json_encode($arr);
		  
       }


          public function deletemore()
	    {

       $data=Request::post();
       $id=Request::post('id');
	       $validate = new \app\admin\validate\Delete;
            if (!$validate->check($data)) {
           $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);	
	    }
	    if (empty($id)) {
	    	$arr=['code'=>1,'status'=>'error','data'=>'未选择删除对象'];
	    	echo json_encode($arr);
	    }
	    $id=explode(',', $id);
	    array_shift($id);
	    $rbac= new Rbac();
	    $rbac->delRole($id);
	    $arr=['code'=>0,'status'=>'ok','data'=>'删除成功'];
	    echo json_encode($arr);
		  
       }

        public function addAction()
	    {
 
        $data=Request::post();
		$validate = new \app\admin\validate\Role;//验证器
		      if (!$validate->check($data)) {//
		$data=['code'=>'2','status'=>'error', 'data'=>$validate->getError()];
		echo $json=json_encode($data);
		die;
        }
          
	     $rbac=new Rbac();
	     $name=$data['name'];
	     $description=$data['description'];
	     $permission_id=$data['permission_id'];
	     $getname=Db::query("select * from role where name='$name'");
	     $arr=json_decode($permission_id,true);
	     $arr=explode(',', $permission_id);
	     array_shift($arr);
	     $permission_id=implode(',', $arr);
	     if (empty($getname)) {
	     	$rbac->createRole([
			    'name' => $name,
			    'description' => $description,
			    'status' => 1
			],$permission_id);
	       $arr=['code'=>1,'status'=>'ok','data'=>'添加成功'];
	       echo json_encode($arr);
	     }else{
             $arr=['code'=>0,'status'=>'error','data'=>'名字不能重复'];
             echo json_encode($arr);
	     }
		    }

    }