<?
/*
 * @param $filename - String (Путь до файла)
 * @param $date - Array (Данный для занесения)
 *
 */
function printData ($filename, $date) {
    if (file_exists($filename)) {
        $fp = fopen($filename, 'a');
    } else {
        $fp = fopen($filename, 'a');
        fwrite($fp, "\xEF\xBB\xBF");
    }
    fputcsv($fp, $date, ';');
    fclose($fp);
}


function getBody ($title, $body) {
    return '<html>
                <head>
                    <title>'.$title.'</title>
                    <style>
                        table {
                            border-collapse: collapse;
                            border-spacing: 0;
                        }
                        table, td {
                            border: solid 1px black;
                        }
                        td {
                            padding: 3px;
                        }
                        ul {
                            padding-left: 15px;
                            margin: 0;
                        }
                    </style>
                </head>
                <body>
                    '.$body.'
                </body>
            </html>';
}

if( isset($_POST['form-type']) ) {
    // $to       = ""; //Почта получателя
	$to       = "anton.kurbatov.59@gmail.com, staremang@ya.ru"; //Почта получателя (developer)
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; //Кодировка письма
    $headers .= "From: Fitness Marketing <mail@mail.ru>\r\n"; //Наименование и почта отправителя

    $subject = '';
    $message = '';

    $formCheck = true;






    ////////////////////////
    // Гостевой визит (с промо-страницы)
    ////////////////////////

    if ($_POST['form-type'] == "anketa") {

        if ((isset($_POST['name']) && $_POST['name'] != "") && 
            (isset($_POST['tel']) && $_POST['tel'] != "") &&
            (isset($_POST['email']) && $_POST['email'] != "") &&
            (isset($_POST['city']) && $_POST['city'] != "") &&
            (isset($_POST['clubname']) && $_POST['clubname'] != "")) {

            $subject = 'Заявка на гостевой визит с промо-страницы';
            $message = '<table>
                            <tr>
                                <td>Имя:</td>
                                <td>'.trim(urldecode(htmlspecialchars($_POST['name']))).'</td>
                            </tr>
                            <tr>
                                <td>Телефон:</td>
                                <td>'.trim(urldecode(htmlspecialchars($_POST['tel']))).'</td>
                            </tr>
                            <tr>
                                <td>Почта:</td>
                                <td>'.trim(urldecode(htmlspecialchars($_POST['email']))).'</td>
                            </tr>
                            <tr>
                                <td>Город:</td>
                                <td>'.trim(urldecode(htmlspecialchars($_POST['city']))).'</td>
                            </tr>
                            <tr>
                                <td>Название клуба:</td>
                                <td>'.trim(urldecode(htmlspecialchars($_POST['clubname']))).'</td>
                            </tr>
                        </table>';

            printData('form/anketa.csv', array(date('Y-m-d H:i'), $_POST['name'], $_POST['tel']), $_POST['email']), $_POST['city']), $_POST['clubname']));

        } else {
            $formCheck = false;
        }


    ////////////////////////
    // Просьба перезвонить
    ////////////////////////
    } elseif ($_POST['form-type'] == "call-me") {

        if ((isset($_POST['name']) && $_POST['name'] != "") && 
			(isset($_POST['tel']) && $_POST['tel'] != "")) {

            $subject = 'Позвоните мне!';
            $message = '<table>
                            <tr>
                                <td>Имя:</td>
                                <td>'.trim(urldecode(htmlspecialchars($_POST['name']))).'</td>
                            </tr>
                            <tr>
                                <td>Телефон:</td>
                                <td>'.trim(urldecode(htmlspecialchars($_POST['tel']))).'</td>
                            </tr>
                        </table>';


            printData('form/call-me.csv', array(date('Y-m-d H:i'), $_POST['name'], $_POST['tel']));

        } else {
            $formCheck = false;
        }

    } else {
        $formCheck = false;
    }





    if ($formCheck) {

        mail($to, $subject, getBody($subject, $message), $headers);
        echo json_encode(array('sended'=>true, 'type'=>$_POST['form-type'], 'message'=>''));

    } else {

        echo json_encode(array('sended'=>false, 'message'=>'Серверная ошибка. Пните разработчика, он наговнокодил.'));

    }
}



?>