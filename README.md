[![Latest Stable Version](https://poser.pugx.org/jyokum/healthgraph/v/stable.png)](https://packagist.org/packages/jyokum/healthgraph)
[![Total Downloads](https://poser.pugx.org/jyokum/healthgraph/downloads.png)](https://packagist.org/packages/jyokum/healthgraph)
[![Build Status](https://travis-ci.org/jyokum/healthgraph.png?branch=master)](https://travis-ci.org/jyokum/healthgraph)
# Health Graph Client

The Health Graph Client is a PHP wrapper for the Health Graph API

### Installation via Composer

Add HealthGraph in your composer.json:

```js
{
    "require": {
        "jyokum/healthgraph": "*"
    }
}
```

Download the library:

``` bash
$ php composer.phar update
```

After installing, you need to require Composer's autoloader somewhere in your code:

```php
require_once 'vendor/autoload.php';
```

### Generating a Connect link/button

```php
require_once 'vendor/autoload.php';
use HealthGraph\Authorization;

$client_id = 'xxxxxxxxxxxxxxxxxxxxx';
$redirect_url = 'http://example.com/authorize';

// Link
$link = Authorization::getAuthorizationLink($client_id, $redirect_url);
echo "<a href='$link'>Connect</a>";

// Button
$button = Authorization::getAuthorizationButton($client_id, $redirect_url);
echo $button['html'];
```

### Obtain a token from an auth code

```php
require_once 'vendor/autoload.php';
use HealthGraph\Authorization;

$client_id = 'xxxxxxxxxxxxxxxxxxxxx';
$client_secret = 'xxxxxxxxxxxxxxxxxxxxx';
$redirect_url = 'http://example.com/authorize';

if (isset($_REQUEST['code'])) {
    $token = Authorization::authorize($_GET['code'], $client_id, $client_secret, $redirect_url);
    some_function_to_save_the_token($token);
}
```

### Revoke a token

```php
require_once 'vendor/autoload.php';
use HealthGraph\Authorization;

$token = some_function_to_retrieve_the_token();
Authorization::deauthorize($token->access_token);
```

### Retrieve data for a user

```php
require_once 'vendor/autoload.php';
use HealthGraph\HealthGraphClient;

$token = some_function_to_retrieve_the_token();

$hgc = HealthGraphClient::factory();
$hgc->getUser(array(
    'access_token' => $token['access_token'],
    'token_type' => $token['token_type'],
));

// get profile
$hgc->GetProfile();

// get settings
$hgc->GetSettings();

// get fitness activity list
$activities = $hgc->FitnessActivityFeed()->getAll();
```

### Update profile

```php
$hgc = HealthGraphClient::factory();
$hgc->getUser(array(
    'access_token' => $token['access_token'],
    'token_type' => $token['token_type'],
));
$data = array('athlete_type' => 'Athlete');
$hgc->UpdateProfile($data);
```

### Add a new fitness (cardio) activity

```php
$hgc = HealthGraphClient::factory();
$hgc->getUser(array(
    'access_token' => $token['access_token'],
    'token_type' => $token['token_type'],
));
$command = $hgc->getCommand('NewFitnessActivity', array(
    "type" => "Running",
    "start_time" => "Sat, 1 Jan 2011 00:00:00",
    "duration" => 600,
    "total_distance" => 1000,
    "notes" => "Ran 1000 meters in 600 seconds"
));
$result = $command->execute();
```