<?php

namespace MarketTradeProcessor\Validator\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use MarketTradeProcessor\Validator\Constraints\DateWithMValidator;

class DateWithMValidatorProvider implements ServiceProviderInterface
{
    /**
     * The DateWithMValidator Service Provider
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['validator.dateWithM'] = $app->share(function ($app) {
            $validator = new DateWithMValidator();
            return $validator;
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
