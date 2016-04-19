Need help? I use this codebase to support clients with CRM &rarr; ESP import projects. Contact me via <a href="http://clearbold.com">clearbold.com</a> for project work. Please report bugs or how-to questions via Issues.

#### Will this automatically import my CRM data into my Campaign Monitor subscriber lists?

No.

Here's what it **will** do:

* Provide you with a secure user interface to view your lists and run imports that you've written code for.
* Provide list import functions for Campaign Monitor, as long as your code's output matches the target schema.
* Provide a URL for each list import that you can schedule via cron.
* Allow you to focus on writing a custom class for your CRM system, where you'll handle fetching the API data and mapping it to the target schema.

### Getting Started

You'll need to run `composer update` to install the app's dependencies. The app is built using [Slim Framework v3](http://slimframework.com).

### Authentication

Username and password values are stored in plaintext in `settings.php`.

### Creating CRM Classes

Create a new class file in `classes`. Reference `GenericCrm.php` for an example, making sure that your new class implements the `Crm` interface.

You'll need to fill in the `getListToImport` function, which will be passed your Campaign Monitor `ListID`. The assumption is that you'll write code for each `ListID`, including any variables unique to that list. You may extend your class with additional functions for each list.

Each active list on the console features a *Show JSON* button that will reveal the target schema for that list, including any custom fields.

All classes created must use namespace `Crm2Esp`. See `GenericCrm.php` for an example.

### Configuration

Do not edit the default settings in `src/settings.php`. Reference those defaults and set your own variables in `storage/config/settings.php`.

You will need to create the `storage/config` directory, and the `storage/config/settings.php` file as these are excluded from the git repository. Your `settings.php` file should look like:

```
<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // default is false, set to false in production

        'cm' => [
            'clientId' => '[Your API ID]',
            'clientApiKey' => '[Your API Key]',
            'subscriberLists' => [
                '[Your List ID]',
                '[Your List ID]'
            ]
        ],

        'crm' => [
            // Any values you store here will be available in your code
        ],

        'provider' => '\Crm2Esp\GenericCrm',

        'auth' => [
            'user' => 'password'
        ]
    ],
];
```

Only those lists whose IDs are stored in your settings will be enabled on the console. Other lists in your account will be displayed so that you can easily fetch their IDs.

Anything you put in the `cm` or `crm` arrays in your settings are available via the application's routes and passed to your classes as `$this->cm` or `$this->crm`. See `GenericCrm` for examples. The application requires valid values for `clientId`, `clientApiKey`, and at least one `subscriberLists` ID.

Update the `provider` value in your settings to match the `ClassName` of your `classes/[ClassName].php` file, where you'll do custom work for your CRM.

### TODO

* [ ] Log recent (last 5, 10?) import details, display per list
* [ ] If no CM API key is set, raise an error
