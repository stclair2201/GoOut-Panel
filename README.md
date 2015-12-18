## README
 GoOut是一款基于ThinkCMF开发的中文内容管理框架。
+
 GoOut配合GoOut-Shadowsocks-Manyuser后台代理服务器可以进行在线24小时自动支付，自动发货的代理销售平台。
+
 稳定，高效，及时。
+
 官网:http://www.goout.wang
 
 ## 使用说明
+
 本文件包，为开发版本，下载后需要自己配置数据文件
 
 # ~/data/conf/db.php
 
 <?php
+
 /**
+
  * 配置文件
+
  */
+
 return array(
+
     'DB_TYPE' => 'mysql',
+
     'DB_HOST' => '数据库地址',
+
     'DB_NAME' => '数据库名',
+
     'DB_USER' => '用户名',
+
     'DB_PWD' => '密码',
+
     'DB_PORT' => '3306',
+
     'DB_PREFIX' => 'go_',
+
     //密钥
+
     "AUTHCODE" => 'pas@#D',
+
     //cookies
+
     "COOKIE_PREFIX" => 'GoOut_',
+
 );
+
 ?>
 
+#数据库文件
+
+# ~/goout.sql
+
+默认用户名 admin
 
+密码：1234
 
 
 ## GoOut 免责声明
