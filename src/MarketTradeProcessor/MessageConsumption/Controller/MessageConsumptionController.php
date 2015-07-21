<?php

namespace MarketTradeProcessor\MessageConsumption\Controller;

use MarketTradeProcessor\Validator\Constraints\DateWithM;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class MessageConsumptionController
{

    /**
     * Get and validate messages from POST
     *
     * @param Application $app
     * @param Request $request
     * @return Response
     */
    public function index(Application $app, Request $request)
    {
        $content = $request->getContent();
        $data = json_decode($content, true);
        $validator = new Assert\Collection(array(
                'missingFieldsMessage' => 'Error, some fields are missing',
                'fields' => array(
                    'userId' => array(new Assert\NotBlank()),
                    'currencyFrom' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 3, 'max' => 3)),
                        new Assert\NotEqualTo(array('value' => $data['currencyTo']))
                    ),
                    'currencyTo' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 3, 'max' => 3)),
                        new Assert\NotEqualTo(array('value' => $data['currencyFrom']))
                    ),
                    'amountSell' => array(new Assert\NotBlank(), new Assert\Type('numeric')),
                    'amountBuy' => array(new Assert\NotBlank(), new Assert\Type('numeric')),
                    'rate' => array(new Assert\NotBlank(), new Assert\Type('numeric')),
                    'timePlaced' => array(new Assert\NotBlank(), new DateWithM()),
                    'originatingCountry' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 2, 'max' => 2))
                    )
                )
            )
        );

        $errors = $app['validator']->validateValue($data, $validator);
        if (count($errors) > 0) {
            return new Response('Error, not valid data.', Response::HTTP_BAD_REQUEST);
        }

        \Resque::enqueue('trade_message_processor', 'MarketTradeProcessor\Job\TradeMessageHandlingJob',
            array('message' => $data), true);

        return "The data is successfully received.";
    }
}
