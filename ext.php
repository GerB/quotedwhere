<?php

namespace ger\quotedwhere;

class ext extends \phpbb\extension\base
{

    // Check availabillity of PHP functions
    public function is_enableable()
    {
        if (!class_exists("\DOMXPath") || !class_exists("\DOMDocument"))
        {
            $user = $this->container->get('user');
            $user->add_lang_ext('ger/quotedwhere', 'common');
            trigger_error($user->lang('QW_MISSING_REQUIREMENTS'), E_USER_WARNING);
        }
        return true;
    }

}
