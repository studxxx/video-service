<?php
declare(strict_types=1);

namespace Api\Infrastructure\Model\OAuth\Entity;

use Api\Model\OAuth\Entity\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    /** @var array */
    private $clients;

    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    public function getClientEntity($clientIdentifier): ClientEntityInterface
    {
        $client = new ClientEntity($clientIdentifier);
        $client->setName($this->clients[$clientIdentifier]['name']);
        $client->setRedirectUri($this->clients[$clientIdentifier]['redirect_uri']);

        return $client;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        if (array_key_exists($clientIdentifier, $this->clients) === false) {
            return false;
        }

        if (
            $this->clients[$clientIdentifier]['is_confidential'] === true
            && \password_verify($clientSecret, $this->clients[$clientIdentifier]['secret']) === false
        ) {
            return false;
        }

        return true;
    }
}
