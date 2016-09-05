<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Integration\Infrastructure\Twig;

use Dumplie\SharedKernel\Application\RenderingEngine;
use Dumplie\SharedKernel\Application\View\ContextMap;
use Dumplie\SharedKernel\Infrastructure\Twig\DumplieFileLoader;
use Dumplie\SharedKernel\Infrastructure\Twig\Extension\DummyTranslationExtension;
use Dumplie\SharedKernel\Infrastructure\Twig\Extension\RenderingExtension;
use Dumplie\SharedKernel\Infrastructure\Twig\TwigRenderingEngine;
use Dumplie\SharedKernel\Tests\Integration\Infrastructure\Twig\UserInterface\View\BarView;
use Dumplie\SharedKernel\Tests\Integration\Infrastructure\Twig\UserInterface\View\FooView;

final class TwigRenderingEngineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RenderingEngine
     */
    private $engine;

    /**
     * @var DumplieFileLoader
     */
    private $loader;

    /**
     * @var ContextMap
     */
    private $contextMap;

    public function setUp()
    {
        $this->loader = new DumplieFileLoader();
        $this->loader->addPath(__DIR__ . '/UserInterface/Resources/Twig/test', 'test');
        $this->loader->addPath(__DIR__ . '/UserInterface/Resources/Twig/overwritten', 'test_overwritten');
        $this->loader->addPath(__DIR__ . '/UserInterface/Resources/Twig/test_super', 'test_super');

        $twig = new \Twig_Environment(
            $this->loader,
            [
                'strict_variables' => true,
                'debug' => true,
                'cache' => false,
                'autoescape' => false,
            ]
        );
        $this->contextMap = new ContextMap();
        $twig->addExtension(new DummyTranslationExtension());
        $twig->addExtension(new RenderingExtension($this->contextMap));

        $this->engine = new TwigRenderingEngine($twig, $this->contextMap);
    }

    public function test_rendering_simple_view()
    {
        $viewObject = new FooView();
        $this->assertSame('<foo>foo</foo>', $this->engine->render($viewObject));
    }

    public function test_rendering_embedded_view()
    {
        $viewObject = new BarView(new FooView());

        $this->assertSame('<bar><foo>foo</foo></bar>', $this->engine->render($viewObject));
    }

    public function test_rendering_embedded_view_with_overwritten_context()
    {
        $viewObject = new BarView(new FooView());

        $this->assertSame('<bar-new><foo>foo</foo></bar-new>', $this->engine->render($viewObject, 'test_overwritten'));
    }

    public function test_rendering_with_extended_context()
    {
        $viewObject = new FooView();

        $this->contextMap->extendContext('test', 'test_super');

        $this->assertSame('<super-foo>foo</super-foo>', $this->engine->render($viewObject));
    }

    public function test_fallback_when_extended_context_does_not_have_required_template()
    {
        $viewObject = new BarView(new FooView());

        $this->contextMap->extendContext('test', 'test_super');

        $this->assertSame('<bar><super-foo>foo</super-foo></bar>', $this->engine->render($viewObject));
    }

    public function test_throwing_exception_when_context_already_extendend()
    {
        $this->contextMap->extendContext('test', 'test_super');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Context \"test\" is already extended by, \"test_super\"");

        $this->contextMap->extendContext('test', 'test_new');
    }

    public function test_setting_context_for_single_view_object_class()
    {
        $viewObject = new BarView(new FooView());

        $this->contextMap->setContext(BarView::class, 'test_overwritten');

        $this->assertSame('<bar-new><foo>foo</foo></bar-new>', $this->engine->render($viewObject));
    }
}