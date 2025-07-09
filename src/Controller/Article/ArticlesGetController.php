<?php

use Src\Service\Article\ArticlesSearcherService;

final readonly class ArticlesGetController
{
    private ArticlesSearcherService $service;

    public function __construct()
    {
        $this->service = new ArticlesSearcherService();
    }

    public function start(): void
    {
        $articles = $this->service->search();

        $response = [];

        foreach ($articles as $article) {
            $response[] = [
                "id" => $article->id(),
                "name" => $article->name(),
                "price" => $article->price(),
                "description" => $article->description(),
                "stock" => $article->stock(),
                "imageUrl" => $article->imageUrl()
            ];
        }

        echo json_encode($response);
    }
}

