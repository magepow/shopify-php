<?php

namespace App\Shopify\Admin\Graphql;

class Product extends Metafield
{
    /*
    * https://shopify.dev/custom-storefronts/products-collections/getting-started
    * POST https://{shop}.myshopify.com/api/{api_version}/graphql.json
    * requirement @var [
    *        "term"   => "string_text",
    *        "limit" => 50,
    *   ]
    */
    public function searchProduct() 
    {
        $query = <<<GRAPHQL
        query(\$term: String!, \$limit: Int){
            products(first: \$limit , query: \$term) {
                pageInfo {
                    hasNextPage
                    hasPreviousPage
                }
                edges {
                    # cursor
                    node {
                        id
                        title
                        handle
                        featuredImage {
                            url
                            #transformedSrc
                        }
                    }
                }
            }
        }
GRAPHQL;

        return $query;
    }

    public function getProductByHandle()
    {
        $query = <<<GRAPHQL
        query getProductByHandle(\$handle: String!) {
            products(first: 1, query: "handle: \$handle"){
                id
                handle
                title
                tags
                productType
                vendor                
            }
        }
GRAPHQL;

        $query = <<<GRAPHQL
            query getProductByHandle(\$handle: String!) {
                product(handle: \$handle) {
                    id
                    handle
                    title
                    tags
                    productType
                    vendor
                }
            }
GRAPHQL;

        $query = <<<GRAPHQL
        query getProductByHandle(\$handle: String!) {
            productByHandle(handle: \$handle) {
                id
                handle
                title
                tags
                productType
                vendor
            }
        }
GRAPHQL;

        return $query;
    }
    public function getProductById()
    {
        $query = <<<GRAPHQL
        {
            product(id: \$id) {
                title
                metafield(namespace: "my_fields", key: "image") {
                    reference {
                        ... on MediaImage {
                            image {
                                originalSrc
                            }
                        }
                    }
                }
            }
        }
GRAPHQL;

        return $query;
    }

}
