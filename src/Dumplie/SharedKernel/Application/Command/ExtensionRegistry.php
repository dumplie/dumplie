<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Command;

use Dumplie\SharedKernel\Application\ServiceLocator;

final class ExtensionRegistry
{
    const EXTENSION_KEY = 'extension';
    const EXTENSION_PRIORITY_KEY = 'priority';

    /**
     * @var Extension[]
     */
    private $extensions;

    /**
     * @var ServiceLocator
     */
    private $serviceLocator;

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->extensions = [];
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param Extension $commandExtension
     * @param int $priority
     */
    public function register(Extension $commandExtension, int $priority = 0)
    {
        $this->extensions[] = [self::EXTENSION_KEY => $commandExtension, self::EXTENSION_PRIORITY_KEY => $priority];

        if (count($this->extensions) > 1) {
            uasort($this->extensions, function ($itemA, $itemB) {
                if ($itemA[self::EXTENSION_PRIORITY_KEY] === $itemB[self::EXTENSION_PRIORITY_KEY]) {
                    return 0;
                }

                return ($itemA[self::EXTENSION_PRIORITY_KEY] > $itemB[self::EXTENSION_PRIORITY_KEY]) ? -1 : 1;
            });
        }
    }

    /**
     * @param Command $command
     */
    public function pre(Command $command)
    {
        foreach ($this->extensions as $extensionItem) {
            $extension = $extensionItem[self::EXTENSION_KEY];

            if ($extension->expands($command)) {
                $extension->pre($command, $this->serviceLocator);
            }
        }
    }

    /**
     * @param Command $command
     */
    public function post(Command $command)
    {
        foreach ($this->extensions as $extensionItem) {
            $extension = $extensionItem[self::EXTENSION_KEY];

            if ($extension->expands($command)) {
                $extension->post($command, $this->serviceLocator);
            }
        }
    }

    /**
     * @param Command $command
     * @param \Exception $exception
     */
    public function passException(Command $command, \Exception $exception)
    {
        foreach ($this->extensions as $extensionItem) {
            $extension = $extensionItem[self::EXTENSION_KEY];

            if ($extension->expands($command)) {
                $extension->catchException($command, $exception, $this->serviceLocator);
            }
        }
    }
}