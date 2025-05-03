<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Helpers\Markdowner;

final class MarkdownerTest extends TestCase
{
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    public function testValidMarkdown(): void
    {
        $markdowner = new Markdowner();
        $res = $markdowner->print("#test");
        $this->assertSame($res, "<h1>test</h1>");
    }
}
