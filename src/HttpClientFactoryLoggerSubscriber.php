<?php
/**
 * @file
 */

namespace CultuurNet\CulturefeedHttpGuzzle;

use Guzzle\Log\PsrLogAdapter;
use Guzzle\Plugin\Log\LogPlugin;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * HttpClientFactory subscriber which subscribes a PSR LoggerInterface
 * implementation to each instantiated Http client.
 */
class HttpClientFactoryLoggerSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \Guzzle\Plugin\Log\LogPlugin
     */
    protected $guzzleLogger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;

        $guzzle_log_adapter = new PsrLogAdapter($logger);
        $format = "Request:\n{request}\n\nResponse:\n{response}\n\nErrors: {curl_code} {curl_error}";
        $this->guzzleLogger = new LogPlugin($guzzle_log_adapter, $format);
    }

    public function clientCreated(GenericEvent $event)
    {
        $client = $event->getSubject();
        $this->logger->debug('Http client created');
        $client->addSubscriber($this->guzzleLogger);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'client.created' => 'clientCreated',
        );
    }
}
