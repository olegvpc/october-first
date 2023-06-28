<?php

namespace Olegvpc\Contact\Components;

use Validator;
use Cms\Classes\ComponentBase;
use Olegvpc\Contact\Models\Contact;
use October\Rain\Exception\ValidationException;

class ContactForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Contact Form',
            'description' => 'Contact Form for Sumbiting'
        ];
    }
    public function onSubmit()
    {
        // Get request data
        $data = \Input::only([
            'first_name',
            'last_name',
            'email',
            'content'
        ]);
        // var_dump($data);

        // Validate request
        $validated = $this->validate($data);

        // Send email
        $receiver = 'olegvpc@yandex.com';
        // var_dump($validated['first_name']);
        $contact = new Contact;
        $contact->first_name = $validated['first_name'];
        $contact->last_name = $validated['last_name'];
        $contact->email = $validated['email'];
        $contact->content = $validated['content'];

        $contact->save();
        // \Mail::send('olegvpc.contact::contact', $data, function ($message) use ($receiver) {
        //     $message->to($receiver);
        // });
    }

    protected function validate(array $data)
    {
        // Validate request
        $rules = [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email',
            'content' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            return $data;
        }
    }


}
