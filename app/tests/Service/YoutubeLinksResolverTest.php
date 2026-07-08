<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\YoutubeLinksResolver;

class YoutubeLinksResolverTest extends TestCase
{
    private YoutubeLinksResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new YoutubeLinksResolver();
    }

    public function testResolveStandardWatchUrl(): void
    {
        $result = $this->resolver->resolve('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $result);
    }

    public function testResolveShortUrl(): void
    {
        $result = $this->resolver->resolve('https://youtu.be/dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $result);
    }

    public function testResolveAlreadyEmbedUrl(): void
    {
        $result = $this->resolver->resolve('https://www.youtube.com/embed/dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $result);
    }

    public function testResolveWithSiParameter(): void
    {
        $result = $this->resolver->resolve('https://youtu.be/dQw4w9WgXcQ?si=AbCdEfGhIjKlMnOp');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ?si=AbCdEfGhIjKlMnOp', $result);
    }

    public function testResolveWithAdditionalParametersBeforeV(): void
    {
        $result = $this->resolver->resolve('https://www.youtube.com/watch?list=PL123&v=dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $result);
    }

    public function testResolveInvalidUrlReturnsNull(): void
    {
        $result = $this->resolver->resolve('https://example.com/not-a-youtube-link');

        $this->assertNull($result);
    }

    public function testResolveEmptyStringReturnsNull(): void
    {
        $result = $this->resolver->resolve('');

        $this->assertNull($result);
    }

    public function testResolveMalformedYoutubeUrlReturnsNull(): void
    {
        // ID trop court (moins de 11 caractères)
        $result = $this->resolver->resolve('https://www.youtube.com/watch?v=short');

        $this->assertNull($result);
    }
}
