<?php

namespace Harmony\Bundle\CoreBundle\EventSubscriber;

use Harmony\Bundle\CoreBundle\HarmonyCoreBundle;
use Harmony\Bundle\ThemeBundle\Exception\NoActiveThemeException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\EventListener\RouterListener as SymfonyRouterListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use function array_merge;
use function dirname;
use function ob_get_clean;
use function ob_start;
use function realpath;
use function substr;

/**
 * Class RouterListener
 *
 * @package Harmony\Bundle\CoreBundle\EventListener
 */
class RouterListener extends SymfonyRouterListener
{

    /** @var string $projectDir */
    protected $projectDir;

    /** @var bool $debug */
    private $debug;

    /**
     * RouterListener constructor.
     *
     * @param RouterInterface      $matcher
     * @param RequestStack         $requestStack
     * @param null|RequestContext  $context
     * @param null|LoggerInterface $logger
     * @param string|null          $projectDir
     * @param bool                 $debug
     */
    public function __construct(RouterInterface $matcher, RequestStack $requestStack, ?RequestContext $context = null,
                                ?LoggerInterface $logger = null, string $projectDir = null, bool $debug = true)
    {
        parent::__construct($matcher, $requestStack, $context, $logger, $projectDir, $debug);
        $this->projectDir = $projectDir;
        $this->debug      = $debug;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     * The array keys are event names and the value can be:
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     * For instance:
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array_merge(parent::getSubscribedEvents(), [KernelEvents::EXCEPTION => ['onKernelException', - 60]]);
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        if (!$this->debug || !($e instanceof NotFoundHttpException || $e instanceof NoActiveThemeException)) {
            return;
        }

        if ($e->getPrevious() instanceof NoConfigurationException || $e instanceof NoActiveThemeException) {
            $event->setResponse($this->createWelcomeResponse());
        }
    }

    /**
     * @return Response
     */
    protected function createWelcomeResponse(): Response
    {
        $version    = HarmonyCoreBundle::VERSION;
        $baseDir    = realpath($this->projectDir) . \DIRECTORY_SEPARATOR;
        $docVersion = substr(Kernel::VERSION, 0, 3);

        ob_start();
        include dirname(__DIR__) . '/Resources/views/welcome.html.php';

        return new Response(ob_get_clean(), Response::HTTP_NOT_FOUND);
    }
}