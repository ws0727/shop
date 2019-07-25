<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
use Cache;
class Goodscate extends Common
{
 
	    public function list()
	    {
	      return $this->fetch();
	    }

	   

	    private function getTree($array, $pid =0, $level = 0){
            //声明静态数组,避免递归调用时,多次声明导致数组覆盖
			    static $list = [];

                echo "<ul class='qq'>";
			    foreach ($array as $key => $value){
			    	
			        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
			        if ($value['pid'] == $pid){
			            //父节点为根节点的节点,级别为0，也就是第一级
			            //$flg = str_repeat('|--',$level);
			            // 更新 名称值
			            $value['name'] = $value['name'];
			            $m_id=$value['id'];
			            // 输出 名称
			            echo "<li class='ws' value='$m_id' id='id".$m_id."'>".$value['name']."&nbsp;&nbsp;<a onclick=modaldemo1(\"".$m_id."\",\"".$value['name']."\") class='Hui-iconfont'>&#xe6df;</a>&nbsp;&nbsp;&nbsp;<a onclick='modaldemo(".$m_id.")' class='Hui-iconfont'>×</a></li>";
			            //把数组放到list中
			            $list[] = $value;
			            //把这个节点从数组中移除,减少后续递归消耗
			            unset($array[$key]);
			            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
			            $this->getTree($array, $value['id'], $level+1);
			        }
			        
			    }
			    echo "</ul>";
			    return $list;
}




		    public function show()
		    {   

		    	$arr=Cache::get('name');
		    	if (!$arr) {
		    	 $arr=Db::query("select * from goods_category");
		    	 Cache::set('name',$arr,3600);
		    	}
		    	
		    	$this->getTree($arr);
		    	
		    }

		      public function addAction()
		    {   
		        $data=Request::post();
		       $validate = new \app\admin\validate\Goodscate;//验证器
	            if (!$validate->check($data)) {//
	           $data=['code'=>'2','status'=>'error', 'data'=>$validate->getError()];
				echo $json=json_encode($data);
				die;
	          }

		      $pid=Request::post('pid');
		      $name=Request::post('name');
		      $arr=Db::query("select * from goods_category where name='$name'");
		      if (!empty($arr)) {
		      	$json=['code'=>'1','status'=>'error','data'=>'分类不能重复'];
		      	return json($json);   	
		    }else{
		    	// Db::query("insert into goods_category (`name`,`pid`) value('$name','$pid')");
		    	$data=['name'=>$name,'pid'=>$pid];
		    	$id=Db::name('goods_category')->insertGetId($data);
		    	$json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
		    	return json($json);	
		    }
		}


        
		     public function updateAction()
		    {   
		    	$data=Request::post();
		    	$name=$data['name'];
		    	// var_dump($name);
		    	// die;
		    	$arr=Db::query("select * from goods_category where name='$name'");
		    	if (empty($arr)) {
                    $where=['name'=>$data['name']];
		    	    $arr=Db::name('goods_category')->where('id',$data['id'])->update($where);
		    		$json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
		    		return json($json);
		    	}else{
		    		$json=['code'=>'1','status'=>'error','data'=>'产品类型不能重复'];
		    		return json($json);
		    	}
		    	
		    	
		    }



		        public function delete()
		    {   
		        $data=Request::post();
		        $id=$data['id'];
		    	$arr1=Db::query("select * from goods_category where id='$id'");
		    	Db::query("delete from goods_category where id='$id'");
		        $this->del($id);
		        $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
		        echo json_encode($json);   
		    }

		    function del($id){
		        $arr=Db::query("select * from goods_category where pid='$id'");
		        if (!empty($arr)) {
		            foreach ($arr as $key => $value) {
		                $id1=$value['id'];
		                $arr=Db::query("delete from goods_category where id=$id1");
		                $this->del($value['id']);
		               
		            }
		        }
		    }


}



			