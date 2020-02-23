<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* read files */
$myfile = fopen("fusioncharts.worldwithantarctica.js", "r") or die("Unable to open file!");
$string = fread($myfile, filesize("fusioncharts.worldwithantarctica.js"));
/* replace new line */
$str = str_replace(array("\r", "\n"), "", $string);

$str2 = "";
if (preg_match('/\*\/(.*?);exports/', $string, $match) == 1) {
    $str2 = $match[1];
}
$str2 = str_replace('geodefinitions', 'geodefinitions_0', $str2);

echo file_put_contents("fusioncharts.worldwithantarctica.data.js", $str2);

//$str2 = rtrim($str2, "]");
//$str2 = ltrim($str2, "[");
//
///* Encapsulate keys with quotes */
//$to_decode = preg_replace('/([a-z_]+)\:/ui', '"{$1}":', $str2);
//$to_decode = str_replace('"{', '"', $to_decode);
//$to_decode = str_replace('}"', '"', $to_decode);
//$to_decode = str_replace('\'', '"', $to_decode);
//
//print_r($to_decode);
//fclose($myfile);
?>


<!DOCTYPE html>
<html>
    <head>
        Standard Meta 
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

        Site Properties 
        <title>Helpdesk | Login</title>
    </head>
    <body>
        <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
        <!--<script type="text/javascript" src="fusioncharts.js"></script>-->
        <!--<script type="text/javascript" src="fusioncharts.maps.js"></script>-->
        <!--<script type="text/javascript" src="fusioncharts.worldwithantarctica.js"></script>-->
        <script type="text/javascript">
            $(document).ready(function () {
//                formhandling();
            });


            $.getScript("fusioncharts.worldwithantarctica.data.js", function () {
                console.log(geodefinitions_0);
            });



            function formhandling() {
                $('#form_login').submit(function (e) {
                    return false;
                }).form({
                    fields: {
                        user: {
                            identifier: 'in_username',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Please enter your ID Card'
                                }
                            ]
                        },
                        pass: {
                            identifier: 'in_password',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Please enter your Password'
                                },
                                {
                                    type: 'length[3]',
                                    prompt: 'Your password must be at least 3 characters'
                                }
                            ]
                        }
                    },
                    inline: true,
                    on: 'blur',
                    onSuccess: function () {
                        var mydata = {
                            usr: $('#in_username').val(),
                            pas: $('#in_password').val(),
                            op: 'login'
                        };

                        $.ajax({
                            url: 'controller/global.php',
                            type: 'POST',
                            data: mydata,
                            timeout: 25000,
                            dataType: 'json',
                            beforeSend: function () {
                                $('#bt_login').addClass('disabled loading');
                            },
                            success: function (result, status, xhr) {
                                if (result.stat === false) {
                                    $('#modal_fail').modal({
                                        onVisible: function () {
                                            $(document).keypress(function (e) {
                                                if (e.which === 13 && $('#modal_fail').modal('is active') === true) {
                                                    $('#modal_fail').modal('hide');
                                                }
                                            });
                                        }
                                    }).modal('show');
                                } else if (result.stat === true) {
                                    $.each(result.data, function (idx, val) {
                                        localStorage.setItem(idx, val);
                                    });

                                    window.location = result.data.helpdesk_page;
                                }
                            },
                            error: function (xhr, status, error) {
                                alert(status + "\n" + xhr + "\n" + error);
                            },
                            complete: function (xhr, status) {
                                $('#bt_login').removeClass('disabled loading');
                            }
                        });
                    }
                });
            }
        </script>
    </body>
</html>
