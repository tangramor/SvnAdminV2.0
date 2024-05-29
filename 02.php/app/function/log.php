<?php
/*
 * @Author: tangramor
 * 
 * @LastEditors: tangramor
 * 
 * @Description: log into /home/svnadmin/logs
 */

function log($logmsg = '', $logtype = 'INFO', $logpath = '/home/svnadmin/logs/')
{
    file_put_contents($logpath . '/php_'.date("Y-m-d").'.log', "[".date(DATE_ATOM)."] ".$logtype." - ".$logmsg."\n", FILE_APPEND);
}
