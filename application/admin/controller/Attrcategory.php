<?php
namespace app\admin\controller;
use Request;
use Db;

class Attrcategory extends Common
{
 
		    public function list()
		    {
		    	
		      return $this->fetch();
		    }

		       public function show()
		    {
		    	
	        $arr=Db::query("select * from attr_category");
	        $json=['code'=>'0','status'=>'ok','data'=>$arr];

	        return json($json);

		    }


		    public function addAction()
		    {
		    	$data=Request::post();
		    	$name=$data['name'];
		    	$arr=Db::query("select * from attr_category where name='$name'");
		    	if (empty($arr)) {
		    		$arr=Db::query("insert into attr_category (`name`) value ('$name')");
		    		$json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
		    		return json($json);
		    	}else{
		    		$json=['code'=>'1','status'=>'error','data'=>'属性分类名不能重复'];
		    		return json($json);
		    	}

		    }



		    public function delete()
		    {
		    	$data=Request::post();
		    	$id=$data['id'];
		        $ar=Db::query("select * from attribute where attrcate_id='$id'");
		        if (empty($ar)) {
		        $arr=Db::table('attr_category')->where('id',$id)->delete();
		    	$arr1=Db::table('attribute')->where('attrcate_id',$id)->delete();
		    	$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
		    	echo json_encode($arr);	
		        }else{
		        $arr=['code'=>'1','status'=>'error','data'=>'该分类还有属性无法删除'];
		    	echo json_encode($arr);		
		        }
		    	
		    }

		     public function updateAction()
		    {
		    	$data=Request::post();
		    	$id=$data['id'];
		    	$name=$data['name'];
		    	
		    	$arr=Db::query("select * from attr_category where name='$name'");
		    	if (empty($arr)) {
				        $arr=Db::query("update attr_category set name='$name' where id=$id");
				    	$json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
		    	        return json($json);
		    	}else{
		                $json=['code'=>'0','status'=>'error','data'=>'属性分类名不能重复'];
				    	return json($json);
		    	}
		    	

		    }

}