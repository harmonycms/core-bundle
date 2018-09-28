<?php

namespace Harmony\Bundle\CoreBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\RouterListener as SymfonyRouterListener;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Class RouterListener
 *
 * @package Harmony\Bundle\CoreBundle\EventListener
 */
class RouterListener extends SymfonyRouterListener
{

    /** @var string $projectDir */
    protected $projectDir;

    /**
     * RouterListener constructor.
     *
     * @param UrlMatcherInterface|RequestMatcherInterface $matcher
     * @param RequestStack                                $requestStack
     * @param null|RequestContext                         $context
     * @param null|LoggerInterface                        $logger
     * @param string|null                                 $projectDir
     * @param bool                                        $debug
     */
    public function __construct($matcher, RequestStack $requestStack, ?RequestContext $context = null,
                                ?LoggerInterface $logger = null, string $projectDir = null, bool $debug = true)
    {
        parent::__construct($matcher, $requestStack, $context, $logger, $projectDir, $debug);
        $this->projectDir = $projectDir;
    }

    /**
     * @return Response
     */
    protected function createWelcomeResponse(): Response
    {
        $version    = Kernel::VERSION;
        $baseDir    = realpath($this->projectDir) . \DIRECTORY_SEPARATOR;
        $docVersion = substr(Kernel::VERSION, 0, 3);

        ob_start();
        include dirname(__DIR__) . '/Resources/views/welcome.html.php';

        return new Response(ob_get_clean(), Response::HTTP_NOT_FOUND);
    }
}