<?php
$dbtemplate = array("banned_users" => array("id" => array("id","int(11)","NO","PRI","","auto_increment"),"user" => array("user","varchar(255)","NO","UNI","",""),"date" => array("date","date","NO","","","")),"categories" => array("id" => array("id","int(11)","NO","PRI","","auto_increment"),"name" => array("name","varchar(255)","NO","","New Category","")),"coupons" => array("id" => array("id","int(11)","NO","PRI","","auto_increment"),"code" => array("code","varchar(50)","NO","","",""),"discount" => array("discount","varchar(10)","NO","","10%",""),"uses" => array("uses","int(10)","YES","","","")),"database_info" => array("version" => array("version","float","NO","PRI","","")),"items" => array("id" => array("id","int(11)","NO","PRI","",""),"name" => array("name","text","NO","","",""),"imageurl" => array("imageurl","text","NO","","",""),"description" => array("description","text","NO","","",""),"category" => array("category","int(3)","NO","","",""),"price" => array("price","float(11)","NO","","",""),"featured" => array("featured","tinyint(1)","NO","","0",""),"onetime" => array("onetime","tinyint(1)","NO","","0",""),"command" => array("command","text","NO","","",""),"servers" => array("servers","text","NO","","","")),"logins" => array("id" => array("id","int(11)","NO","PRI","","auto_increment"),"hash" => array("hash","varchar(255)","NO","","",""),"ip" => array("ip","varchar(255)","NO","","",""),"expire" => array("expire","datetime(6)","NO","","","")),"servers" => array("id" => array("id","int(11)","NO","PRI","","auto_increment"),"name" => array("name","varchar(255)","NO","","",""),"ip" => array("ip","varchar(255)","NO","","",""),"pass" => array("pass","varchar(255)","NO","","","")),"transactions" => array("id" => array("id","int(11)","NO","PRI","","auto_increment"),"item" => array("item","text","NO","","",""),"user" => array("user","text","NO","","",""),"ip" => array("ip","varchar(255)","YES","","",""),"transaction_id" => array("transaction_id","varchar(255)","YES","","",""),"payer_id" => array("payer_id","varchar(255)","YES","","",""),"method" => array("method","text","NO","","",""),"date" => array("date","date","NO","","",""),"amount" => array("amount","int(11)","NO","","1",""),"price" => array("price","float","NO","","1337","")));
$dbtemplate_table_names = array("banned_users","categories","coupons","database_info","items","logins","servers","transactions");
$dbtemplate_json_columns = array("item.command","item.servers");