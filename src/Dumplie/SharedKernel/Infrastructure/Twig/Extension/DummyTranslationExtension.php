<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Twig\Extension;

final class DummyTranslationExtension extends \Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'dumplie_translation';
    }

    public function getFilters()
    {
        return [new \Twig_SimpleFilter("trans", [$this, 'trans'])];
    }

    /**
     * @param $message
     * @param array $arguments
     * @param null $domain
     * @param null $locale
     * @return mixed
     */
    public function trans($message, array $arguments = array(), $domain = null, $locale = null)
    {
        return $message;
    }
}