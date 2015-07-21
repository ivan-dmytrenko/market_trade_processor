<?php

namespace MarketTradeProcessor\Graph\Controller;

use Silex\Application;

class GraphController
{

    /**
     * Display trade messages graph
     *
     * @param Application $app
     * @return Response
     */
    public function index(Application $app)
    {
        $currencyPairs = $app['currency_pair']->getCurrencyPairsForGraph();
        return $app['twig']->render(
            'graph/index.twig.html',
            array('currencyPairs' => $currencyPairs->toJson())
        );
    }
}
