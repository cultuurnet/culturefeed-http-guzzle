<?php
/**
 * @file
 */

namespace CultuurNet\CulturefeedHttpGuzzle;

use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Symfony\Component\EventDispatcher\EventDispatcherInterface;
use \Symfony\Component\EventDispatcher\EventDispatcher;
use \Symfony\Component\EventDispatcher\GenericEvent;
use CultureFeed_HttpClientFactory;
use Guzzle\Http\Client;

class HttpClientFactory implements CultureFeed_HttpClientFactory
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var HttpRequestFactory
     */
    protected $requestFactory;

    /**
     * {@inheritdoc}
     */
    public function createHttpClient()
    {
        $guzzleClient = new Client();
        $guzzleClient->setRequestFactory($this->getRequestFactory());

        $event = new GenericEvent($guzzleClient);
        $this->getEventDispatcher()->dispatch('client.created', $event);

        $client = new HttpClient($guzzleClient);

        return $client;
    }

    /**
     * @inheritdoc
     */
    protected function getRequestFactory()
    {
        if (!isset($this->requestFactory)) {
            $this->requestFactory = new HttpRequestFactory();
        }

        return $this->requestFactory;
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        if (!isset($this->eventDispatcher)) {
            $this->eventDispatcher = new EventDispatcher();
        }

        return $this->eventDispatcher;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->getEventDispatcher()->addSubscriber($subscriber);
    }
}
