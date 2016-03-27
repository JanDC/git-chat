<?php

namespace SilexChatProvider\Controller;

use GitChat\Service\ChatService;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController implements ControllerProviderInterface
{
    public function sendMessageAction(Request $request, Application $app)
    {


        /** @var ChatService $chatService */
        $chatService = $app['gitchat.chat_service'];

        $chatService->addMessage($request->get('msg', ''));

        $history = $chatService->getHistory(25);
        return new JsonResponse($history);
    }

    public function getChatHistoryAction($request, $app)
    {
        /** @var ChatService $chatService */
        $chatService = $app['gitchat.chat_service'];

        $history = $chatService->getHistory();
        return new JsonResponse($history);
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public
    function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->match(
            '/sendMessage',
            function (Request $request, Application $app) {
                return $this->sendMessageAction($request, $app);
            }
        )->bind('send_message');
        $controllers->match(
            '/getChatHistory',
            function (Request $request, Application $app) {
                return $this->getChatHistoryAction($request, $app);
            }
        )->bind('chat_history');

        return $controllers;
    }


}