# symfony-etromby-bundle
Client pour interroger le trombinoscope interne

## installation
```bash
$ composer require connected-company/symfony-etromby-bundle
```
Si vous utilsiez symfony Flex, le bundle sera automatiquement inclu.
Sinon, ajoutez la ligne suivante aux bundles qui sont chargés dans config/bundles.php :
```php
return [
    // ...
    Connected\ETrombyBundle\ETrombyBundle::class => ['all' => true],
];
```

Si besoin, créez ensuite un service qui etendra celui du bundle (c'est dans ce service que vous pourrez ajouter vos constantes ou méthodes custom)
```php
<?php

namespace App\Service;

use Connected\JupiterBundle\Service\JupiterClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ETrombyClientService extends ETrombyClient
{

    public function __construct(string $apiUrl, string $xApiKey, LoggerInterface $logger)
    {
        parent::__construct($apiUrl, $xApiKey, $logger);
    }

}
```

## configuration
Vous devez créer un fichier `etromby.yaml` dans `config/parameters` (sans oublier de bien l'inclure dans votre fichier `service.yaml`) avec ceci

```yaml
    parameters:
        etromby_url: "%env(resolve:ETROMBY_URL)%"
        etromby_key: "%env(resolve:ETROMBY_KEY)%"
    
    services:
        App\Service\ETrombyClientService:
            public: true
            autowire: true
            arguments:
                $apitromby_url: '%etromby_url%'
                $apitromby_key: '%etromby_key%'
```
Enfin, n'oubliez pas d'ajouter les variables suivantes à vos fichiers d'environnement
```yaml
ETROMBY_URL=http://urlDuTromby/
ETROMBY_KEY=VotreCle
```