<?php 

namespace Src\Infrastructure\Repository\Article;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Article\Article;

final readonly class ArticleRepository extends PDOManager implements ArticleRepositoryInterface {
    public function find(int $id): ?Article
    {
        $query = <<<HEREDOC
                        SELECT 
                            *
                        FROM
                            articles A
                        WHERE
                            A.id = :id 
                        AND A.deleted = 0
                    HEREDOC;

        $parameters = [
            "id" => $id,
        ];

        $result = $this->execute($query, $parameters);

        return $this->toArticle($result[0] ?? null);
    }

    /** @return Article[] */
    public function search(): array
    {
        $query = <<<HEREDOC
                        SELECT
                            *
                        FROM
                            articles A 
                        WHERE
                            A.deleted = 0
                    HEREDOC;
        
        $results = $this->execute($query);

        $articles = [];
        foreach($results as $result) {
            $articles[] = $this->toArticle($result);
        }

        return $articles;
    }


    public function findByCategory(int $categoryId): array
    {
        $query = <<<SQL
                        SELECT * FROM articles
                        WHERE id_category = :categoryId AND deleted = 0
                    SQL;

        $parameters = [
            "categoryId" => $categoryId
        ];

        $results = $this->execute($query, $parameters);

        $articles = [];
        foreach ($results as $result) {
            $articles[] = $this->toArticle($result);
        }

        return $articles;
    }


    public function create(Article $article): void{



        $query = <<< INSERT_QUERY
                        INSERT INTO articles (price, description, stock, imageUrl, name,id_category, deleted)
                        VALUES (:price, :description, :stock, :imageUrl, :name, :id_category, :deleted)
                        INSERT_QUERY;
        
        $parameters = [
            "price" => $article->price(),
            "description" => $article->description(),
            "stock" => $article->stock(),
            "imageUrl" => $article->imageUrl(),
            "name" => $article->name(),
            "id_category" => $article->id_category(),
            "deleted" => $article->deleted()
            
           
            
        ];

        $this->execute($query, $parameters);
    }

    public function update(Article $article): void
    {
        $query = <<<UPDATE_QUERY
                        UPDATE articles
                        SET price = :price, description = :description, stock = :stock, imageUrl = :imageUrl, name = :name,id_category=:id_category, deleted = :deleted
                        WHERE id = :id
                    UPDATE_QUERY;

        $parameters = [
            "id" => $article->id(),
            "price" => $article->price(),
            "description" => $article->description(),
            "stock" => $article->stock(),
            "imageUrl" => $article->imageUrl(),
            "name" => $article->name(),
            "id_category" => $article->id_category(),
            "deleted" => $article->deleted()
        ];

        $this->execute($query, $parameters);
    }

    private function toArticle(?array $primitive): ?Article {
        if ($primitive === null) {
            return null;
        }

        return new Article(
            $primitive["id"],
            $primitive["price"],
            $primitive["description"],
            $primitive["stock"],
            $primitive["imageUrl"],
            $primitive["name"],
            $primitive["id_category"],
            $primitive["deleted"] ?? 0

        );
    }
}