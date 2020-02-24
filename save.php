<?php

$link = mysqli_connect('localhost', 'root', '', 'personal') or die(mysqli_error());
mysqli_set_charset($link, 'utf8');

$data = json_decode(file_get_contents('php://input'), true);
$name = mysqli_real_escape_string($link, $data['data'][0]['name']);
$rev = mysqli_real_escape_string($link, $data['data'][0]['revision']);

$value = "";
foreach ($data['data'][0]['entities'] as $key => $val) {

    if (isset($val['label'])) {
        $label = mysqli_real_escape_string($link, $val['label']);
        $shortlabel = mysqli_real_escape_string($link, $val['shortLabel']);
    } else if (isset($val['labels'])) {
        $label = mysqli_real_escape_string($link, $val['labels'][0]['text']);
        $shortlabel = mysqli_real_escape_string($link, $val['labels'][0]['shortText']);
    }

    $value .= "('{$name}', '{$rev}', '{$key}', '{$shortlabel}', '{$label}', NOW()),";
}
$valuex = rtrim($value, ",");

if ($valuex == "") {
    echo json_encode(array('stat' => false));
    return false;
}

$query = "INSERT INTO maps (NAME, REVISION, ID, SHORT_LABEL, LABEL, INSERT_TIME) VALUES {$valuex};";
$result = mysqli_query($link, $query) or die(mysqli_error($link));

if ($result) {
    echo json_encode(array('stat' => true, 'name' => $name));
} else {
    echo json_encode(array('stat' => false));
}
