<?php

namespace App\Application\DTO;

final readonly class PostData
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $lead,
        public string $content,
        public string $publishedAt,
        public string $newsAgencyName,
        public ?string $url = null,
        public array $categories = [],
        public array $tags = [],
        public array $mainImages = [],
        public ?string $language = null,
        public ?string $newsAgencyId = null,
    ) {}
}
