<?php declare(strict_types=1);

namespace Connected\ETrombyBundle\Service;

interface ETrombyClientInterface
{
    public function getPerson(string $ldap): ?array;

    public function getSignatureByEmail(string $email): ?string;

    public function getSignatureByLdap(string $ldap): ?string;

    public function search(array $params): ?array;
}
