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

namespace MoveElevator\Styleguide\Middleware;

use MoveElevator\Styleguide\Utility\PreviewDocument;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Http\{HtmlResponse, StreamFactory};

use function is_string;

use const ENT_QUOTES;

/**
 * ElementPreviewMiddleware.
 *
 * Answers the preview frames of the element documentation. A request for a page with the parameter
 * `sg_preview=<uid>` returns that page reduced to one content element. The page is rendered as usual,
 * so its own access rules apply and the project's CSS and layout reach the element. Only logged-in
 * backend users get the reduced page, because the frames are meant for the people who document
 * the elements. A request with `sg_page_preview=1` returns the page as a whole, for the page
 * preview element, which shows a page without the frame around it.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class ElementPreviewMiddleware implements MiddlewareInterface
{
    public const PARAMETER = 'sg_preview';

    public const PAGE_PARAMETER = 'sg_page_preview';

    public function __construct(private readonly Context $context) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $uid = (int) ($queryParams[self::PARAMETER] ?? 0);
        $wholePage = '' !== (string) ($queryParams[self::PAGE_PARAMETER] ?? '');
        if ($uid <= 0 && !$wholePage) {
            return $handler->handle($request);
        }

        if (!$this->context->getAspect('backend.user')->isLoggedIn()) {
            return $this->notice('The preview is only available to logged-in backend users.', 403);
        }

        $response = $handler->handle($request);
        if (200 !== $response->getStatusCode() || !str_contains($response->getHeaderLine('Content-Type'), 'text/html')) {
            return $response;
        }

        if ($wholePage) {
            return $response
                ->withHeader('Cache-Control', 'private, no-store')
                ->withHeader('X-Robots-Tag', 'noindex');
        }

        $html = PreviewDocument::isolate((string) $response->getBody(), $uid);
        if (!is_string($html)) {
            return $this->notice('The element is not on this page.', 404);
        }

        return $response
            ->withBody((new StreamFactory())->createStream($html))
            ->withHeader('Cache-Control', 'private, no-store')
            ->withHeader('X-Robots-Tag', 'noindex');
    }

    private function notice(string $message, int $status): HtmlResponse
    {
        return new HtmlResponse(
            '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Preview</title></head><body><p>'.htmlspecialchars($message, ENT_QUOTES).'</p></body></html>',
            $status,
            ['Cache-Control' => 'private, no-store', 'X-Robots-Tag' => 'noindex'],
        );
    }
}
