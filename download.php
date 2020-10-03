<?php

require_once('PHPMailer/PHPMailerAutoload.php');


$token = "";


$username = "";
$expiry = "";
$filename = "";
$url = "";


if(!empty($_GET['token'])){
    $token = $_GET['token'];
}




$string = file_get_contents("save.json");
$json_a = json_decode($string, true);




foreach ($json_a as $key => $jsons) {
  foreach($jsons as $key => $value) {
       
        $url = $value["url"];
        if ($token == $value["url"]){
            $username = $value["username"];
            $expiry = $value["expiry"];
            $filename = $value["filename"];
        }
 
   } 
}




  if (CheckNotExpired($expiry)){
  DownloadFile($filename);
  SendEmail($username,$filename);

  } else{
     echo "URL Expired";
  
  }
  







function CheckNotExpired($unix){

    $expire_date = date("Y-m-d H:i:s",$unix);
    $now = date("Y-m-d H:i:s"); 
    
    if ($now<$expire_date) {
        return true;
    }

    return false;

}





function DownloadFile($filename){

header('Content-type: application/zip');
header('Content-Disposition: attachment; filename='.$filename);
readfile($filename);

}

function SendEmail($client,$filename){
    $mail = new PHPMailer(true);
    $username = "";
    $password = "";
    try {  
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->isHTML();
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SetFrom($username,'Sari Postcodezip');
        $mail->Subject = "Somebody downloaded your file: ".$filename;
        $mail->Body = 'The user who download was '.$client;
        $mail->AddAddress($username);
        $mail->Send();
        
      
    } catch (Exception $e) {
        echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
    }

}

?>
