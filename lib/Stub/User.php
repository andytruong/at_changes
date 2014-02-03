<?php
namespace Drupal\changes\Stub;

class User {
    /**
     * Get account object from author string — First Last <username@domain.com>
     *
     * @param string $author
     * @return \stdClass
     * @throws \Exception
     */
    public function account($author) {
        preg_match('/(.+) <(.+)>$/', $author, $matches);
        if (!$matches || 3 !== count($matches)) {
            throw new \Exception('Wrong author format');
        }

        list($full, $name, $mail) = $matches;
        if ($account = user_load_by_mail($mail)) {
            return $account;
        }

        if ($account = user_load_by_name($name)) {
            return $account;
        }

        return $this->initAccount($name, $mail);
    }

    private function initAccount($name, $mail) {
        $account = new \stdClass();
        $account->name = $name;
        $account->mail = $mail;
        $account->pass = user_password();
        $account->status = 1;
        user_save($account);
        return $account;
    }
}
