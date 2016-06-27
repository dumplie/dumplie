<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Tactician\Middleware;

use Dumplie\Application\Command\Command;
use Dumplie\Application\Command\ExtensionRegistry;
use League\Tactician\Middleware;

final class ExtensionMiddleware implements Middleware
{
    /**
     * @var ExtensionRegistry
     */
    private $extensionRegistry;

    /**
     * @param ExtensionRegistry $extensionRegistry
     */
    public function __construct(ExtensionRegistry $extensionRegistry)
    {
        $this->extensionRegistry = $extensionRegistry;
    }

    /**
     * @param object $command
     * @param callable $next
     */
    public function execute($command, callable $next)
    {
        if ($command instanceof Command) {
            $this->extensionRegistry->pre($command);
        }

        try {
            $next($command);
        } catch (\Exception $exception) {
            $this->extensionRegistry->passException($command, $exception);
        }

        if ($command instanceof Command) {
            $this->extensionRegistry->post($command);
        }
    }
}