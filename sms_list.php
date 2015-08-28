<?php
include 'page_header.php';
listMaker('sms_files','date_sent desc',array('sms','date_sent','letter_code'),'SMS List');
?>