
<?php

class PHPMailer_Library
{
    public function __construct()
    {
    }

    public function load()
    {
        require_once("phpMailer/PHPMailerAutoload.php");
        $mail = new PHPMailer();
        return $mail;
    }
}

?>
