<?php
class Control
{
	protected $cmd;
	protected $arg1;
	protected $arg2;
	protected $arg3;
	protected $arg4;
	protected $arg5;
	protected $arg6;
	protected $arg7;
	protected $arg8;
	protected $arg9;
	protected $arg10;
	protected $arg11;
	protected $apis;
	protected $error;
	
	const DOC_FILE		=	__DIR__. '/api_data.json';
	const DOC_NAME		=	'wx-fb-v1';
	const HELP_CMDS		=	['add','del','alter','move'];
	const HELPS			=	[
	'add'=>'php Control.php add 接口名 接口描述 请求方式 请求地址 请求头 请求头实例 请求体 请求体实例 响应注释 响应体实例',
	'del'=>'php Control.php del 接口下标',
	'alter'=>'php Control.php alter 接口下标 模块 模块值',
	'move'=>'php Control.php move 接口下标 目标下标',
	];
	public function __construct($args)
	{
		$this->cmd				=	isset($args[1]) ? $args[1] : '';
		$this->arg1				=	isset($args[2]) ? $args[2] : '';
		$this->arg2				=	isset($args[3]) ? $args[3] : '';
		$this->arg3				=	isset($args[4]) ? $args[4] : '';
		$this->arg4				=	isset($args[5]) ? $args[5] : '';
		$this->arg5				=	isset($args[6]) ? $args[6] : '';
		$this->arg6				=	isset($args[7]) ? $args[7] : '';
		$this->arg7				=	isset($args[8]) ? $args[8] : '';
		$this->arg8				=	isset($args[9]) ? $args[9] : '';
		$this->arg9				=	isset($args[10]) ? $args[10] : '';
		$this->arg10			=	isset($args[11]) ? $args[11] : '';
	}
	
	protected function validate()
	{
		switch($this->cmd)
		{
			case 'add':
					return $this->validateWhenAdd();	
					break;
			case 'del':
					return $this->validateWhenDel();
					break;
			case 'alter':
					return $this->validateWhenAlter();
					break;
			case 'move':
					return $this->validateWhenMove();
					break;
			case 'help':
					return $this->validateWhenHelp();
					break;
			default:
					$this->error='无此命令';
					return false;
					break;
		}
	}
	
	protected function validateWhenAdd()
	{
		if(!$this->arg1 || !$this->arg2 || !$this->arg3 || !$this->arg4)
		{
			$this->error='参数错误';
			return false;
		}
		$headerParams	=	json_decode($this->arg5);
		$requestParams	=	json_decode($this->arg7);
		$reponseParams	=	json_decode($this->arg9);
		if(is_array($headerParams) && !empty($headerParams))
		{
			if(!$this->validateParm($headerParams))
			{
				$this->error='参数错误';
				return false;
			}
		}
		if(is_array($requestParams) && !empty($requestParams))
		{
			if(!$this->validateParm($requestParams))
			{
				$this->error='参数错误';
				return false;
			}
		}
		if(is_array($reponseParams) && !empty($reponseParams))
		{
			if(!$this->validateParm($reponseParams))
			{
				$this->error='参数错误';
				return false;
			}
		}
		return true;
	}
	
	protected function validateParm($params)
	{
		foreach($params as $param)
		{
			if(!isset($param->desc)|| !isset($param->type) || !isset($param->key))
			{
				return false;
			}
		}
		return true;
	}
	
	protected function add()
	{
		$this->arg5	=	json_decode($this->arg5) ? json_decode($this->arg5) : [];
		$this->arg6	=	json_decode($this->arg6) ? json_decode($this->arg6) : [];
		$this->arg7	=	json_decode($this->arg7) ? json_decode($this->arg7) : [];
		$this->arg8	=	json_decode($this->arg8) ? json_decode($this->arg8) : [];
		$this->arg9	=	json_decode($this->arg9) ? json_decode($this->arg9) : [];
		$this->arg10=	json_decode($this->arg10) ? json_decode($this->arg10) : [];
		$this->apis	=	self::readFile();
		$this->apis[]=[
			'api_name'			=>	$this->arg1,
			'api_desc'			=>	$this->arg2,
			'req_type'			=>	$this->arg3,
			'req_url'			=>	$this->arg4,
			'hea_params'		=>	$this->arg5,
			'hea_sample'		=>	$this->arg6,
			'req_params'		=>	$this->arg7,
			'req_sample'		=>	$this->arg8,
			'res_annotation'	=>	$this->arg9,
			'res_sample'		=>	$this->arg10,
		];
		self::writeFile($this->apis);
	}
	
	protected function validateWhenDel()
	{
		$this->apis	=	self::readFile();
		if(!isset($this->apis[$this->arg1]))
		{
			$this->error='无此接口';
			return false;
		}
		return true;
	}
	
	protected function del()
	{
		unset($this->apis[$this->arg1]);
		$this->apis=array_values($this->apis);
		self::writeFile($this->apis);
	}
	
	protected function validateWhenAlter()
	{	
		$this->apis	=	self::readFile();
		$module		=	$this->arg2;
		if(!isset($this->apis[$this->arg1]))
		{
			$this->error='无此接口';
			return false;
		}
		if(!isset($this->apis[$this->arg1][$module]))
		{
			$this->error='无此模块';
			return false;
		}
		if(in_array($module,['req_sample','hea_sample','res_sample']))
		{
			$this->arg3=json_decode($this->arg3);
			if(!$this->arg3)
			{
				$this->error='参数错误';
				return false;
			}
			return true;
		}
		if(in_array($module,['hea_params','req_params','res_annotation']))
		{
			$this->arg3	=	json_decode($this->arg3);
			if(!is_array($this->arg3))
			{
				$this->error='参数错误';
				return false;
			}
			if(empty($this->arg3))
			{
				return true;
			}
			if(!$this->validateParm($this->arg3))
			{
				$this->error='参数错误';
				return false;
			}
			return true;
		}
		return true;
	}
	
	protected function alter()
	{
		$module								=	$this->arg2;
		$this->apis[$this->arg1][$module]	=	$this->arg3;
		self::writeFile($this->apis);
	}
	
	protected function validateWhenMove()
	{
		$this->apis		=	self::readFile();
		if(!isset($this->apis[$this->arg1]) || !isset($this->apis[$this->arg2]))
		{
			$this->error='无此接口';
			return false;
		}
		return true;
	}
	
	protected function move()
	{
		$tmp						=	$this->apis[$this->arg1];
		$this->apis[$this->arg1]	=	$this->apis[$this->arg2];
		$this->apis[$this->arg2]	=	$tmp;
		self::writeFile($this->apis);
	}
	
	protected function validateWhenHelp()
	{
		if(!in_array($this->arg1,self::HELP_CMDS,true))
		{
			$this->error='帮助文档中无此命令的信息';
			return false;
		}
		return true;
	}
	
	protected function help()
	{
		return self::HELPS[$this->arg1];
	}
	
	public function make()
	{
		if(!$this->validate())
		{
			die($this->error);
		}
		die(call_user_func([$this,$this->cmd],[]));
	}
	
	protected static function readFile()
	{
		if(!is_file(self::DOC_FILE))
		{
			self::writeFile([]);
		}
		$fileContent			=	file_get_contents(self::DOC_FILE);
		$jsonContent			=	substr($fileContent,14);
		$doc					=	json_decode($jsonContent,true);
		$apis					=	$doc['apis'];
		return $apis;
	}
	
	protected static function writeFile($apis)
	{
		$apis=array_map(function($v){
			$v['hea_sample']	=	empty($v['hea_sample']) ? new \stdClass() : $v['hea_sample'];
			$v['req_sample']	=	empty($v['req_sample']) ? new \stdClass() : $v['req_sample'];
			$v['res_sample']	=	empty($v['res_sample']) ? new \stdClass() : $v['res_sample'];
			return $v;
		},$apis);
		$fileContent	=	json_encode(['doc_name'=>self::DOC_NAME,'apis'=>$apis]);
		file_put_contents(self::DOC_FILE,'var api_lists='.$fileContent);
	}
}
(new Control($argv))->make();
// Example
/*
ADD
	php Control.php add 登录 登录接口 POST http://w.com/v1/user/wnp '[{"key":"Cookie","type":"string","desc":"Cookie"}]' '{"Cookie":"dsadsadygqwjhsadsno"}'  '[{"key":"u","type":"string","desc":"xxxxx"}]' '{"u":"sdsadsadsadwjfsdbhau"}'  '[{"key":"uid","type":"string","desc":"UID"}]' '{"uid":"dsdsadsadsaasdsa","cc":"dsdsadsa"}'
DEL
	php Control.php del 0
ALTER
	php Control.php alter 0 req_type DELETE
MOVE
	php Control.php move 0 7
HELP
	php Control.php help move
*/ 


