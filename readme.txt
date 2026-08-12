sed -i "s+\ClientConfig+\Models\\\ClientConfig+g" content/promo.blade.php


// custom funds

1. Create client directory by copying from another client directory
2. app/CLIENT
   2.a Create client directory in app -
   2.b app/CLIENT/CLIENTStatement.php
3. public/css/client
    3.a
4. Fund.getStatementBalance()
4. FundStatementController.apiFundStatement()




php artisan make:mail ResetPassword
php artisan make:mail PasswordUpdated


1. Update .env for mail
2. Update AuthTrait.php

#MAIL_USERNAME=oyepages
#MAIL_PASSWORD=oyelok123!


php artisan make:model LogActivity



1. activity log
package
https://github.com/spatie/laravel-activitylog/blob/master/migrations/create_activity_log_table.php.stub
custom log
https://www.itsolutionstuff.com/post/custom-user-log-activity-in-laravel-5-app-exampleexample.html


integer(id)
string('name')->nullable() - 64
string('action') - 64
string('url')->nullable() - 128
string('description')->nullable()  - 255
string('target_string_id')->nullable() => backward (fund id, etc which are string)
integer('target_id')->nullable()
string('target_type') - 64
text / json('data')->nullable()
string('ip')->nullable() - 64
string('agent')->nullable() - 255
integer('auth_user_id')->nullable()
timestamps('created_on')
timestamps('updated_on')


index
 - name
 - user_id
 - target_id, target_type
 - created_at


https://stackoverflow.com/questions/31539727/laravel-password-validation-rule