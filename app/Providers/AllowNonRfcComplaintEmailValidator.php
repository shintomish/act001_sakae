<?php

namespace App\Providers;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\EmailValidation;

class AllowNonRfcComplaintEmailValidator extends EmailValidator
{
    /**
     * 最低限、@が含まれていて、ドメインとアカウント名が含まれているメールアドレスは許可します
     * @param string          $email
     * @param EmailValidation $emailValidation
     * @return bool
     */
    public function isValid($email, EmailValidation $emailValidation): bool
    {
        // warningsとerrorのプロパティを埋める
        parent::isValid($email, $emailValidation);

        if (substr_count($email, '@') !== 1) {
            return false;
        }

        list($account, $domain) = explode('@', $email);

        if (strlen($account) > 0 && strlen($domain) > 0) {
            return true;
        }

        return false;
    }
}