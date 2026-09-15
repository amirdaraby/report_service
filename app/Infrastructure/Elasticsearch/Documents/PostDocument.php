<?php

namespace App\Infrastructure\Elasticsearch\Documents;

use App\Application\DTO\PostData;

final class PostDocument
{
    public static function fromDTO(PostData $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'lead' => $post->lead,
            'content' => $post->content,
            'published_at' => $post->publishedAt,
            'news_agency_name' => $post->newsAgencyName,
            'url' => $post->url,
            'categories' => $post->categories,
            'tags' => $post->tags,
            'main_images' => $post->mainImages,
            'lf_lang' => $post->language,
            'news_agency_id' => $post->newsAgencyId,
        ];
    }

    public static function toDTO(array $document): PostData
    {
        return new PostData(
            id: $document['id'],
            title: $document['title'],
            lead: $document['lead'] ?? null,
            content: $document['content'],
            publishedAt: $document['published_at'],
            newsAgencyName: $document['news_agency_name'] ?? null,
            url: $document['url'] ?? null,
            categories: $document['categories'] ?? [],
            tags: $document['tags'] ?? [],
            mainImages: $document['main_images'] ?? [],
            language: $document['lf_lang'] ?? null,
            newsAgencyId: $document['news_agency_id'] ?? null,
        );
    }
}
