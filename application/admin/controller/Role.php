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
	 
	         
        public function show()
	    {
	    
	     $rbac= new Rbac();
         $arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");
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
	    
   }
