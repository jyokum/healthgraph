# Health Graph API

PHP class for interacting with the Health Graph API by RunKeeper


### Generating a Connect link/button

    $client_id = 'xxxxxxxxxxxxxxxxxxxxx';
    $client_secret = 'xxxxxxxxxxxxxxxxxxxxx';
    $redirect_url = 'http://example.com/authorize';
    
    require 'lib/healthgraph.php'
    
    // Link
    $link = \HealthGraph\Client::getAuthorizationLink($client_id, $redirect_url);
    echo "<a href='$link'>Connect</a>";
    
    // Button
    $button = \HealthGraph\Client::getAuthorizationButton($client_id, $redirect_url);
    echo $button['html'];

### Obtain a token from an auth code

    $client_id = 'xxxxxxxxxxxxxxxxxxxxx';
    $client_secret = 'xxxxxxxxxxxxxxxxxxxxx';
    $redirect_url = 'http://example.com/authorize';

    require 'lib/healthgraph.php'
    
    if (isset($_REQUEST['code'])) {
        $token = \HealthGraph\Authorization::authorize($_REQUEST['code'], $client_id, $client_secret, $auth_url);
        some_function_to_save_the_token($token);
    }

### Revoke a token

    require 'lib/healthgraph.php'
    $token = some_function_to_retrieve_the_token();
    \HealthGraph\Authorization::deauthorize($_SESSION['token']->access_token);

### Retrieve data for a user

    require 'lib/healthgraph.php'
    $token = some_function_to_retrieve_the_token();
    $hgu = new \HealthGraph\User($token->access_token);
    
    // get profile
    $hgu->profile();
    
    // get fitness activity list
    $list = $hgu->fitness_activities()->items();
    
    // get detail for a fitness activity
    $activity = $list[0]->uri;
    $detail = $hgu->fitness_activities()->detail($activity);