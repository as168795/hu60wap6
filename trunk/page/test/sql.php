<?php
$tpl=$PAGE->start();
$sql=trim(str_replace(array(chr(12),"\xC2\xA0"),array("\n",' '),$_POST['sql']));
if($sql != '') {
$db2=$db=db::conn(trim($_POST['dbname']));
$db=$db->query($sql);
if(!$db) {
$tpl->assign('msg',$db2->errorinfo());
$db=array();
$ok=false;
  } else {
$ok=true;
$db=$db->fetchall(db::ass);
  }
$tpl->assign('sql',$sql);
$tpl->assign('db',$db);
$tpl->assign('ok',$ok);
 } else {
$tpl->assign('sql','');
$tpl->assign('db',array());
$tpl->assign('ok',null);
 }
$tpl->assign('showdbname',DB_TYPE=='sqlite');
$tpl->display('tpl:sql');