<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;

class Permission extends Common
	{
	    public function list()
	    {
	     $rbac=new Rbac();
	     $getarr=$rbac->getPermissionCategory([]);
	      $this->assign('cate',$getarr);
	      return $this->fetch();
	    }
        
         public function addAction()
	    {
	     $rbac=new Rbac();
	     $name=Request::post('name');
	     $cateid=Request::post('cateid');
	     $rbac->createPermission([
	    'name' => $name,
	    'category_id' =>$cateid,
    	 ]);
	     $arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
	     echo json_encode($arr);
	    
	    }


        public function show()
	    {
	    	
	     
	    }


	  
	    
   }