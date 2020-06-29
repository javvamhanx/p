<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Facebook
{
    public $access_token = null;
    public $is_shielded = null;

    public function requestShield() {
        $client = new Client;
        $check = json_decode(file_get_contents('https://graph.facebook.com/v3.0/me?fields=id&access_token=' . $this->access_token), true);
        if (isset($check['id'])) {
            try {
                $response = $client->request('POST', 'https://graph.facebook.com/graphql', [
                    'form_params' => [
                        'variables' => '{"input":{"client_mutation_id":"1","actor_id":"' . $check['id'] . '","is_shielded":' . $this->is_shielded . '}}',
                        'doc_id' => '1205255906269260',
                        'access_token' => $this->access_token
                    ],
                    'http_errors' => false
                ]);
                if ($response->getStatusCode() == 200) {
                    $result = json_decode($response->getBody(), true);
                    if (isset($result['data']['is_shielded_set']['is_shielded']))
                        return [
                            'success' => true,
                            'is_shielded' => $result['data']['is_shielded_set']['is_shielded']
                        ];
                }
            } catch (ClientException $ce) {
                return ['success' => false];
            }
        }
        return ['success' => false];
    }
}