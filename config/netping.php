<?php

return [
    'netping_login' => env('NETPING_LOGIN', 'LOGIN LINK'),
    'power_state' => env('POWER_STATE', '/io.cgi?io1'),
    'power_state_v4' => env('POWER_STATE_v4', '/io.cgi?io3'),
    'door_state' => env('DOOR_STATE', '/io.cgi?io2'),
    'door_state_v4' => env('DOOR_STATE_v4', '/io.cgi?io2'),
    'alarm_state' => env('ALARM_STATE', '/io.cgi?io3'),
    'alarm_state_v4' => env('ALARM_STATE_v4', '/io.cgi?io1'),
    'netping_state' => env('NETPING_STATE', '/io_get.cgi'),
    'netping_state_v4' => env('NETPING_STATE_v4', '/logic_run.cgi'),
    'alarm_control' => env('ALARM_CONTROL', '/io.cgi?io3&mode='),
    'alarm_control_v4' => env('ALARM_CONTROL_v4', '/logic_run.cgi?'),
    'alarm_switch_v4' => env('ALARM_SWITCH_V4', '/io.cgi?io1=')
];
