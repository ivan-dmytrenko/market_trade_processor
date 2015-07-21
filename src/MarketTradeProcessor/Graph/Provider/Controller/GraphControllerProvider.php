<?php

namespace MarketTradeProcessor\Graph\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class GraphControllerProvider implements ControllerProviderInterface
{

    /**
     * @param Application $app
     * @return ControllerCollection The ControllerCollection instance.
     */
    public function connect(Application $app)
    {
        $graphFactory = $app['controllers_factory'];

        $graphFactory
            ->get('/', 'MarketTradeProcessor\Graph\Controller\GraphController::index');

        return $graphFactory;
    }
}
