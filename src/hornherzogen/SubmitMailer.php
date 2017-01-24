<?php namespace hornherzogen;

class SubmitMailer
{
    private $email;// tbd!
    private $firstname = 'Philio'; // tbd!
    private $lastname = 'Egonitschow'; // tbd!

    private $subject = 'Herzogenhorn 2017 - Deine Anmeldung ist engegangen bei филипп/フィリップ';

// In case you need authentication you should switch the the PEAR module
    // https://www.lifewire.com/send-email-from-php-script-using-smtp-authentication-and-ssl-1171197

    public function send()
    {
        date_default_timezone_set('Europe/Berlin');

        // TODO externalize in separate class that maps form input into a bean with a boolean isValid()
        $this->email = $_POST['email'];
        if (empty($this->email) || $this->email != $_POST["emailcheck"]) {
            return '<p>Invalid emailadress - no mail to send</p>';
        }

        $replyto = $GLOBALS["horncfg"]["registrationmail"];
        // PHP7.0: ?? 'invalidconfigurationfile@example.com';
        if (empty($replyto)) {
            $replyto = 'invalidconfigurationfile@example.com';
        }

        $importance = 1; //1 UrgentMessage, 3 Normal

        // HowToSend at all: https://wiki.goneo.de/mailversand_php_cgi

        // Fix encoding errors in subject:
        // http://stackoverflow.com/questions/4389676/email-from-php-has-broken-subject-header-encoding#4389755
        // https://ncona.com/2011/06/using-utf-8-characters-on-an-e-mail-subject/
        $preferences = ['input-charset' => 'UTF-8', 'output-charset' => 'UTF-8'];
        $encoded_subject = iconv_mime_encode('Subject', $this->subject, $preferences);
        $encoded_subject = substr($encoded_subject, strlen('Subject: '));

        // set all necessary headers to prevent being treated as SPAM in some mailers, headers must not start with a space
        $headers =
            'Bcc: ' . $replyto . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .

            'X-Priority: ' . $importance . "\r\n" .
            'Importance: ' . $importance . "\r\n" .
            'X-MSMail-Priority: High' . "\r\n" .

            'Reply-To: ' . $replyto . "\r\n" .
            // https://api.drupal.org/api/drupal/includes%21mail.inc/function/drupal_mail/6.x
            'From: ' . $replyto . "\r\n" .
            'Sender: ' . $replyto . "\r\n" .
            'ReturnPath: ' . $replyto . "\r\n" .
            'Errors-To: ' . $replyto . "\r\n" .

            'Content-type: text/html; charset=utf-8' . "\r\n" .
            'Date: ' . date("r") . "\r\n" .
            'Message-ID: <' . md5(uniqid(microtime())) . '@' . $_SERVER["SERVER_NAME"] . ">\r\n" .
            'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"] . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($this->email, $encoded_subject, $this->getMailtext(), $headers, "-f " . $replyto);
    }

    public function getMailtext()
    {
        $name = trim($this->firstname . ' ' . $this->lastname);

        return '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Anmeldebestätigung Herzogenhorn Woche TBD eingegangen</title >
        </head>
        <body>
            <h1>Herzogenhorn 2017 - Anmeldung für Woche 1</h1>
            <p>
                Hallo ' . $name . ', wir haben Deine Anmeldedaten für den Herzogenhornlehrgang 2017 um ' . date('Y-m-d H:i:s') . '
                erhalten und melden uns sobald die Anmeldefrist abgelaufen ist und wir die beiden Wochen geplant haben.
                <br />
                Danke für Deine Geduld -<br /> 
                sonnige Grüße aus Berlin
                von Benjamin und Philipp<br />
            </p>
        </body>
    </html>';
    }

    /**
     * private function mail_utf8($to, $from_user, $from_email,
     * $subject = '(No subject)', $message = '')
     * {
     * $from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
     * $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
     *
     * $headers = "From: $from_user <$from_email>\r\n".
     * "MIME-Version: 1.0" . "\r\n" .
     * "Content-type: text/html; charset=UTF-8" . "\r\n";
     *
     * return mail($to, $subject, $message, $headers);
     * }
     */

}

?>