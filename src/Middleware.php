<?php

declare(strict_types=1);

/*
 * This file is part of the MiddlewareBundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\MiddlewareBundle;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class Middleware
{
    private $middlewares = [];
    private $sorted = [];

    public function addMiddleware(string $event, string $name, \Closure $middleware, int $priority = 0): void
    {
        if ($this->isMiddlewareExists($event, $name)) {
            throw new \InvalidArgumentException(sprintf('Duplicate middleware of name "%s" on "%s" event.', $name, $event));
        }

        $this->middlewares[$event][] = [
            'name' => $name,
            'priority' => $priority,
            'middleware' => $middleware,
        ];
        $this->sorted[$event] = false;
    }

    public function getMiddlewares(string $event, array $names): array
    {
        if (empty($this->middlewares[$event]) || empty($names)) {
            return [];
        }

        if (false === $this->sorted[$event]) {
            $this->sortMiddlewares($event);
        }

        $middlewares = array_filter($this->middlewares[$event], function (array $middleware) use ($names) {
            return \in_array($middleware['name'], $names, true);
        });

        $middlewares = array_reduce($middlewares, function (array $result, array $middleware) {
            $result[$middleware['name']] = $middleware['middleware']();

            return $result;
        }, []);

        return $middlewares;
    }

    private function sortMiddlewares(string $event)
    {
        // sort middleware by priority desc
        usort($this->middlewares[$event], function (array $a, array $b) {
            return $b['priority'] <=> $a['priority'];
        });
        $this->sorted[$event] = true;
    }

    private function isMiddlewareExists(string $event, string $name): bool
    {
        if (!isset($this->middlewares[$event])) {
            return false;
        }

        foreach ($this->middlewares[$event] as $middleware) {
            if ($name === $middleware['name']) {
                return true;
            }
        }

        return false;
    }
}
