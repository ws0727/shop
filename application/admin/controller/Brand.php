<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class Brand extends Common
{
    

	     public function add()
	    {
	      return $this->fetch();
	    }

	    public function list()
	    {
	      return $this->fetch();
	    }

	     public function addAction()
	    {
           $data=Request::post();
	       $validate = new \app\admin\validate\Brand;//验证器
            if (!$validate->check($data)) {//
           $data=['code'=>'2','status'=>'error', 'data'=>$validate->getError()];
			echo $json=json_encode($data);
			die;
          }
            $name=Request::post('name');
			$site_url=Request::post('site_url');
			$desc=Request::post('desc');
			$status=Request::post('status');
		    $arr=Db::query("select brand_id from brand where brand_name='$name'");
		    if (!empty($arr)) {
		        $json=['code'=>2,'status'=>'error','data'=>'名字不能重复']	;
               return json($json);
		    }
		      $brand_logo=request()->file('brand_logo');
	        if ($brand_logo) {
	            $info = $brand_logo->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
			    if($info){
			       $path=str_replace("\\", "/",$info->getSaveName());
			        Db::query("insert into brand (`brand_name`,`site_url`,`brand_desc`,`status`,`brand_logo`) values ('$name','$site_url','$desc','$status','$path')");
			         $json=['code'=>0,'status'=>'ok','data'=>'添加成功'];
                     return json($json);   
			    }else{
			        echo $file->getError();
			    }

            }else{
               $json=['code'=>1,'status'=>'error','data'=>'文件不能为空']	;
               return json($json);
            }
	     
	    }

	    public function show ()
	    {
	     $arr=Db::query("select * from brand");
	     $arr=['code'=>0,'status'=>'ok','data'=>$arr];
	     echo json_encode($arr);
	    }

	     public function updateAction ()
	    {
	     $data=Request::post();
	     $name=$data['name'];
	     $site_url=$data['site_url'];
	     $desc=$data['desc'];
	     $logo=$data['logo'];
	     $arr=Db::query("select * from brand where brand_name='$name' or brand_desc='$desc' or site_url='$site_url'");
	     if (empty($arr)) {
	     	$arr=['brand_name'=>$name,'brand_desc'=>$desc,'site_url'=>$site_url,'brand_logo'=>$logo];
	     	$arr=Db::table('brand')->where('brand_id',$data['id'])->update($arr);

	     	$arr=['code'=>0,'status'=>'ok','data'=>'修改成功'];
	     	echo json_encode($arr);
	     }else{
	     	foreach ($arr as $key => $value) {
	     		if ($value['brand_id']!=$data['id']) {
	     		 $arr=['code'=>1,'status'=>'error','data'=>'不能重复'];
	     		 echo json_encode($arr);
	     		 die;
	     		 }
	     	}
	      	$arr=Db::table('brand')->where('brand_id',$data['id'])->update($arr);
	      	$arr=['code'=>0,'status'=>'ok','data'=>'修改成功'];
	      	echo json_encode($arr);
	      	die;
	     }
    }


		    public function delete()
		    {
		     $data=Request::post();
		     $id=$data['id'];
		     $brand_logo=$data['logo'];   
		     unlink("./uploads/". $brand_logo);
		     $arr=Db::table('brand')->where('brand_id',$id)->delete();
		     $arr=['code'=>0,'status'=>'ok','data'=>'删除成功'];
		     echo json_encode($arr);
		     die;
		    }


		    public function deletemore()
		    {
		    	$data=Request::post();
		    	$id=$data['id'];
		    	if (empty($id)) {
		    		$arr=['code'=>1,'status'=>'error','data'=>'未选择删除对象'];
		    		echo json_encode($arr);
		    		die;
		    	}
		    	$id=explode(",", $id);
		    	array_shift($id);
		    	$arr=Db::table('brand')->where('brand_id','in',$id)->delete();
		    	$arr=['code'=>0,'status'=>'ok','data'=>'删除成功'];
		    	echo json_encode($arr);
		    	die;
		    }

             
                      public function imgupdate()
		       {
	         
	             $brand_logo=request()->file('brand_logo');
	             $data=Request::post();
	             $brand_id=$data['brand_id'];
	             $old_logo=$data['old_logo'];
                 $info = $brand_logo->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
				 if($info){
				       $logo_path=str_replace("\\", "/",$info->getSaveName());
				       $arr=['brand_logo'=>$logo_path];
                       Db::name('brand')->where('brand_id',$brand_id)->update($arr);
                       unlink(".".$old_logo);
				       $arr1=['code'=>0,'status'=>'ok','data'=>'添加成功'];
	                    echo  json_encode($arr1);
	                    die;

	            }else{
	               $arr1=['code'=>1,'status'=>'error','data'=>'请选择正确文件格式'];
	               echo  json_encode($arr1);
	               die;
	           
		    }
		}


    }
        
