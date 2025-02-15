<?php
$hook_version = 1;
$hook_array = array();

$hook_array['before_api_call'][] = array(
    2,
    'Custom check for noLoginRequired GET calls',
    'custom/modules/ApiCheckHook.php',
    'ApiCheckHook',
    'beforeApiCheck',
);
