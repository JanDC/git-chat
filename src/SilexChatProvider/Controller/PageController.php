<?php

namespace SilexChatProvider\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class PageController implements ControllerProviderInterface
{

    public function indexAction(Request $request, Application $app)
    {
        $app['gitchat.chat_service']->addMessage('Zomaar een test om '. time());
        $messages = $app['gitchat.chat_service']->getHistory();

        return $app['twig']->render('Page/index.twig', ['messages' => $messages]);
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->match(
            '/',
            function (Request $request, Application $app) {
                return $this->indexAction($request, $app);
            }
        )->bind('index');

        return $controllers;
    }
}