<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\MailingText;
use App\Models\SettingUser;
use App\Models\Setting;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        //$country_id = ($input['country'] != '') ? $input['country'] : 1;
        $country_id = 1;

        $user = User::create([
            'name' => $input['name'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 4,
            'country' => $country_id
        ]);

        // Free coins on register?
        $coins = coins_on_register();
        if(floatval($coins) > 0) {
            User::set_free_coins($user->id, $coins);
        }

        $template = MailingText::template('Signup', App::getLocale());

        if(!empty($template)) {

            $user    = User::find($user->id);
            $subject = replace_variables($template->name, $user->id);
            $content = replace_variables($template->description, $user->id);
            $name    = $user->name . ' ' . $user->lastname;
            $email   = $user->email;

            Mail::send('mails.template', ['content' => $content, 'align' => 'center', 'password' => $input['password'], 'email' => $input['email'], 'link' => env('APP_URL').'/login' , 'link_text' => 'Log in' ], function ($mail) use ($email, $name, $subject) {
                $mail->from(env('APP_EMAIL'), env('APP_NAME'));
                $mail->to($email, $name)->subject($subject);
            });
        }

        $options = Setting::all();
        if (!empty($options->toArray())) {
            foreach ($options as $index => $option) {
                SettingUser::updateOrCreate(
                    ['user' => $user->id, 'option' => $option->id],
                    ['value' => 1]
                );
            }
        }

        return $user;
    }
}
