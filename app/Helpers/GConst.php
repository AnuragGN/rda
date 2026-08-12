<?php
/**
 * Created by PhpStorm.
 * User: AlkeshKumar
 * Date: 11/14/2017
 * Time: 8:40 PM
 */

namespace App\Helpers;


class GConst
{
    // super session for admin
    const SUPER_SESSION = 'super_session_role';
    const SUPER_SESSION_ADMIN = 'admin';
    const SUPER_SESSION_CONTACT_ID = 'super_session_contact_id';

    // session for all user
    const SESSION_ROLE = 'session_role';
    const SESSION_ROLE_DONOR = 'donor';
    const SESSION_ROLE_AGENCY = 'agency';
    const SESSION_ROLE_SUPPORT_STAFF = 'support_staff';
    const SESSION_ROLE_GRANTEE = 'grantee';
    const SESSION_ROLE_ADMIN = 'admin';
    const SESSION_ROLE_DAF = 'daf';

    const SESSION_CONTACT_ID = 'session_contact_id';
    const SESSION_USERNAME_ONCE = 'session_username_once';
    const CART_RECOMMENDATION_TICKET = 'cart recommendation';
    const GRANT_RECOMMENDATION_TICKET = 'grant recommendation';
    const TICKET_COMMENT = 'comment';
    const DEFAULT_TICKET_TYPE = 'raise cash';
    const DEFAULT_TICKET_STATUS = 'open';
    const DEFAULT_TICKET_PRIORITY = 'high';
    const TEST_EMAIL_IDS = 'rajanktiwari@giftingnetwork.com';

    const CIRCLE_PUBLIC = 'public';
    const CIRCLE_DONOR = 'donor';
    const CIRCLE_ADVISOR = 'advisor';
    const CIRCLE_STAFF = 'staff';
    const ADVISOR_REGISTRATION = 'advisor registration';


    # Notification Type
    const BELL_NOTIFICATION_TYPE_NAME = 'Bell Notification';
    const TOAST_NOTIFICATION_TYPE_NAME = 'Toast Notification';

    const BELL_NOTIFICATION_TYPE_VAL = 1;
    const TOAST_NOTIFICATION_TYPE_VAL = 2;
    #End

    # Notification Disappear
    
    const AUTOMATIC_NOTIFICATION_DISAPPEAR_NAME = 'Automatic';  
    const MANUAL_NOTIFICATION_DISAPPEAR_NAME = 'Manual'; 

    const AUTOMATIC_NOTIFICATION_DISAPPEAR_VAL = 1; # 1=> Automatic
    const MANUAL_NOTIFICATION_DISAPPEAR_VAL = 0;  # 0=> Manual
    #End

    # Default Value for Notification
    const DEFAULT_NOTIFICATION_TYPE = ["1","2"];  # 1=> Bell Notification, 2=> Toast Notification
    const DEFAULT_NOTIFICATION_DISAPPEAR = 1; 
    const DEFAULT_NOTIFICATION_DISAPPEAR_SECS = 5;  # in Sec(s)
    #End
    
    // moved to client config as DATA_NOT_FOUND
    // const M_DATA_NOT_FOUND = "It looks like you have nothing here!";

    const M_USER_EMAIL_NOT_FOUND = "We can't find any user with this e-mail address!";
    const M_FORGOT_PASSWORD_SUCCESS = "We have e-mailed your password reset link.";
    const M_RESET_PASSWORD_SUCCESS = "Your password has been saved. Please login with your new password.";
    const M_RESET_PASSWORD_BAD_LINK = "Your password reset link is invalid or expired!";
    const M_PAGE_NOT_FOUND = "The requested page is not available.";
    const M_CHANGE_PASSWORD_SUCCESS = "Your password has been updated.";

    // 'reset' => 'Your password has been reset!',
    // 'sent' => 'We have e-mailed your password reset link!',
    // 'token' => 'This password reset token is invalid.',
    // 'user' => "We can't find a user with that e-mail address.",
    // 'failed' => 'These credentials do not match our records.',

    const M_REGISTER_DAF_BAD_LINK = "Your account activation link is invalid or expired!";

    const M_EMULATION_MODE = "Data change is not allowed in Emulation Mode";
}