<?php
namespace app\admin\controller;
use Request;
use Db;

class Goodsp extends Common
{
 
    			public function list()
    			{
    				$goods_id=Request::get('id');
    				$this->assign('goods_id',$goods_id);
    				return $this->fetch();
    			}
            


            public function getcate()
            {
             $goods_id=Request::post('goods_id');
     
             $arr=Db::query("select attribute.id as a_id,attribute.name as a_name,goods_attr.attr_id as ga_attr_id,goods_attr.attr_details_id as ga_ad_id,attr_details.name as ad_name,attr_details.id as a_d_id from attribute join goods_attr on attribute.id=goods_attr.attr_id join attr_details on goods_attr.attr_details_id=attr_details.id where goods_attr.goods_id='$goods_id'");
             $newarr=[];
                 
                 foreach ($arr as $key => $value) {
                  $newarr[$value['a_name']][]=$value;
                 }
                  $json=['code'=>'0','status'=>'ok','data'=>$newarr];
                  return json($json);
            }


            public function addAction()
            {
            	$data=Request::post();
            	$goods_id=$data['goods_id'];
               // var_dump($goods_id);
               // die;
            	$price=$data['price'];
            	$n_number=$data['n_number'];
            	$goods_attr_id=$data['goods_attr_id'];
            	$goods_attr_id=implode("-", $goods_attr_id);
            	$arr=Db::query("select * from goodsp where goods_attr_id='$goods_attr_id'");
            	if (empty($arr)) {
            		$arr1=Db::query("insert into goodsp (`goods_id`,`price`,`n_number`,`goods_attr_id`) value ('$goods_id','$price','$n_number','$goods_attr_id')");
            		$json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
            		return json($json);
            	}else{
            		$json=['code'=>'1','status'=>'error','data'=>'货品名不能重复！'];
            		return json($json);
            	}


            }


            public function show()
            {   
            	$data=Request::post();
            	$goods_id=$data['goods_id'];
                $arr=Db::query("select * from goodsp where goods_id=$goods_id");
                for ($i=0; $i <count($arr) ; $i++) { 
                	$newarr=[];
                	$goods_attr_id=$arr[$i]['goods_attr_id'];
                	//获取表中goods—attr——id的值 
                	$all_goods_attr_id=explode("-", $goods_attr_id);
                	//拆分数组
                	for ($j=0; $j <count($all_goods_attr_id) ; $j++) { 
                	 	 $goods_attr_id=$all_goods_attr_id[$j];
                	 	 //字符串
                	 	 $arr1=Db::query("select * from attr_details where id='$goods_attr_id'");
                	 	 //查询属性细分的名称
                	 	
                	 	 $newarr[]=$arr1[0]['name'];
                	 	 
                	 }
                     $newarr=implode("-", $newarr);
                     $arr[$i]['name']=$newarr;

                }
                $json=['code'=>'0','status'=>'ok','data'=>$arr];
                return json($json);
               
            }




            public function delete()
            {
               $data=Request::post();
               $id=$data['id'];
               $arr=Db::query("delete from goodsp where id='$id'");
               $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
               return json($json);
            }

              public function updateAction()
            {
               $data=Request::post();
               $id=$data['id'];
               $price=$data['price'];
               $n_number=$data['n_number'];
               $arr=Db::query("update goodsp set  price='$price',n_number='$n_number' where id='$id'");
               $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
               return json($json);
            }

}