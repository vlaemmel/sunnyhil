TEST



{def $fields = ezini( 'FormSettings', 'AvailableFields', 'ezcomments.ini' )}

{$fields|debug}


{def $bypass_captcha = fetch( 'comment', 'has_access_to_security', hash( 'limitation', 'AntiSpam',
                                                                           'option_value', 'bypass_captcha' ) )}

{cond($bypass_captcha, 1, 2)|debug}