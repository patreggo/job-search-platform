<?php

namespace App\Traits;

use Admin\Entity\Admin;
use App\Entity\User;

trait SystemUserTrait
{
    /**
     * get system user
     * @return Admin
     */
    public function getSystemUser(): Admin
    {
        $systemUser = new Admin();
        $systemUser->setEmail(Admin::SYSTEM_USER_MAIL);
        $systemUser->setId(Admin::SYSTEM_USER_ID);
        return $systemUser;
    }

    /**
     * @param object|null $object
     * @return Admin|User
     */
    public function getUser(?object $object = null): Admin|User
    {
        if ($object !== null && method_exists($object, 'getUser')) {
            return $object->getUser();
        }
            return $this->getSystemUser();
    }
}
