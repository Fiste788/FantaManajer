<?php

namespace App\Controller;

use App\Service\CredentialService;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\Event;

/**
 * @property CredentialService $Credential
 */
class CredentialsController extends AppController
{
    use ServiceAwareTrait;

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('Credential');
    }

    /**
     * Before filter
     *
     * @param Event $event Event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['publicKeyRequest', 'login']);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function publicKeyRequest()
    {
        $publicKeyCredentialRequestOptions = $this->Credential->assertionRequest($this->request);

        $this->set([
            'success' => true,
            'data' => $publicKeyCredentialRequestOptions,
            '_jsonOptions' => (JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * Get Webauthn public key
     *
     * @return void
     */
    public function publicKeyCreation()
    {
        $publicKeyCredentialCreationOptions = $this->Credential->attestationRequest($this->request);

        $this->set([
            'success' => true,
            'data' => $publicKeyCredentialCreationOptions,
            '_jsonOptions' => (JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function login()
    {
        $check = $this->Credential->assertionResponse($this->request);
        $this->set([
            'success' => $check,
            'data' => $check,
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * Save the credential
     *
     * @return void
     */
    public function register()
    {
        $check = $this->Credential->attestationResponse($this->request);
        $this->set([
            'success' => $check,
            'data' => $check,
            '_serialize' => ['success', 'data']
        ]);
    }
}
