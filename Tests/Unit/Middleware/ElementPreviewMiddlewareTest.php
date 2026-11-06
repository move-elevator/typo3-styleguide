<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_styleguide" TYPO3 CMS extension.
 *
 * (c) 2025-2026 Konrad Michalik <km@move-elevator.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MoveElevator\Styleguide\Tests\Unit\Middleware;

use MoveElevator\Styleguide\Middleware\ElementPreviewMiddleware;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;
use TYPO3\CMS\Core\Context\{Context, UserAspect};
use TYPO3\CMS\Core\Http\{HtmlResponse, ServerRequest};

/**
 * ElementPreviewMiddlewareTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ElementPreviewMiddlewareTest extends TestCase
{
    private const PAGE = '<!DOCTYPE html><html><head><title>T</title></head><body><header>Nav</header>'
        .'<!--sg-preview-start:7--><section>Element</section><!--sg-preview-end:7--><footer>Foot</footer></body></html>';

    private Context&MockObject $context;

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);
    }

    public function testPassesThroughWithoutTheParameter(): void
    {
        $response = new HtmlResponse(self::PAGE);
        $handler = $this->handlerReturning($response);

        $result = $this->createSubject()->process($this->request([]), $handler);

        self::assertSame($response, $result);
    }

    public function testRefusesTheParameterWithoutBackendLogin(): void
    {
        $this->setBackendLogin(false);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::never())->method('handle');

        $result = $this->createSubject()->process($this->request(['sg_preview' => '7']), $handler);

        self::assertSame(403, $result->getStatusCode());
        self::assertSame('private, no-store', $result->getHeaderLine('Cache-Control'));
    }

    public function testReturnsTheReducedPageForABackendUser(): void
    {
        $this->setBackendLogin(true);

        $result = $this->createSubject()->process($this->request(['sg_preview' => '7']), $this->handlerReturning(new HtmlResponse(self::PAGE)));

        $body = (string) $result->getBody();
        self::assertSame(200, $result->getStatusCode());
        self::assertStringContainsString('<section>Element</section>', $body);
        self::assertStringNotContainsString('Nav', $body);
        self::assertStringNotContainsString('Foot', $body);
        self::assertStringContainsString('html,body{background:none!important}', $body);
        self::assertSame('private, no-store', $result->getHeaderLine('Cache-Control'));
        self::assertSame('noindex', $result->getHeaderLine('X-Robots-Tag'));
    }

    public function testRefusesThePagePreviewWithoutBackendLogin(): void
    {
        $this->setBackendLogin(false);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::never())->method('handle');

        $result = $this->createSubject()->process($this->request(['sg_page_preview' => '1']), $handler);

        self::assertSame(403, $result->getStatusCode());
    }

    public function testReturnsTheWholePageForThePagePreview(): void
    {
        $this->setBackendLogin(true);

        $result = $this->createSubject()->process($this->request(['sg_page_preview' => '1']), $this->handlerReturning(new HtmlResponse(self::PAGE)));

        self::assertSame(self::PAGE, (string) $result->getBody());
        self::assertSame('private, no-store', $result->getHeaderLine('Cache-Control'));
        self::assertSame('noindex', $result->getHeaderLine('X-Robots-Tag'));
    }

    public function testAnswersNotFoundWhenTheElementIsNotOnThePage(): void
    {
        $this->setBackendLogin(true);

        $result = $this->createSubject()->process($this->request(['sg_preview' => '99']), $this->handlerReturning(new HtmlResponse(self::PAGE)));

        self::assertSame(404, $result->getStatusCode());
        self::assertStringNotContainsString('Element', (string) $result->getBody());
    }

    public function testKeepsAnErrorResponseOfThePageAsItIs(): void
    {
        $this->setBackendLogin(true);
        $response = new HtmlResponse('Error', 500);

        $result = $this->createSubject()->process($this->request(['sg_preview' => '7']), $this->handlerReturning($response));

        self::assertSame($response, $result);
    }

    private function createSubject(): ElementPreviewMiddleware
    {
        return new ElementPreviewMiddleware($this->context);
    }

    /**
     * @param array<string, string> $query
     */
    private function request(array $query): ServerRequestInterface
    {
        return (new ServerRequest('https://example.test/page'))->withQueryParams($query);
    }

    private function handlerReturning(HtmlResponse $response): RequestHandlerInterface
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response);

        return $handler;
    }

    private function setBackendLogin(bool $loggedIn): void
    {
        $backendUser = null;
        if ($loggedIn) {
            $backendUser = $this->createMock(AbstractUserAuthentication::class);
            $backendUser->userid_column = 'uid';
            $backendUser->user = ['uid' => 1];
        }
        $this->context->method('getAspect')->with('backend.user')->willReturn(new UserAspect($backendUser));
    }
}
