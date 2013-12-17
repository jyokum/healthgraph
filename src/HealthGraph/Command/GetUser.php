<?php

namespace HealthGraph\Command;

use Guzzle\Service\Command\AbstractCommand;

class GetUser extends AbstractCommand
{

    protected function build()
    {
        $this->getClient()->setDefaultOption(
            'headers/Authorization',
            $this->data['token_type'] . ' ' . $this->data['access_token']
        );
        $this->request = $this->client->get(
            '/user',
            array('Content-Type' => 'application/vnd.com.runkeeper.User+json')
        );
    }

    public function execute()
    {
        $result = parent::execute();
        $config = $this->client->getConfig();
        foreach ($result as $key => $value) {
            $config->set('hg.' . $key, $value);
        }
        return $result;
    }
}
