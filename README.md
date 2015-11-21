## README
GoOut是一款基于ThinkCMF开发的中文内容管理框架。
GoOut配合GoOut-Shadowsocks-Manyuser后台代理服务器可以进行在线24小时自动支付，自动发货的代理销售平台。
稳定，高效，及时。
官网:http://www.goout.wang

## 使用说明
本文件包，为开发版本，下载后需要自己配置数据文件

# ~/data/conf/db.php

<?php
/**
 * 配置文件
 */
return array(
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '数据库地址',
    'DB_NAME' => '数据库名',
    'DB_USER' => '用户名',
    'DB_PWD' => '密码',
    'DB_PORT' => '3306',
    'DB_PREFIX' => 'go_',
    //密钥
    "AUTHCODE" => 'pas@#D',
    //cookies
    "COOKIE_PREFIX" => 'GoOut_',
);
?>




## GoOut 免责声明
  1、利用 GoOut 构建的网站的任何信息内容以及导致的任何版权纠纷和法律争议及后果，GoOut 官方不承担任何责任。
  
  2、您一旦安装使用GoOut，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。
 
## GoOut 使用建议

请在您的网站首页加上GoOut相关链接，O(∩_∩)O~ ！

  
  
GoOut 正在为你开放更多....
