<?php 

use Src\Service\Article\ArticlesByCategorySearcherService;

final readonly class ArticlesByCategoryGetController {
    private ArticlesByCategorySearcherService $service;

    public function __construct() {
        $this->service = new ArticlesByCategorySearcherService();
    }

    public function start(array $params): void
    {
     
        $categoryId = (int) ($params['0'] ?? 0);
        $articles = $this->service->searchByCategory($categoryId);

        echo json_encode($this->toResponse($articles));
    }

    private function toResponse(array $articles): array 
    {
        $responses = [];
        
        foreach($articles as $article) {
            $responses[] = [
                "id" => $article->id(),
                "price" => $article->price(),
                "description" => $article->description(),
                "stock" => $article->stock(),
                "imageUrl" => $article->imageUrl(),
                "name" => $article->name()
            ];
        }

        return $responses;
    }
}
