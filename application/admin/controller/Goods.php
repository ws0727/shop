<?php
namespace app\admin\controller;
use Request;
use Db;
class Goods extends Common
{    
            public function list()
            {     
                return $this->fetch();  
            }


            public function add()
            {     
                return $this->fetch();  
            }


               public function brandshow()
            {
                $arr=Db::query("select * from brand");
                $json=['code'=>'0','status'=>'ok','data'=>$arr];
                echo json_encode($arr);
            }


            public function show()
            {
                $arr=Db::query("select goods.product_id,goods.product_name,brand.brand_id,brand.brand_name,goods_category.id,goods_category.name,goods.desction,goods.price,goods.status from goods join brand on goods.brand_id=brand.brand_id join goods_category on goods_category.id=goods.cate_id order by goods.product_id");
                $json=['code'=>'0','status'=>'ok','data'=>$arr];
                echo json_encode($json);
            }



            public function cateshow ()
            {
                $arr=Db::query("select * from goods_category");
                $this->getTree($arr);
            }

            private function getTree($array, $pid =0, $level = 0){
              foreach ($array as $key => $value){
                //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
                if ($value['pid'] == $pid){
                    //父节点为根节点的节点,级别为0，也就是第一级
                    $flg = str_repeat('|--',$level);
                    $value['name'] = $flg.$value['name'];
                    $name=$value['name'];
                    $id=$value['id'];
                    // 输出 名称
                    echo "<option value='$id'>$name</option>";
                    //把数组放到list中
                    $list[] = $value;
                    //把这个节点从数组中移除,减少后续递归消耗
                    unset($array[$key]);
                    //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                   $this->getTree($array,$value['id'],$level+1);
                }  
              }
           }
     
           

            public function addAction()
            {   
            $data=Request::post();
            $data = ['product_name' =>$data['name'],'brand_id' =>$data['brand_id'],'cate_id'=>$data['cate_id'],'desction'=>$data['desction'],'price'=>$data['price'],'status'=>$data['status']];
            $arr=Db::name('goods')->insert($data);
            $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
            return json($json);
             }
            

             public function delete()
            {
            $data=Request::post();
            $id=$data['id'];
            $arr=Db::query("select * from goods_img where goods_id='$id'");
            Db::query("delete from goods where product_id='$id'");
            if ($arr) {
                foreach ($arr as $key => $value) {
                    unlink("./uploads/goodsimg/".$value['big_img']);
                    unlink("./uploads/goodsimg/".$value['middle_img']);
                    unlink("./uploads/goodsimg/".$value['small_img']);
                    unlink("./uploads/goodsimg/".$value['origin']);
            Db::query("delete from goods_img where goods_id='$goods_id'");
            }
          }
           $arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
           echo json_encode($arr);
      }
}