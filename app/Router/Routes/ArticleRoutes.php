<?php 

final readonly class ArticleRoutes {
  public static function getRoutes(): array {
    return [
      [
        "name" => "article_get",
        "url" => "/articles/{id}",
        "controller" => "Article/ArticleGetController.php",
        "method" => "GET",
        "parameters" => [
          [
            "name" => "id",
            "type" => "int"
          ]
        ]
      ],
      [
        "name" => "articles_get",
        "url" => "/articles",
        "controller" => "Article/ArticlesGetController.php",
        "method" => "GET"
      ],
      [
        "name" => "article_create",
        "url" => "/articles",
        "controller" => "Article/ArticlePostController.php",
        "method" => "POST"
      ],
      [
        "name" => "article_update",
        "url" => "/articles/{id}",
        "controller" => "Article/ArticlePutController.php",
        "method" => "PUT",
        "parameters" => [
          [
            "name" => "id",
            "type" => "int"
          ]
        ]
          ],
          [
            "name" => "article_delete",
            "url" => "/articles/{id}",
            "controller" => "Article/ArticleDeleteController.php",
            "method" => "DELETE",
            "parameters" => [
              [
                "name" => "id",
                "type" => "int"
              ]
            ]
              ],

          [
             "name" => "article_by_category",
             "url" => "/articles/by_category/{id}",
             "controller" => "Article/ArticlesByCategoryGetController.php",
             "method" => "GET",
             "parameters" => [
             [
               "name" => "id",
               "type" => "int"
             ]
           ]
       ]

    
    ];
  }
}
