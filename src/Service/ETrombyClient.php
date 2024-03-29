<?php

namespace Connected\ETrombyBundle\Service;

use Exception;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;


class ETrombyClient implements ETrombyClientInterface
{

    /** @var Client */
    protected $guzzleClient;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ETrombyClient constructor.
     *
     * @param string $apiUrl
     * @param string $apiKey
     * @param LoggerInterface $logger
     */
    public function __construct(string $apiUrl, string $apiKey, LoggerInterface $logger)
    {
        $this->guzzleClient = new Client([
            'base_uri' => $apiUrl,
            'headers' => [
                'X-TRB-API-KEY' => $apiKey
            ]
        ]);
        $this->logger = $logger;
    }

    /**
     * @param string $ldap
     *
     * @return array|null
     */
    public function getPerson(string $ldap): ?array
    {
        try {
            try {
                $response = $this->guzzleClient->get('usersByLdap/'.$ldap);
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
                return null;
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    /**
     * @param string $email
     *
     * @return string|null
     */
    public function getSignatureByEmail(string $email): ?string
    {
        try {
            try {
                $response = $this->guzzleClient->get('/users/signature_by_mail/'. $email);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                return null;
            }

            return json_decode($response->getBody()->getContents())->view;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    /**
     * @param string $ldap
     * @return string|null
     */
    public function getSignatureByLdap(
        string $ldap,
        ?int $forcedGroupId = null,
        ?int $forcedDirectionId = null,
        ?int $forcedEntiteId = null
    ): ?string
    {
        try {
            try {
                if ($forcedGroupId !== null) {
                    $response = $this->guzzleClient->get('/users/signature/'. $ldap . '/groupe/' . $forcedGroupId);
                } elseif ($forcedDirectionId !== null) {
                    $response = $this->guzzleClient->get('/users/signature/'. $ldap . '/direction/' . $forcedGroupId);
                } elseif ($forcedEntiteId !== null) {
                    $response = $this->guzzleClient->get('/users/signature/'. $ldap . '/entite/' . $forcedGroupId);
                } else {
                    $response = $this->guzzleClient->get('/users/signature_by_ldap/'. $ldap);
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                return null;
            }

            return json_decode($response->getBody()->getContents())->view;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    /**
     * @param array $params
     * @return array|null
     */
    public function search(array $params): ?array
    {
        try {
            try {
                $response = $this->guzzleClient->post('users/search', [
                    'body' => json_encode($params)
                ]);
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());

                return null;
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return null;
        }
    }
}