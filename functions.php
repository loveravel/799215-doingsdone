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

function nav_item_count($task_type, $category_name) {
    $count = 0;
    foreach ($task_type as $value) {
        if ($value['type'] == $category_name) {
            $count++;
        }
    };
    return $count;
};