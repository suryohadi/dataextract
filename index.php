<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$files = glob('map/*'); //search all files
$js_files = preg_grep('/\.(js)$/i', $files); //filter only javascript

$arr = array(); //list of filename
foreach ($js_files as $idx => $jsf) {
    $dest = str_replace('map/', 'data/', $jsf);
    extractdata($_SERVER['DOCUMENT_ROOT'] . '/tes/' . $jsf, $dest);
    $arr[] = $dest;
}

/*
 * extractdata(source_path, destination_path) 
 */

function extractdata($src_filepath, $des_filepath) {
    /* read files */
    $myfile = fopen($src_filepath, "r") or die("Unable to open file!");
    $string = fread($myfile, filesize($src_filepath));

    /* replace new line, space */
    $str = str_replace(array("\r", "\n"), "", $string);

    /* get data only from js */
    $str2 = "";
    if (preg_match('/\*\/(.*?);exports/', $str, $match) == 1) {
        $str2 = $match[1];
    }

    /* save to file */
    file_put_contents($des_filepath, $str2);
    fclose($myfile);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>web title</title>
    </head>
    <body>
        <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                //looping list of filenames then POST
                var arr = [<?php echo "'" . implode('\', \'', $arr) . "'" ?>];
                $.each(arr, function (idx, val) {
                    $.getScript(val, function () {
                        send_data(geodefinitions);
                    });
                });
            });

            /*
             * send_data(mapdata array object)
             */
            var number = 1;
            function send_data(mydata) {
                var mapdata = {
                    data: mydata
                };

                $.ajax({
                    url: 'save.php',
                    type: 'POST',
                    contentType: 'application/json; charset=utf-8',
                    data: JSON.stringify(mapdata),
                    timeout: 25000,
                    dataType: 'json',
                    success: function (result, status, xhr) {
                        if (result.stat === false) {
                            alert('Failed');
                        } else {
                            $('<p>' + number + '. ' + result.name + '-ok</p>').appendTo(document.body);
                            number++;
                        }
                    },
                    error: function (xhr, status, error) {
                        alert(status + "\n" + xhr + "\n" + error);
                    }
                });
            }
        </script>
    </body>
</html>
