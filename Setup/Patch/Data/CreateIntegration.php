<?php

namespace Bluebadger\JasperPim\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class CreateIntegration implements DataPatchInterface, PatchRevertableInterface
{
    const INTEGRATION_NAME = 'JasperPIM Integration';
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var \Magento\Integration\Model\IntegrationFactory
     */
    private $integrationFactory;
    /**
     * @var \Magento\Integration\Model\AuthorizationService
     */
    private $authorizationService;
    /**
     * @var \Magento\Integration\Model\OauthService
     */
    private $oAuthService;
    /**
     * @var \Magento\Integration\Model\Oauth\Token
     */
    private $token;

    /**
     * CreateIntegration constructor.
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
     * @param \Magento\Integration\Model\AuthorizationService $authorizationService
     * @param \Magento\Integration\Model\OauthService $oAuthService
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Integration\Model\IntegrationFactory $integrationFactory,
        \Magento\Integration\Model\AuthorizationService $authorizationService,
        \Magento\Integration\Model\OauthService $oAuthService,
        \Magento\Integration\Model\Oauth\Token $token
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->integrationFactory = $integrationFactory;
        $this->authorizationService = $authorizationService;
        $this->oAuthService = $oAuthService;
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $integration = $this->integrationFactory->create()->load(self::INTEGRATION_NAME, 'name');

        if (empty($integration->getData())) {
            $integrationData = [
                'name' => self::INTEGRATION_NAME,
                'email' => '',
                'status' => '1',
                'endpoint' => '',
                'setup_type' => '0'
            ];
            $integration->setData($integrationData);
            $integration->save();
            $integrationId = $integration->getId();

            $consumerName = 'Integration' . $integrationId;
            $consumer = $this->oAuthService->createConsumer(['name' => $consumerName]);
            $consumerId = $consumer->getId();
            $integration->setConsumerId($consumerId);
            $integration->save();

            $this->authorizationService->grantAllPermissions($integrationId);

            $this->token->createVerifierToken($consumerId)->setType('access')->save();
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $integration = $this->integrationFactory->create()->load(self::INTEGRATION_NAME, 'name');

        if (!empty($integration->getData())) {
            $integration->delete();
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
