<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table webenquiry(sno int not null auto_increment, name varchar(100), email varchar(100), subject varchar(150), message varchar(1000), post_date date, website_id varchar(100),   primary key(sno));
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 