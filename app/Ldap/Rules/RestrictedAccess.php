<?php

namespace App\Ldap\Rules;

use LdapRecord\Laravel\Auth\Rule;
use LdapRecord\Models\ActiveDirectory\Group;

class RestrictedAccess extends Rule
{
    /**
     * Check if the rule passes validation.
     *
     * @return bool
     */
    public function isValid()
    {
        if ( env('APP_ENV') == 'production')
        {
            return $this->user->groups()->recursive()->contains(
            [
                Group::find('CN=Reportes_E,CN=Users,DC=argentina,DC=FSM,DC=CORP'),
                Group::find('CN=Reportes_L,CN=Users,DC=argentina,DC=FSM,DC=CORP'),
                Group::find('CN=Reportes_A,CN=Users,DC=argentina,DC=FSM,DC=CORP'),
            ]);
        }
        else
        {
            return $this->user->groups()->recursive()->contains(
            [
                Group::find('CN=Reportes_E,CN=Users,DC=argentina,DC=FSM,DC=CORP'),
                Group::find('CN=Reportes_L,CN=Users,DC=argentina,DC=FSM,DC=CORP'),
                Group::find('CN=Reportes_A,CN=Users,DC=argentina,DC=FSM,DC=CORP'),
            ]);
        }
    }
}
