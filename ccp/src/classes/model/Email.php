<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Poker\Ccp\classes\utility\DateTimeUtility;
class Email extends Base {
    private const EMAIL_ADDRESS_LOCAL = "me@localhost.com";
    private bool $local;
    private array $localEmail;
    public function __construct(protected bool $debug, protected array $fromName, protected array $fromEmail, protected array $toName, protected array $toEmail, protected ?array $ccName, protected ?array $ccEmail, protected ?array $bccName, protected ?array $bccEmail, protected ?string $subject, protected ?string $body) {
        parent::__construct(debug: $debug, id: NULL);
        $this->local = Constant::FLAG_LOCAL();
        return $this;
    }
    public function getFromName(): array {
    return $this->fromName;
    }
    public function getFromEmail(): array {
    return $this->fromEmail;
    }
    public function getToName(): array {
    return $this->toName;
    }
    public function getToEmail(): array {
        $toEmail = $this->toEmail;
        if ($this->isLocal()) {
            $this->setLocalEmail($toEmail);
            foreach ($toEmail as $key => $value) {
                $toEmail[$key] = self::EMAIL_ADDRESS_LOCAL;
            }
            unset($value);
            $this->setLocalEmail($toEmail);
            $toEmail = $this->getLocalEmail();
        }
        return $toEmail;
    }
    public function getCcName(): ?array {
        return $this->ccName;
    }
    public function getCcEmail(): ?array {
        return $this->ccEmail;
    }
    public function getBccName(): ?array {
        return $this->bccName;
    }
    public function getBccEmail(): ?array {
        return $this->bccEmail;
    }
    public function getSubject(): ?string {
        return $this->subject;
    }
    public function getBody(): ?string {
        return $this->body;
    }
    public function isLocal(): bool {
        return $this->local;
    }
    public function getLocalEmail(): array {
        return $this->localEmail;
    }
    public function setFromName(array $fromName): Email {
        $this->fromName = $fromName;
        return $this;
    }
    public function setFromEmail(array $fromEmail): Email {
        $this->fromEmail = $fromEmail;
        return $this;
    }
    public function setToName(array $toName): Email {
        $this->toName = $toName;
        return $this;
    }
    public function setToEmail(array $toEmail): Email {
        $this->toEmail = $toEmail;
        return $this;
    }
    public function setCcName(array $ccName): Email {
        $this->ccName = $ccName;
        return $this;
    }
    public function setCcEmail(array $ccEmail): Email {
        if ($this->isLocal()) {
            $this->setBody("<br>CC to " . print_r(value: $ccEmail, return: true) . "\n\n" . $this->getBody());
            $ccEmail = array($this->localEmail);
        }
        $this->ccEmail = $ccEmail;
        return $this;
    }
    public function setBccName(array $bccName): Email {
        $this->bccName = $bccName;
        return $this;
    }
    public function setBccEmail(array $bccEmail): Email {
        $this->bccEmail = $bccEmail;
        return $this;
    }
    public function setSubject(string $subject): Email {
        $this->subject = $subject;
        return $this;
    }
    public function setBody(string $body): Email {
        $this->body = $body;
        return $this;
    }
    public function setLocal(bool $local): Email {
        $this->local = $local;
        return $this;
    }
    public function setLocalEmail(?array $localEmail): Email {
        $this->localEmail = $localEmail;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= " fromName = ";
        $output .= print_r(value: $this->fromName, return: true);
        $output .= ", fromEmail = ";
        $output .= print_r(value: $this->fromEmail, return: true);
        $output .= ", toName = ";
        $output .= print_r(value: $this->toName, return: true);
        $output .= ", toEmail = ";
        $output .= print_r(value: $this->toEmail, return: true);
        $output .= ", ccName = ";
        $output .= print_r(value: $this->ccName, return: true);
        $output .= ", ccEmail = ";
        $output .= print_r(value: $this->ccEmail, return: true);
        $output .= ", bccName = ";
        $output .= print_r(value: $this->bccName, return: true);
        $output .= ", bccEmail = ";
        $output .= print_r(value: $this->bccEmail, return: true);
        $output .= ", subject = '";
        $output .= $this->subject;
        $output .= "', body = '";
        $output .= $this->body;
        $output .= "', local = ";
        $output .= var_export(value: $this->local, return: true);
        $output .= ", localEmail = ";
        $output .= print_r(value: $this->localEmail, return: true);
        return $output;
    }

    // from, to, cc, bcc should comply with
    // user@example.com
    // User <user@example.com>
    // each body line < 70 characters and separated with \n
    public function sendEmail(): string {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = $this->isDebug() ? SMTP::DEBUG_LOWLEVEL : SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Timeout = 60;
        $mail->Host = Constant::SERVER_EMAIL();
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);
        for ($idx = 0; $idx < count($this->fromName); $idx ++) {
            $mail->SetFrom(address: $this->getFromEmail()[$idx], name: $this->getFromName()[$idx]);
            $mail->AddAddress(address: $this->getToEmail()[$idx], name: $this->getToName()[$idx]);
            if (isset($this->getCcEmail()[$idx]) && isset($this->getCcName()[$idx])) {
                $mail->AddCC(address: $this->getCcEmail()[$idx], name: $this->getCcName()[$idx]);
            }
            if (isset($this->getBccEmail()[$idx]) && isset($this->getBccName()[$idx])) {
                $mail->AddBCC(address: $this->getBccEmail[$idx], name: $this->getBccName()[$idx]);
            }
            $mail->Subject = $this->getSubject();
            $mail->Body = $this->getBody();
            $message = "";
            if (! $mail->send()) {
                $message .= "Message could not be sent to " . $this->getToName()[$idx] . " due to " . $mail->ErrorInfo;
            } else {
                $message .= "Message has been sent to " . $this->getToName()[$idx] . " at " . $this->getToEmail()[$idx];
                if (isset($this->getCcEmail()[$idx]) && isset($this->getCcName()[$idx])) {
                    $message .= " and cc to " . $this->getCcName()[$idx] . " at " . $this->getCcEmail()[$idx];
                }
            }
        }
        return $message;
    }

    public function sendRegisteredEmail(Location $location, Tournament $tournament, string $feeStatus, string|int $waitList, string $autoRegister = NULL): string {
        $result = "";
        $url = "";
        for ($idx = 0; $idx < count($this->fromName); $idx ++) {
            $url .= Constant::PATH() . "registration.php?tournamentId=" . $tournament->getId();
            if (isset($autoRegister)) {
                $subject = "Auto registration of " . $autoRegister . " was successful for the tournament on ";
            } else {
                $subject = "registration (update) successful for the tournament on ";
            }
            $subject .= DateTimeUtility::formatDisplayDate($tournament->getDate()) . " starting at " . DateTimeUtility::formatDisplayTime($tournament->getStartTime());
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />&nbsp;&nbsp;Your season fee is " . $feeStatus . ".<BR />";
            if (is_string($waitList)) {
                $body .= "&nbsp;&nbsp;" . $waitList . " was successfully moved from the wait list to registered ";
            } else {
                if (isset($autoRegister)) {
                    $body .= "&nbsp;&nbsp;Auto registration of " . $autoRegister . " was successful ";
                } else {
                    $body .= "&nbsp;&nbsp;Your registration (update) was successful ";
                }
                if ($waitList > 0) {
                    $body .= "(you were added to the wait list) ";
                } else if ($waitList == - 99) {
                    $body .= "(you were moved from the wait list to registered) ";
                }
            }
            $body .= "for the tournament on " . DateTimeUtility::formatDisplayDate($tournament->getDate()) . " starting at " . DateTimeUtility::formatDisplayTime($tournament->getStartTime()) . " with additional details below:<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $location->getAddress() . "<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $location->getCity() . ", " . $location->getState() . " " . $location->getZipCode() . "<br />";
            $body .= "&nbsp;&nbsp;If you need to cancel your registration for this tournament, please <a href=\"" . $url . "\">click here</a>";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }

    public function sendReminderEmail(Location $location, Tournament $tournament, int $waitListCount): string {
        $result = "";
        $url = "";
        for ($idx = 0; $idx < count(value: $this->fromName); $idx ++) {
            $url .= Constant::PATH() . "registration.php?tournamentId=" . $tournament->getId();
            $subject = " registration is open reminder for the tournament on ";
            $subject .= DateTimeUtility::formatDisplayDate($tournament->getDate()) . " starting at " . DateTimeUtility::formatDisplayTime($tournament->getStartTime());
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />";
            $body .= "&nbsp;&nbsp;Registration is open ";
            if ($waitListCount > 0) {
                $body .= "(on the wait list) ";
            }
            $body .= "for the tournament on " . DateTimeUtility::formatDisplayDate($tournament->getDate()) . " starting at " . DateTimeUtility::formatDisplayTime($tournament->getStartTime()) . " with additional details below:<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $tournament->getDescription() . "<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;Hosted by " . $tournament->getLocation()->getPlayer()->getName() . "<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $tournament->getLimitType()->getName() . " " . $tournament->getGameType()->getName() . "<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;$" . $tournament->getBuyinAmount() . " for " . $tournament->getChipCount() . " chips<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $tournament->getMaxRebuys() . " rebuy(s) in first hour";
            if ($tournament->getMaxRebuys() > 0) {
                $body .= ", $" . $tournament->getRebuyAmount() . " for " . $tournament->getChipCount() . " chips<br />";
            } else {
                $body .= "<br />";
            }
            if ($tournament->getAddonAmount() > 0) {
                $body .= "&nbsp;&nbsp;&nbsp;&nbsp;$" . $tournament->getAddonAmount() . " addon for " . $tournament->getAddonChipCount() . " chips<br />";
            }
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $location->getAddress() . "<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $location->getCity() . ", " . $location->getState() . " " . $location->getZipCode() . "<br />";
            $body .= "&nbsp;&nbsp;If you need to register for this tournament, please <a href=\"" . $url . "\">click here</a>";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }

    public function sendCancelledEmail(Location $location, Tournament $tournament): string {
        $result = "";
        $url = "";
        for ($idx = 0; $idx < count(value: $this->fromName); $idx ++) {
            $url .= Constant::PATH() . "registration.php?tournamentId=" . $tournament->getId();
            $subject = "registration cancelled for the tournament on ";
            $subject .= DateTimeUtility::formatDisplayDate($tournament->getDate()) . " starting at " . DateTimeUtility::formatDisplayTime($tournament->getStartTime());
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />";
            $body .= "&nbsp;&nbsp;Your registration was cancelled for the tournament on " . DateTimeUtility::formatDisplayDate($tournament->getDate()) . " starting at " . DateTimeUtility::formatDisplayTime($tournament->getStartTime()) . " with additional details below:<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $location->getAddress() . "<br />";
            $body .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $location->getCity() . ", " . $location->getState() . " " . $location->getZipCode() . "<br />";
            $body .= "&nbsp;&nbsp;If you need to re-register for this tournament, please <a href=\"" . $url . "\">click here</a>";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }

    public function sendSignUpEmail(): string {
        $result = "";
        for ($idx = 0; $idx < count(value: $this->fromName); $idx ++) {
            $subject = "sign up request sent for approval";
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />";
            $body .= "&nbsp;&nbsp;Your sign up request was sent for approval. Once it has been approved or rejected you will receive an email with further instructions.";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }

    public function sendSignUpApprovalEmail(): string {
        $result = "";
        for ($idx = 0; $idx < count(value: $this->fromName); $idx ++) {
            $subject = "sign up request needs approval";
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />";
            $body .= "&nbsp;&nbsp;" . $this->fromName[$idx] . " sign up request requires your approval. Please <a href=\"" . (Constant::PATH()) .
            "manageSignupApproval.php\">click here</a> to go to the approval screen.";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }

    public function sendApprovedEmail(): string {
        $result = "";
        for ($idx = 0; $idx < count(value: $this->fromName); $idx ++) {
            $subject = "sign up request approved";
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />";
            $body .= "&nbsp;&nbsp;Your request has been approved. Please <a href=\"" . (Constant::PATH()) . "\">click here</a> to login.";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }

    public function sendRejectedEmail(): string {
        $result = "";
        for ($idx = 0; $idx < count(value: $this->fromName); $idx ++) {
            $subject = "sign up request rejected";
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />";
            $body .= "&nbsp;&nbsp;Your request has been rejected.";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }

    // array of username, email, selector and validator
    public function sendPasswordResetRequestEmail(array $info, array $selectorAndToken): string {
        $result = "";
        for ($idx = 0; $idx < count(value: $this->fromName); $idx ++) {
            $subject = "password reset request";
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />";
            $url = Constant::PATH() . "resetPassword.php?mode=resetPassword&username=" . $info[0] . "&email=" . $info[1] . "&selector=" . $selectorAndToken[0] . "&validator=" . $selectorAndToken[1];
            $body .= "&nbsp;&nbsp;Your request has been received. Please <a href=\"" . $url . "\">click here</a> to reset your password.";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }

    public function sendPasswordResetSuccessfulEmail(): string {
        $result = "";
        for ($idx = 0; $idx < count(value: $this->fromName); $idx ++) {
            $subject = "password reset successfully";
            $this->setSubject(subject: $subject);
            $body = $this->getBody() . "<br />" . $this->toName[$idx] . ",<br />";
            $url = Constant::PATH() . "login.php";
            $body .= "&nbsp;&nbsp;Your password was changed successfully. Please <a href=\"" . $url . "\">click here</a> to login.";
            $this->setBody(body: $body);
            $result .= $this->sendEmail();
        }
        return $result;
    }
}