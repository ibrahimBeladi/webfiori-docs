<?php
/*
 * The MIT License
 *
 * Copyright 2019 Ibrahim, WebFiori Framework.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace webfiori\entity\mail;

use phpStructs\html\HTMLDoc;
use phpStructs\html\HTMLNode;
use webfiori\conf\MailConfig;
use webfiori\entity\exceptions\SMTPException;
use webfiori\entity\File;
use webfiori\logic\EmailController;
/**
 * A class that can be used to write HTML formatted Email messages.
 *
 * @author Ibrahim
 * @version 1.0.4
 */
class EmailMessage {
    /**
     *
     * @var HTMLDoc 
     * @since 1.0 
     */
    private $asHtml;
    /**
     *
     * @var EmailMessage 
     * @since 1.0
     */
    private static $em;
    private static $log;
    /**
     *
     * @var SocketMailer
     * @since 1.0 
     */
    private $socketMailer;
    /**
     * Creates new instance of the class.
     * @param type $sendAccountName
     * @return type
     * @throws SMTPException
     * @since 1.0
     */
    private function __construct($sendAccountName = '') {
        self::$log = [];

        if (class_exists('webfiori\conf\MailConfig')) {
            $acc = MailConfig::getAccount($sendAccountName);

            if ($acc instanceof SMTPAccount) {
                $this->socketMailer = EmailController::get()->getSocketMailer($acc);

                if ($this->socketMailer == EmailController::INV_CREDENTIALS) {
                    throw new SMTPException('The account "'.$sendAccountName.'" has invalid credintials.');
                } else {
                    if ($this->socketMailer == EmailController::INV_HOST_OR_PORT) {
                        throw new SMTPException('The account "'.$sendAccountName.'" has invalid host or port number. Port: '.$acc->getPort().', Host: '.$acc->getServerAddress().'.');
                    } else {
                        $this->asHtml = new HTMLDoc();
                        $this->asHtml->getHeadNode()->addMeta('charset', 'UTF-8');

                        return;
                    }
                }
            }
            throw new SMTPException('No SMTP account was found which has the name "'.$sendAccountName.'".');
        }
        throw new SMTPException('Class "MailConfig" not found.');
    }
    /**
     * Adds new receiver address to the list of message receivers.
     * @param string $name The name of the email receiver (such as 'Ibrahim').
     * @param string $email The email address of the receiver (such as 'example@example.com').
     * @param boolean $isCC If set to true, the receiver will receive 
     * a carbon copy of the message (CC).
     * @param boolean $isBcc If set to true, the receiver will receive 
     * a blind carbon copy of the message (Bcc).
     * @since 1.0.4
     */
    public static function addReceiver($name,$email,$isCC = false,$isBcc = false) {
        self::addReciver($name, $email, $isCC, $isBcc);
    }
    /**
     * Adds new receiver address to the list of message receivers.
     * @param string $name The name of the email receiver (such as 'Ibrahim').
     * @param string $email The email address of the receiver (such as 'example@example.com').
     * @param boolean $isCC If set to true, the receiver will receive 
     * a carbon copy of the message (CC).
     * @param boolean $isBcc If set to true, the receiver will receive 
     * a blind carbon copy of the message (Bcc).
     * @since 1.0
     * @deprecated since version 1.0.4
     */
    public static function addReciver($name,$email,$isCC = false,$isBcc = false) {
        self::createInstance()->_getSocketMailer()->addReceiver($name, $email, $isCC, $isBcc);
    }
    /**
     * Adds a file to the email message as an attachment.
     * @param File $file The file that will be added. It will be added only if the file 
     * exist in the path or the raw data of the file is set.
     * @since 1.0
     */
    public static function attach($file) {
        self::createInstance()->_getSocketMailer()->addAttachment($file);
    }
    /**
     * Creates new email message.
     * @param string $sendAccountName The name of SMTP account that will be used 
     * to send the message. The account must exist in the file 'MailConfig.php'. 
     * If it does not exist, an exception will be thrown. The name of the account 
     * must be supplied only for the first call.
     * @return EmailMessage
     * @since 1.0
     */
    public static function createInstance($sendAccountName = '') {
        if (self::$em === null) {
            self::$em = new EmailMessage($sendAccountName);
        }

        return self::$em;
    }
    /**
     * Sets or returns the HTML document that is associated with the email 
     * message.
     * @param HTMLDoc $new If it is not null, the HTML document 
     * that is associated with the message will be set to the given one.
     * @return HTMLDoc The document that is associated with the email message.
     * @since 1.0
     */
    public static function document($new = null) {
        if ($new != null) {
            self::createInstance()->_setDocument($new);
        }

        return self::createInstance()->_getDocument();
    }
    /**
     * Returns an associative array that contains the names and the addresses 
     * of people who will receive a blind carbon copy of the message.
     * The indices of the array will act as the addresses of the receivers and 
     * the value of each index will contain the name of the receiver.
     * @return array An array that contains receivers information.
     * @since 1.0.2
     */
    public static function getBCC() {
        return self::createInstance()->_getSocketMailer()->getBCC();
    }
    /**
     * Returns a string that contains the names and the addresses 
     * of people who will receive a blind carbon copy of the message.
     * The format of the string will be as follows:
     * <p>NAME_1 &lt;ADDRESS_1&gt;, NAME_2 &lt;ADDRESS_2&gt; ...</p>
     * @return string A string that contains receivers information.
     * @since 1.0.3
     */
    public static function getBCCStr() {
        return self::createInstance()->_getSocketMailer()->getBCCStr();
    }
    /**
     * Returns an associative array that contains the names and the addresses 
     * of people who will receive a carbon copy of the message.
     * The indices of the array will act as the addresses of the receivers and 
     * the value of each index will contain the name of the receiver.
     * @return array An array that contains receivers information.
     * @since 1.0.2
     */
    public static function getCC() {
        return self::createInstance()->_getSocketMailer()->getCC();
    }
    /**
     * Returns a string that contains the names and the addresses 
     * of people who will receive a carbon copy of the message.
     * The format of the string will be as follows:
     * <p>NAME_1 &lt;ADDRESS_1&gt;, NAME_2 &lt;ADDRESS_2&gt; ...</p>
     * @return string A string that contains receivers information.
     * @since 1.0.3
     */
    public static function getCCStr() {
        return self::createInstance()->_getSocketMailer()->getCCStr();
    }
    /**
     * Returns an array that contains log messages which are generated 
     * from sending SMTP commands.
     * @return array The array will be indexed. In every index, there 
     * will be a sub-associative array with the following indices:
     * <ul>
     * <li>command</li>
     * <li>response-code</li>
     * <li>response-message</li>
     * </ul>
     * @since 1.0.4
     */
    public static function getLog() {
        if (self::$em !== null) {
            return self::$em->getSocketMailer()->getResponsesLog();
        }

        return self::$log;
    }
    /**
     * Returns an associative array that contains the names and the addresses 
     * of people who will receive an original copy of the message.
     * The indices of the array will act as the addresses of the receivers and 
     * the value of each index will contain the name of the receiver.
     * @return array An array that contains receivers information.
     * @since 1.0.2
     */
    public static function getReceivers() {
        return self::createInstance()->_getSocketMailer()->getReceivers();
    }
    /**
     * Returns a string that contains the names and the addresses 
     * of people who will receive an original copy of the message.
     * The format of the string will be as follows:
     * <p>NAME_1 &lt;ADDRESS_1&gt;, NAME_2 &lt;ADDRESS_2&gt; ...</p>
     * @return string A string that contains receivers information.
     * @since 1.0.3
     */
    public static function getReceiversStr() {
        return self::createInstance()->_getSocketMailer()->getReceiversStr();
    }
    /**
     * Returns the associated socket mailer object.
     * @return SocketMailer|null The method will return an 
     * object of type 'SocketMailer' if initialized and message 
     * is not yet sent. If the mailer is not initialized or the 
     * message is sent, the method will return null.
     * @since 1.0.4
     */
    public static function getSocketMailer() {
        return self::$em;
    }
    /**
     * Sets or gets the importance level of email message.
     * @param int $imp The importance level of the message. -1 for not urgent, 0 
     * for normal and 1 for urgent.
     * @return int The importance level of the message.
     * @since 1.0.1
     */
    public static function importance($imp = null) {
        if ($imp !== null) {
            self::createInstance()->_getSocketMailer()->setPriority($imp);
        }

        return self::createInstance()->_getSocketMailer()->getPriority();
    }
    /**
     * Adds a child HTML node to the body of the message.
     * @param HTMLNode $htmlNode An instance of 'HTMLNode'.
     * @since 1.0
     */
    public static function insertNode($htmlNode) {
        self::createInstance()->_getDocument()->addChild($htmlNode);
    }
    /**
     * Sends the message and set message instance to null.
     * @since 1.0
     */
    public static function send() {
        self::createInstance()->_sendMessage();
        self::$em = null;
    }
    /**
     * Sets the subject of the email message.
     * @param string $subject The subject of the email message.
     * @since 1.0
     */
    public static function subject($subject) {
        self::createInstance()->_getSocketMailer()->setSubject($subject);
    }
    /**
     * Adds a text node to the body of the message.
     * @param string $text The text that will be in the body of the node.
     * @since 1.0
     */
    public static function write($text) {
        self::createInstance()->_getDocument()->addChild(HTMLNode::createTextNode($text,false));
    }
    /**
     * 
     * @return HTMLDoc
     * @since 1.0
     */
    private function &_getDocument() {
        return $this->asHtml;
    }
    /**
     * 
     * @return SocketMailer
     * @since 1.0
     */
    private function &_getSocketMailer() {
        return $this->socketMailer;
    }
    /**
     * @since 1.0
     */
    private function _sendMessage() {
        $this->socketMailer->write($this->asHtml->toHTML(), true);
        self::$log = $this->socketMailer->getResponsesLog();
    }
    /**
     * 
     * @param HTMLDoc $doc
     */
    private function _setDocument($doc) {
        if ($doc instanceof HTMLDoc) {
            $this->asHtml = $doc;
        }
    }
}
