<?php
namespace app\admin\controller;
use Request;
use Db;

class Attribute extends Common
{
 
		    public function list()
		    {
		      $attrcate_id=Request::get('id');
		      $this->assign('attrcate_id',$attrcate_id);
		      return $this->fetch();
		    }

		     public function show()
		    {
		     $attrcate_id=Request::post('attrcate_id');
		     $arr=Db::query("select attribute.id,attribute.name,attribute.attrcate_id,attr_category.name as a_c_name from attribute join attr_category on attribute.attrcate_id=attr_category.id where attribute.attrcate_id='$attrcate_id'");
		       $json=['code'=>'0','status'=>'ok','data'=>$arr];
				return json($json);
		    }
          
		   public function addAction()
		   {
		   	 $data=Request::post();
		   	 $name=$data['name'];
		   	 $attrcate_id=$data['attrcate_id'];
		   	 $arr=Db::query("select * from attribute where name='$name' and attrcate_id='$attrcate_id'");
		   	 if (empty($arr)) {
		   	 	$arr=Db::query("insert into attribute (`name`,`attrcate_id`) values ('$name', '$attrcate_id')");
		   	 	$json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
		   	 	return json($json);
		   	 }else{
		   	 	$json=['code'=>'1','status'=>'error','data'=>'属性名不能重复'];
		   	 	return json($json);
		   	 }
		   }

		    public function delete()
		   {
		   	 $data=Request::post();
		   	 $id=$data['id'];
		   	 $ar=Db::query("select *from attr_details where attr_id='$id'");
		   	 if (empty($ar)) {
		   	 $arr=Db::table('attribute')->where('id',$id)->delete();
		   	 $arr=Db::table('attr_details')->where('attr_id',$id)->delete();
		   	 $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
		   	 return json($json);
		   	 }else{
		   	  $json=['code'=>'0','status'=>'error','data'=>'该属性有属性细分无法删除'];
		   	  return json($json);
		   	 }
		   	
		   }
           
           
           public function updateAction()
           {
           	$data=Request::post();
           	$id=$data['id'];
           	$name=$data['name'];
            $attrcate_id=$data['attrcate_id'];
             $arr=Db::query("select * from attribute where name='$name' and attrcate_id='$attrcate_id'");
            if (empty($arr)) {
            $arr=Db::query("update attribute set name='$name' where id='$id'");
           	$json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
           	return json($json);
            }else{
            $json=['code'=>'1','status'=>'error','data'=>'属性名不能重复'];
           	return json($json);
            }
           
           
           }

}