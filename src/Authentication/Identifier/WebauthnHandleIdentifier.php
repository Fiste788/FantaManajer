<?php
declare(strict_types=1);

namespace App\Authentication\Identifier;

use AllowDynamicProperties;
use App\Service\WebauthnService;
use ArrayAccess;
use Authentication\Identifier\AbstractIdentifier;
use Authentication\Identifier\Resolver\ResolverAwareTrait;
use Authentication\Identifier\Resolver\ResolverInterface;
use League\Container\ContainerAwareTrait;

/**
 * @property \App\Service\WebauthnService $Webauthn
 */
#[AllowDynamicProperties]
class WebauthnHandleIdentifier extends AbstractIdentifier
{
    use ResolverAwareTrait;
    use ContainerAwareTrait;

    /**
     * Constructor
     *
     * @param array<string, mixed> $config Configuration
     * @throws \Cake\Core\Exception\CakeException
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * Default configuration.
     * - `fields` The fields to use to identify a user by:
     *   - `username`: one or many username fields.
     * - `resolver` The resolver implementation to use.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'fields' => [
            self::CREDENTIAL_USERNAME => 'uuid',
        ],
        'resolver' => 'Authentication.Orm',
    ];

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function identify(array $credentials): ArrayAccess|array|null
    {
        if (!isset($credentials['publicKey'])) {
            return null;
        }

        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        $request = $credentials['request'];
        $publicKey = (string) $credentials['publicKey'];
        /** @var string|null $userHandle */
        $userHandle = $credentials['userHandle'];

        $result = $this->getContainer()->get(WebauthnService::class)->login($publicKey, $request, $userHandle);

        return $this->_findIdentity($result->userHandle);
    }

    /**
     * Find a user record using the username/identifier provided.
     *
     * @param string $identifier The username/identifier.
     * @return \ArrayAccess|array|null
     * @throws \RuntimeException
     */
    protected function _findIdentity(string $identifier): ArrayAccess|array|null
    {
        /** @var array<string> $fields */
        $fields = (array) $this->getConfig('fields.' . self::CREDENTIAL_USERNAME);
        $conditions = [];
        foreach ($fields as $field) {
            $conditions[$field] = $identifier;
        }

        return $this->getResolver()->find($conditions, ResolverInterface::TYPE_OR);
    }
}
