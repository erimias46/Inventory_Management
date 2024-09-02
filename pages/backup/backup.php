<?php
// include 'madeline.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';
 




class Backup
{
    public function backup_tables($host = '109.70.148.67', $user = 'habeshyq_erimias', $pass = '+s3wY8jnE@kJ', $dbname = 'habeshyq_fgsystemnet_elegant', $tables = '*')
    {
        $link = mysqli_connect($host, $user, $pass, $dbname);

        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit;
        }

        mysqli_query($link, "SET NAMES 'utf8'");

        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = mysqli_query($link, 'SHOW TABLES');
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        $return = '';
        //cycle through
        foreach ($tables as $table) {
            $result = mysqli_query($link, 'SELECT * FROM ' . $table);
            $num_fields = mysqli_num_fields($result);
            $num_rows = mysqli_num_rows($result);

            $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE ' . $table));
            $return .= "\n\n" . $row2[1] . ";\n\n";
            $counter = 1;

            //Over tables
            for ($i = 0; $i < $num_fields; $i++) {   //Over rows
                while ($row = mysqli_fetch_row($result)) {
                    if ($counter == 1) {
                        $return .= 'INSERT INTO ' . $table . ' VALUES(';
                    } else {
                        $return .= '(';
                    }

                    //Over fields
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return .= ',';
                        }
                    }

                    if ($num_rows == $counter) {
                        $return .= ");\n";
                    } else {
                        $return .= "),\n";
                    }
                    ++$counter;
                }
            }
            $return .= "\n\n\n";
        }

        // delete the files already existing 
        $files = glob('backups/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }

        //save file
        // $date = date_create();
        $date = time();
        $fileName = 'backups/'. $date . '.sql';
        // $handle = fopen($fileName,'w+');
        // fwrite($handle,$return);
        if (file_put_contents($fileName, $return) !== false) {

            
        $mail = new PHPMailer(true);

try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'elegant.habeshamovie.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'test1@elegant.habeshamovie.com';                     //SMTP username
    $mail->Password   = '6$31@zvc1i$d';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('test1@elegant.habeshamovie.com', 'Mailer');
    $mail->addAddress('erimias46@gmail.com', 'Erimias ');     //Add a recipient
    

    //Attachments
    $mail->addAttachment($fileName);         //Add attachments
        //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'last updated back up file';
    $mail->Body    = 'last updated back up file';
    $mail->AltBody = '';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
            return "Done, the file name is in the backup folder: " . $fileName;
            exit;
        }
    }
    
   
    public function backup_tableslc($host = '109.70.148.67', $user = 'habeshyq_erimias', $pass = '+s3wY8jnE@kJ', $dbname = 'habeshyq_fgsystemnet_elegant', $tables = '*'){
        $link = mysqli_connect($host, $user, $pass, $dbname);

        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit;
        }

        mysqli_query($link, "SET NAMES 'utf8'");

        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = mysqli_query($link, 'SHOW TABLES');
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        $return = '';
        //cycle through
        foreach ($tables as $table) {
            $result = mysqli_query($link, 'SELECT * FROM ' . $table);
            $num_fields = mysqli_num_fields($result);
            $num_rows = mysqli_num_rows($result);

            $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE ' . $table));
            $return .= "\n\n" . $row2[1] . ";\n\n";
            $counter = 1;

            //Over tables
            for ($i = 0; $i < $num_fields; $i++) {   //Over rows
                while ($row = mysqli_fetch_row($result)) {
                    if ($counter == 1) {
                        $return .= 'INSERT INTO ' . $table . ' VALUES(';
                    } else {
                        $return .= '(';
                    }

                    //Over fields
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return .= ',';
                        }
                    }

                    if ($num_rows == $counter) {
                        $return .= ");\n";
                    } else {
                        $return .= "),\n";
                    }
                    ++$counter;
                }
            }
            $return .= "\n\n\n";
        }

        // delete the files already existing 
        $files = glob('backups/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }

        //save file
        // $date = date_create();
        $date = time();
        $fileName = 'backups/'. $date . '.sql';
        // $handle = fopen($fileName,'w+');
        // fwrite($handle,$return);
        if (file_put_contents($fileName,$return) !== false) {
            
  header('Content-Type: application/octet-stream');
  header("Content-Transfer-Encoding: Binary");
  header("Content-disposition: attachment; filename=\"".$fileName."\"");
  echo $return;  exit;
            
//       
// }
            return "Done, the file name is in the backup folder: " . $fileName;
            exit;
        }
    }
}