<?php

namespace App\Shopify\Admin\Graphql;

class Shop
{
    /*
    * requirement @var [
    *        "term"   => "string_text",
    *        "limit" => 50,
    *   ]
    */
    public function searchProduct() 
    {
        $query = <<<GRAPHQL
        query(\$term: String!, \$limit: Int){
            shop {
                search(query: \$term, types: PRODUCT, first: \$limit) {
                    edges {
                        node {
                            reference {
                                id
                            }
                            title
                            image {
                                originalSrc
                                id
                            }
                            description
                        }
                        #cursor
                    }
                    pageInfo {
                        hasNextPage
                        hasPreviousPage
                    }
                }
            }
        }
GRAPHQL;

        return $query;
    }

    /*
    * refer https://www.codeshopify.com/blog_posts/how-to-access-metafields-with-the-storefront-api
    * requirement @var ['input' => [
    *           "namespace" => "magepowapps",
    *           "key" => "meta_example",
    *           "ownerType" => "SHOP"
    *   ]]
    */
    public function createMetafieldStorefrontVisibility()
    {
        $query = <<<GRAPHQL
        mutation CreateMetafieldStorefrontVisibility(\$input: MetafieldStorefrontVisibilityInput) {
            metafieldStorefrontVisibilityCreate(input: \$input) {
                metafieldStorefrontVisibility {
                    namespace
                    key
                }
                userErrors {
                    field
                    message
                }
            }
        }
GRAPHQL;

        return $query;
    }

}
