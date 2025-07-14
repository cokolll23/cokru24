<?php

class BXHelper
{

    public static function addLog($message,$itemID='',$moduleID='',$type='',$severity=''){

        CEventLog::Add(array(
            "SEVERITY" => $severity,
            "AUDIT_TYPE_ID" => $type,
            "MODULE_ID" => $moduleID,
            "ITEM_ID" => $itemID,
            "DESCRIPTION" => $message,
        ));
    }


}