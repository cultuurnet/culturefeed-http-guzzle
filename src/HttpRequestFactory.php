<?php
/**
 * @file
 */

namespace CultuurNet\CulturefeedHttpGuzzle;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\RequestFactory;
use Guzzle\Http\QueryAggregator\DuplicateAggregator;

class HttpRequestFactory extends RequestFactory
{
    /**
     * {@inheritdoc}
     */
    public function create($method, $url, $headers = null, $body = null, array $options = array())
    {
        /** @var Request $request */
        $request = parent::create($method, $url, $headers, $body);
        $request->getQuery()->setAggregator(new DuplicateAggregator());

        return $request;
    }
}
