<?php
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require_once $name;

    $result = ob_get_clean();

    return $result;
};

function nav_item_count($task_type, $project_id) {
    $count = 0;
    foreach ($task_type as $value) {
        if ($value['project_id'] == $project_id) {
            $count++;
        }
    };
    return $count;
};