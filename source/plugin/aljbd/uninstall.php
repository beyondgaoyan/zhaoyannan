<?php
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//start to put your own code 
$sql = <<<EOF
DROP TABLE IF  EXISTS `pre_aljbd`;
DROP TABLE IF  EXISTS `pre_aljbd_comment`;
DROP TABLE IF  EXISTS `pre_aljbd_point`;
DROP TABLE IF  EXISTS `pre_aljbd_region`;
DROP TABLE IF  EXISTS `pre_aljbd_type`;
DROP TABLE IF  EXISTS `pre_aljbd_username`;
DROP TABLE IF  EXISTS `pre_aljbd_winfo`;
DROP TABLE IF  EXISTS `pre_aljbd_syscache`;
DROP TABLE IF  EXISTS `pre_aljbd_comment_notice`;
DROP TABLE IF  EXISTS `pre_aljbd_notice`;
DROP TABLE IF  EXISTS `pre_aljbd_album`;
DROP TABLE IF  EXISTS `pre_aljbd_album_attachments`;
DROP TABLE IF  EXISTS `pre_aljbd_comment_consume`;
DROP TABLE IF  EXISTS `pre_aljbd_consume`;
EOF;

runquery($sql);
//finish to put your own code
$finish = TRUE;
?>