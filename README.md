[![Build Status](https://travis-ci.org/[YOUR_GITHUB_USERNAME]/[YOUR_PROJECT_NAME].png?branch=master)](https://travis-ci.org/jyokum/healthgraph)

# Health Graph API

Guzzle client for interacting with the Health Graph API by RunKeeper

### Installation via Composer

Add HealthGraph in your composer.json:

    {
        "require": {
            "jyokum/healthgraph": "*"
        }
    }

Download the library:

    $ php composer.phar update

After installing, you need to require Composer's autoloader somewhere in your code:

    require_once 'vendor/autoload.php';


### Generating a Connect link/button

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

### Obtain a token from an auth code

    require_once 'vendor/autoload.php';
    use HealthGraph\Authorization;
    
    $client_id = 'xxxxxxxxxxxxxxxxxxxxx';
    $client_secret = 'xxxxxxxxxxxxxxxxxxxxx';
    $redirect_url = 'http://example.com/authorize';
    
    if (isset($_REQUEST['code'])) {
        $token = Authorization::authorize($_GET['code'], $client_id, $client_secret, $redirect_url);
        some_function_to_save_the_token($token);
    }

### Revoke a token

    require_once 'vendor/autoload.php';
    use HealthGraph\Authorization;
    
    $token = some_function_to_retrieve_the_token();
    Authorization::deauthorize($token->access_token);

### Retrieve data for a user

    require_once 'vendor/autoload.php';
    use HealthGraph\HealthGraphClient;
    
    $token = some_function_to_retrieve_the_token();
    
    $hgc = HealthGraphClient::factory(array(
        'access_token' => $token['access_token'],
        'token_type' => $token['token_type'],
    ));
    
    // get profile
    $hgc->profile();
    
    // get fitness activity list
    $activities = $hgc->FitnessActivityFeed()->getAll();
