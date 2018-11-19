<?php

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Indragunawan\MiddlewareBundle\Annotation\AfterFilter;
use Indragunawan\MiddlewareBundle\Annotation\BeforeFilter;
use Indragunawan\MiddlewareBundle\Middleware;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class FilterSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Middleware
     */
    private $middleware;

    public function __construct(Reader $reader, Middleware $middleware)
    {
        $this->reader = $reader;
        $this->middleware = $middleware;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!\is_array($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        }

        if (!\is_array($controller)) {
            return;
        }

        $className = ClassUtils::getClass($controller[0]);
        $filters = $this->getClassAnnotations($className, $controller[1], BeforeFilter::class);
        $middlewares = $this->middleware->getMiddlewares('before_filter', $filters);

        foreach ($middlewares as $middleware) {
            $middleware->onBeforeFilter($event->getRequest(), $controller, $event->getRequestType());
        }
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        // in request attributes '_controller' we trust
        $controller = $event->getRequest()->attributes->get('_controller');
        // $controller value should like 'App\Controller\HomepageController::index'
        $controller = explode('::', $controller);
        if (!\is_array($controller) || 2 !== \count($controller)) {
            return;
        }

        $filters = $this->getClassAnnotations($controller[0], $controller[1], AfterFilter::class);
        $middlewares = $this->middleware->getMiddlewares('after_filter', $filters);
        foreach ($middlewares as $middleware) {
            $middleware->onAfterFilter($event->getRequest(), $event->getResponse(), $controller, $event->getRequestType());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -255], //expect priority -255 is the last
            KernelEvents::RESPONSE => ['onKernelResponse', 255], //expect priority 255 is the first
        ];
    }

    /**
     * @param string $className
     * @param string $method
     * @param string $annotationClass
     *
     * @return array
     */
    private function getClassAnnotations(string $className, string $method, string $annotationClass): array
    {
        $filters = [];
        $rejectedFilters = [];
        $classAnnotations = $this->reader->getClassAnnotations(new \ReflectionClass($className));

        foreach ($classAnnotations as $annotation) {
            if ($annotation instanceof $annotationClass) {
                if ($annotation->isSupportsMethod($method)) {
                    $filters = array_merge($filters, array_diff($annotation->getNames(), $rejectedFilters));
                } else {
                    $rejectedFilters = array_merge($rejectedFilters, $annotation->getNames());
                }
            }
        }

        return array_unique($filters);
    }
}
