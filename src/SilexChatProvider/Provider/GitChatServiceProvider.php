<?php

namespace SilexChatProvider\Provider;

use GitChat\Service\ChatService;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\ServiceProviderInterface;

class GitChatServiceProvider implements ServiceProviderInterface
{
    /** @var array */
    private $gitchat_config;

    public function __construct(array $config)
    {
        $this->gitchat_config = $config;
    }

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app->register(new TwigServiceProvider(), [
            'twig.path' => __DIR__ . '/../View',
            'twig.options' => [
                'debug' => $app['debug'],
                'cache' => __DIR__ . '/../../../cache/twig',
            ]
        ]);
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app['gitchat.chat_service'] = new ChatService($this->gitchat_config);
    }
}