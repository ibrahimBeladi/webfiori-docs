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
namespace webfiori\logic;

use webfiori\conf\MailConfig;
use webfiori\entity\FileHandler;
use webfiori\entity\mail\SMTPAccount;
use webfiori\entity\mail\SocketMailer;
use webfiori\entity\File;
/**
 * A class for the methods that is related to mailing.
 * The controller is responsible for controlling the following operations:
 * <ul>
 * <li>Adding, removing and updating SMTP connections in the class 'MailConfig' programmatically.</li>
 * <li>Creating the class 'MailConfig' if it does not exist.</li>
 * </ul>  
 * @author Ibrahim
 * @version 1.3.1
 */
class EmailController extends Controller {
    /**
     * A constant that indicates the given username or password  
     * is invalid.
     * @since 1.1
     */
    const INV_CREDENTIALS = 'inv_username_or_pass';
    /**
     * A constant that indicates a mail server address or its port 
     * is invalid.
     * @since 1.1
     */
    const INV_HOST_OR_PORT = 'inv_mail_host_or_port';
    /**
     *
     * @var EmailController 
     * @since 1.0
     */
    private static $instance;
    /**
     * Creates the file 'MailConfig.php' if it does not exist.
     * @since 1.0
     */
    public function createEmailConfigFile() {
        if (!class_exists('webfiori\conf\MailConfig')) {
            $this->writeMailConfig([]);
        }
    }
    /**
     * Returns a singleton of the class.
     * @return EmailController
     * @since 1.0
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new EmailController();
        }

        return self::$instance;
    }
    /**
     * Returns a new instance of the class SocketMailer.
     * The method will try to establish a connection to SMTP server using 
     * the given SMTP account.
     * @param SMTPAccount $emailAcc An account that is used to initiate 
     * socket mailer.
     * @return SocketMailer|string The method will return an instance of SocketMailer
     * on successful connection. If no connection is established, the method will 
     * return MailFunctions::INV_HOST_OR_PORT. If user authentication fails, 
     * the method will return 'MailFunctions::INV_CREDENTIALS'.
     * @since 1.0
     */
    public function getSocketMailer($emailAcc) {
        if ($emailAcc instanceof SMTPAccount) {
            $retVal = EmailController::INV_HOST_OR_PORT;
            $m = new SocketMailer();

            $m->setHost($emailAcc->getServerAddress());
            $m->setPort($emailAcc->getPort());

            if ($m->connect()) {
                $m->setSender($emailAcc->getSenderName(), $emailAcc->getAddress());

                if ($m->login($emailAcc->getUsername(), $emailAcc->getPassword())) {
                    $retVal = $m;
                } else {
                    $retVal = EmailController::INV_CREDENTIALS;
                }
            }

            return $retVal;
        }

        return false;
    }
    /**
     * Removes SMTP email account if it is exist.
     * @param string $accountName The name of the email account (such as 'no-replay').
     * @return boolean If the account is not exist or the class 'MailConfig' 
     * does not exist, the method will return false. If the account was removed, 
     * The method will return true.
     * @since 1.3
     */
    public function removeAccount($accountName) {
        $retVal = false;

        if (class_exists('webfiori\conf\MailConfig')) {
            $account = MailConfig::getAccount($accountName);

            if ($account instanceof SMTPAccount) {
                $accountsArr = MailConfig::getAccounts();
                unset($accountsArr[$accountName]);
                $toSave = [];

                foreach ($accountsArr as $account) {
                    $toSave[] = $account;
                }
                $this->writeMailConfig($toSave);
                $retVal = true;
            }
        }

        return $retVal;
    }
    /**
     * Adds new SMTP account or Updates an existing one.
     * Note that the connection will be added or updated only if it 
     * has correct information.
     * @param SMTPAccount $emailAccount An instance of 'SMTPAccount'.
     * @return boolean|string The method will return true if the email 
     * account was updated or added. If the email account contains wrong server
     *  information, the method will return MailFunctions::INV_HOST_OR_PORT. 
     * If the given email account contains wrong login info, the method will 
     * return MailFunctions::INV_CREDENTIALS. Other than that, the method 
     * will return false.
     * @since 1.1
     */
    public function updateOrAddEmailAccount($emailAccount) {
        $retVal = false;

        if ($emailAccount instanceof SMTPAccount) {
            $sm = $this->getSocketMailer($emailAccount);

            if ($sm instanceof SocketMailer) {
                if (class_exists('webfiori\conf\MailConfig')) {
                    $accountsArr = MailConfig::getAccounts();
                    $accountsArr[$emailAccount->getSenderName()] = $emailAccount;
                    $toSave = [];

                    foreach ($accountsArr as $account) {
                        $toSave[] = $account;
                    }
                    $this->writeMailConfig($toSave);
                } else {
                    $arr = [$emailAccount];
                    $this->writeMailConfig($arr);
                }
                $retVal = true;
            }
            $retVal = $sm;
        }

        return $retVal;
    }
    /**
     * Initialize new session or use an existing one.
     * Note that the name of the session must be 'wf-session' in 
     * order to initialize it.
     * @param array $options An array of session options. See 
     * Controller::useSettion() for more information about available options.
     * @return boolean If session is created or resumed, the method will 
     * return true. False otherwise.
     * @since 1.3.1
     */
    public function useSession($options = []) {
        if (gettype($options) == 'array' && isset($options['name']) && $options['name'] == 'wf-session') {
            return parent::useSession($options);
        }

        return false;
    }
    /**
     * A method to save changes to mail configuration file.
     * @param array $emailAccountsArr An associative array that contains an objects of 
     * type 'SMTPAccount'. The indices of the array are the names of the accounts.
     * @since 1.1
     */
    private function writeMailConfig($emailAccountsArr) {
        $fileData = ""
                . "<?php\n"
                . "namespace webfiori\\conf;\n"
                . "\n"
                . "use webfiori\\entity\\mail\\SMTPAccount;\n"
                . "/**\n"
                . " * SMTP configuration class.\n"
                . " * The developer can create multiple SMTP accounts and add\n"
                . " * Connection information inside the body of this class.\n"
                . " * @author Ibrahim\n"
                . " * @version 1.0.1\n"
                . " */\n"
                . "class MailConfig {\n"
                . "    private \$emailAccounts;\n"
                . "    /**\n"
                . "     *\n"
                . "     * @var MailConfig\n"
                . "     * @since 1.0\n"
                . "     */\n"
                . "    private static \$inst;\n"
                . "    private function __construct() {\n";
        $index = 0;
        foreach ($emailAccountsArr as $emailAcc) {
            $fileData .= ""
                    . "        \$acc$index = new SMTPAccount([\n"
                    . "            'server-address' => '".$emailAcc->getServerAddress()."',\n"
                    . "            'port' => ".$emailAcc->getPort().",\n"
                    . "            'user' => '".$emailAcc->getUsername()."',\n"
                    . "            'pass' => '".$emailAcc->getPassword()."',\n"
                    . "            'sender-name' => '".$emailAcc->getSenderName()."',\n"
                    . "            'sender-address' => '".$emailAcc->getAddress()."',\n"
                    . "            'account-name' => '".$emailAcc->getAccountName()."'\n"
                    . "        ]);\n"
                    . "        \$this->addAccount(\$acc$index, '".$emailAcc->getAccountName()."');\n"
                    . "        \n";
            $index++;
        }
        $fileData .= "    }\n"
                . "    /**\n"
                . "     * Adds new SMTP connection information or updates an existing one.\n"
                . "     * @param string \$accName The name of the account that will be added or updated.\n"
                . "     * @param SMTPAccount \$smtpConnInfo An object of type 'SMTPAccount' that\n"
                . "     * will contain SMTP account information.\n"
                . "     * @since 1.0.1\n"
                . "     */\n"
                . "    public static function addSMTPAccount(\$accName, \$smtpConnInfo) {\n"
                . "        if (\$smtpConnInfo instanceof SMTPAccount) {\n"
                . "            \$trimmedName = trim(\$accName);\n"
                . "            \n"
                . "            if (strlen(\$trimmedName) != 0) {\n"
                . "                self::get()->addAccount(\$smtpConnInfo, \$trimmedName);\n"
                . "            }\n"
                . "        }\n"
                . "    }\n";
        $fileData .= ""
                . "    /**\n"
                . "     * Return a single instance of the class.\n"
                . "     * Calling this method multiple times will result in returning\n"
                . "     * the same instance every time.\n"
                . "     * @return MailConfig\n"
                . "     * @since 1.0\n"
                . "     */\n"
                . "    public static function get() {\n"
                . "        \n"
                . "        if (self::\$inst === null) {\n"
                . "            self::\$inst = new MailConfig();\n"
                . "        }\n"
                . "        \n"
                . "        return self::\$inst;\n"
                . "    }\n";
        $fileData .= ""
                . "    /**\n"
                . "     * Returns an email account given its name.\n"
                . "     * The method will search for an account with the given name in the set\n"
                . "     * of added accounts. If no account was found, null is returned.v"
                . "     * @param string \$name The name of the account.\n"
                . "     * @return SMTPAccount|null If the account is found, The method\n"
                . "     * will return an object of type SMTPAccount. Else, the\n"
                . "     * method will return null.\n"
                . "     * @since 1.0\n"
                . "     */\n"
                . "    public static function getAccount(\$name) {\n"
                . "        return self::get()->_getAccount(\$name);\n"
                . "    }\n";
        $fileData .= ""
                . "    /**\n"
                . "     * Returns an associative array that contains all email accounts.\n"
                . "     * The indices of the array will act as the names of the accounts.\n"
                . "     * The value of the index will be an object of type EmailAccount.\n"
                . "     * @return array An associative array that contains all email accounts.\n"
                . "     * @since 1.0\n"
                . "     */\n"
                . "    public static function getAccounts() {\n"
                . "        return self::get()->_getAccounts();\n"
                . "    }\n"
                . "    private function _getAccount(\$name) {\n"
                . "        if (isset(\$this->emailAccounts[\$name])) {\n"
                . "            return \$this->emailAccounts[\$name];\n"
                . "        }\n"
                . "        \n"
                . "        return null;\n"
                . "    }\n"
                . "    private function _getAccounts() {\n"
                . "        return \$this->emailAccounts;\n"
                . "    }\n";
        $fileData .= ""
                . "    /**\n"
                . "     * Adds an email account.\n"
                . "     * The developer can use this method to add new account during runtime.\n"
                . "     * The account will be removed once the program finishes.\n"
                . "     * @param SMTPAccount \$acc an object of type SMTPAccount.\n"
                . "     * @param string \$name A name to associate with the email account.\n"
                . "     * @since 1.0\n"
                . "     */\n"
                . "    private function addAccount(\$acc,\$name) {\n"
                . "        \$this->emailAccounts[\$name] = \$acc;\n"
                . "    }\n";
        //End of class
        $fileData .= "}\n";
        $mailConfigFile = new File('MailConfig.php', ROOT_DIR.DIRECTORY_SEPARATOR.'conf');
        $mailConfigFile->remove();
        $mailConfigFile->setRawData($fileData);
        $mailConfigFile->write(false, true);
    }
}
