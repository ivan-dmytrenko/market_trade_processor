<?php

namespace MarketTradeProcessor\MessageConsumption\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class MessageConsumptionControllerProvider implements ControllerProviderInterface
{

    /**
     * @param Application $app
     * @return ControllerCollection The ControllerCollection instance.
     */
    public function connect(Application $app)
    {
        $messageConsumptionFactory = $app['controllers_factory'];

        $messageConsumptionFactory
            ->post('/messages', 'MarketTradeProcessor\MessageConsumption\Controller\MessageConsumptionController::index');

        return $messageConsumptionFactory;
    }
}
