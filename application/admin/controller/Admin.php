<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class admin extends Common
	{
	    public function list()
	    {
	      return $this->fetch();
	    }

	     public function add()
	    {
	    	
	      return $this->fetch();
	    }
        
       
   }
