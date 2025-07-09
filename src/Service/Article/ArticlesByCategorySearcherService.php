<?php 

namespace Src\Service\Article;

use Src\Entity\Article\Article;
use Src\Infrastructure\Repository\Article\ArticleRepository;

final readonly class ArticlesByCategorySearcherService {
    private ArticleRepository $repository;

    public function __construct() {
        $this->repository = new ArticleRepository();
    }

    /** @return Article[] */
    public function searchByCategory(int $categoryId): array
    {
        return $this->repository->findByCategory($categoryId);
    }
}
