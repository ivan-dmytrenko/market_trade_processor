<?php

namespace {

    use BitolaCo\Silex\CapsuleServiceProvider;
    use MarketTradeProcessor\MessageConsumption\Service\CurrencyPairService;
    use MarketTradeProcessor\Graph\Provider\Controller\GraphControllerProvider;
    use MarketTradeProcessor\MessageConsumption\Provider\Controller\MessageConsumptionControllerProvider;
    use MarketTradeProcessor\MessageConsumption\Service\CountryMessageService;
    use MarketTradeProcessor\MessageConsumption\Service\TradeMessageService;
    use MarketTradeProcessor\Model\CountryMessage;
    use MarketTradeProcessor\Model\CurrencyPair;
    use MarketTradeProcessor\Model\TradeMessage;
    use MarketTradeProcessor\Validator\Provider\DateWithMValidatorProvider;
    use Silex\Provider\MonologServiceProvider;
    use Silex\Provider\TwigServiceProvider;
    use Silex\Provider\ValidatorServiceProvider;

    /**
     * The Silex Application containing the project definition.
     */
    class Application extends \Silex\Application
    {
        /**
         * Instantiate a new Application.
         *
         * Objects and parameters can be passed as argument to the constructor, e.g. `debug`.
         *
         * @param array $values The parameters or objects.
         */
        public function __construct(array $values = array())
        {
            parent::__construct($values);

            $this['config'] = require_once __DIR__ . '/config/main.conf.php';

            $this['debug'] = $this['config']['debug'];

            $this->register(new CapsuleServiceProvider(), array(
                'capsule.connection' => $this['config']['capsule_connection']
            ));

            $this->register(
                new TwigServiceProvider(),
                array(
                    'twig.path' => $this['config']['twig']['path'],
                )
            );

            $this->register(
                new ValidatorServiceProvider()
            );

            $this->register(new DateWithMValidatorProvider());

            $this->register(new MonologServiceProvider(), array(
                'monolog.logfile' => $this['config']['monolog']['path'],
            ));

            $this->mount('/', new GraphControllerProvider());
            $this->mount('/', new MessageConsumptionControllerProvider());

            $this['currency_pair'] = function () {
                return new CurrencyPair();
            };

            $this['country_message'] = function () {
                return new CountryMessage();
            };

            $this['trade_message'] = function () {
                return new TradeMessage();
            };

            $this->setCurrencyPairServices();
            $this->setCountryMessageService();
            $this->setTradeMessageService();
        }

        /**
         * Set the CurrencyPair service.
         *
         * Services are available in the dependency injection container.
         */
        protected function setCurrencyPairServices()
        {
            $this['currency_pair_service'] = function (\Application $app) {
                return new CurrencyPairService($app['currency_pair'], $app['monolog']);
            };
        }

        /**
         * Set the CountryMessage service.
         *
         * Services are available in the dependency injection container.
         */
        protected function setCountryMessageService()
        {
            $this['country_message_service'] = function (\Application $app) {
                return new CountryMessageService($app['country_message'], $app['monolog']);
            };
        }

        /**
         * Set the TradeMessage service.
         *
         * Services are available in the dependency injection container.
         */
        protected function setTradeMessageService()
        {
            $this['trade_message_service'] = function (\Application $app) {
                return new TradeMessageService($app['trade_message'], $app['monolog']);
            };
        }
    }
}
