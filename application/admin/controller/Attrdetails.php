<?php
namespace app\admin\controller;
use Request;
use Db;

class Attrdetails extends Common
{
 
		    public function list()
		    {
		    	$attr_id=Request::get('id');
		    	$this->assign('attr_id',$attr_id);
		        return $this->fetch();
		    }
             
             public function show()
		    {
			$attr_id=Request::post('attr_id');
			$arr=Db::query("select attr_details.id,attr_details.attr_id,attribute.name as aname,attr_details.name from attr_details join attribute on attr_details.attr_id=attribute.id where attr_details.attr_id='$attr_id'");
			$json=['code'=>'0','status'=>'ok','data'=>$arr];
			return json($json);

		    }

		    public function addAction()
		    {
		    $data=Request::post();
		    $attr_id=$data['attr_id'];
		    $name=$data['name'];
		    $arr=Db::query("select * from attr_details where name='$name'and attr_id='$attr_id'");
		    if (empty($arr)) {
		    	 $arr=Db::query("insert into attr_details (`name`,`attr_id`) value ('$name',$attr_id)");
		         $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
		         return json($json);
		    }else{
                $json=['code'=>'0','status'=>'error','data'=>'属性细分不能重复'];
		          return json($json);
		    }
		   
		    }
            
            public function delete()
            {
            $data=Request::post();
            $id=$data['id'];
            $arr=Db::table('attr_details')->where('id',$id)->delete();
            $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
		    return json($json);

            }
           

           public function updateAction()
           {
           	$data=Request::post();
           	$id=$data['id'];
           	$name=$data['name'];
           	$attr_id=$data['attr_id'];
            $arr=Db::query("select * from attr_details where name='$name' and attr_id='$attr_id'");
            if (empty($arr)) {
                    $arr=Db::query("update attr_details set name='$name' where id=$id");
                 	$json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
           	         return json($json);
            }else{
              	$json=['code'=>'0','status'=>'error','data'=>'属性细分不能重名'];
           	    return json($json);
            }
          
           
           }


}