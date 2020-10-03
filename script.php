<?php
require_once ('PHPMailer/PHPMailerAutoload.php');

$username = "";
$password = "";
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
$emails = imap_search($inbox, 'UNSEEN');

if ($emails)
{

    $output = '';

    rsort($emails);

    foreach ($emails as $email_number)
    {

        $overview = imap_fetch_overview($inbox, $email_number, 0);
        if ($overview[0]->seen ? '0' : '1' == '1')
        {
            $message = imap_fetchbody($inbox, $email_number, 2);

            $from = getEmail($message, '>mail:', '</a></p>');

            //Label Mail
            $label_mailbox = date('Ym');
            $copy_success = imap_mail_copy($inbox, '1,2,3', $label_mailbox);
            //
            

            //Parse Mail
            $parsed_products_array = ParseEmail($message, $from);

            StoreJson($parsed_products_array);

            //Send URL to User
            sendURLtoSender($username, $password, $from, $parsed_products_array);

        }

    }

}
imap_close($inbox);

function getEmail($str, $from, $to)
{
    $sub = substr($str, strpos($str, $from) + strlen($from) , strlen($str));
    return substr($sub, 0, strpos($sub, $to));
}

function ParseEmail($message, $email)
{

    $products = array(
        "https://www.{demo}.com/en/postal-united-kingdom",
        "https://www.{demo}.com/en/postal-cocos-keeling-islands",
        "https://www.{demo}.com/en/city-sint-maarten",
        "https://www.{demo}.com/en/caribbean-netherlands",
        "https://www.{demo}.com/en/germany",
        "https://www.{demo}.com/malaysia"
    );
    $parsed_products = array();

    foreach ($products as $product)
    {

        if (strpos($message, $product) !== false)
        {
            $product_name = basename($product);
            $parsed_products[] = array(
                'username' => $email,
                'expiry' => strtotime("+1 month") ,
                'filename' => $product_name . '.zip',
                'url' => GenerateURL(10)
            );

        }
    }

    return $parsed_products;
}

function ParseURL($url)
{
    $array_url = parse_url($url);

}

function StoreJson($response)
{

    $json = file_get_contents('save.json');
    $data = json_decode($json);
    $data[] = $response;
    file_put_contents('save.json', json_encode($data));

}

function GenerateURL($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0;$i < $length;$i++)
    {
        $randomString .= $characters[rand(0, $charactersLength - 1) ];
    }
    return $randomString;

}

function sendURLtoSender($username, $password, $sender, $parsed_products_array)
{
    $domain = "";
    $message = "";
    foreach ($parsed_products_array as $key => $value)
    {
        $filename = $value["filename"];
        $url = $domain."/download.php?token=" . $value["url"];
        $message .= "Download: <a href='$url'>" . $filename . "</a><br>";
    }
    $mail = new PHPMailer(true);

    try
    {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->isHTML();
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SetFrom($username, 'Name');
        $mail->Subject = "Download Your Content";
        $mail->Body = $message;
        $mail->AddAddress($sender);
        $mail->Send();

    }
    catch(Exception $e)
    {
        echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
    }

}

?>
