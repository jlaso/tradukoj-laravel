# Instalation

Add the package in the main ```composer.json```

```json
  { 
     "require": 
     {
        ...,
	    "jlaso/tradukoj-laravel": "dev-master",
	    ...
     }
  }
```

Launch ```composer update``` in order to get **TradukojLaravel** installed.

Launch the command ```php artisan vendor:publish``` to get the configuration of the package exposed and edit the
file _app/config/tradukoj.php_ adding the data of your project in the user area of [**Tradukoj**](https://www.tradukoj.com).

![Data project in the user area](https://raw.githubusercontent.com/jlaso/tradukoj-laravel/master/doc/images/user-area-project-detail.png)

```php
<?php

return array(

    'key' => 'put-your-api-key-here',
    'secret' => 'put-your-api-secret-here',
    'project_id' => 0,
    'url' => 'https://www.tradukoj.com/api/',

);
```

# Using

Once you have configured your parameters you can use the synchronization with the **Tradukoj** server.

To upload the first time all the translations you have in local to the server:

```bash
php artisan tradukoj:sync --upload-first
```

If you use this option the subsequents times you can lost your translations' changes in the server. **Remember this**

To download all the translations from the server to local:

```bash
php artisan tradukoj:sync 
```

There is no way at this moment to full synchronize translations full-duplex, because of the date of the file where the translations
are in local. There is no easy way to figure out which translations are modified in local. 


# Troubleshooting

If you found any troubles using the commands of this packages remember the option _debug_ that helps you to figure out whats
going on.

```bash
php artisan tradukoj:sync --debug
```


# Author and collaborators

* Joseluis Laso (aka Patrick)  <jlaso@joseluislaso.es>


References

[JoseluisLaso]: (http://www.joseluislaso.es)
