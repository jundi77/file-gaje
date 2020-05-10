<?php
$tasks = array(
    array(
        'classname' => 'local_sync_siak\task\sync_class',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '2',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
        'disabled' => 1
    ),
    array(
        'classname' => 'local_sync_siak\task\sync_enrol',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '3',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
        'disabled' => 1
    ),
);
