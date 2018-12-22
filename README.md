# Offline-Api-Files
Offline Api Files
### add an api
```
php add api_name api_intro api_method api_url request_header_params request_header_example request_body_params request_body_example response_annotation response_example
the format of request_header_params request_body_params, response_annotation value is:
[
	{"key":"param name","type":"param type","desc":"param intro"}
]
or 
[
]
request_header_example request_body_example, response_example is the json format or ''
```
### del an api
```
php Control.php del index
```
### alter an api
```
php Control.php alter index module value
```
### move an api
```
php Control.php move index index
```
### help
```
php Control.php help [add|del|alter|move]
```

### 中文
### 添加一个接口
```
php Control.php add 接口名 接口描述 请求方式 请求地址 请求头 请求头实例 请求体 请求体实例 响应注释 响应体实例
```
### 删除一个接口
```
php Control.php del 接口下标
```
### 修改一个接口
```
php Control.php alter 接口下标 模块 模块值
```
### 接口移动
```
php Control.php move 接口下标 目标下标
```
### 获取帮助
```
php Control.php help 操作
```
### UI
![image](https://github.com/AaronAndreas/Offline-Api-Files/ui.png)
