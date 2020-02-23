<?php

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['data'][0]['name'];

$value = "";
foreach ($data['data'][0]['entities'] as $key => $val) {
    $value .= "('{$name}', '{$val['label']}', '{$val['shortLabel']}'),";
}

if ($value != "") {
    echo json_encode(array('stat' => true, 'name' => $name));
} else {
    echo json_encode(array('stat' => false));
}
