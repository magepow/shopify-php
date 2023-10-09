<?php

namespace App\Shopify\Admin\Graphql;

class Product extends Metafield
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

    /* https://shopify.dev/apps/online-store/media/products#uploading-media-to-shopify */
    public function createProductMedia()
    {
        $query = <<<GRAPHQL
        mutation createProductMedia {
            productCreateMedia(productId: \$id, media: [
            {
                originalSource: "https://storage.googleapis.com/shopify-video-production-core-originals/c/o/v/af64d230f6bc40cbba40a87be950a1a2.mp4?external_video_id=1730",
                alt: "Comparison video showing the different models of watches.",
                mediaContentType: VIDEO
            }
            ]) {
            media {
                ... fieldsForMediaTypes
                mediaErrors {
                code
                details
                message
                }
                mediaWarnings {
                code
                message
                }
            }
            product {
                id
            }
            mediaUserErrors {
                code
                field
                message
            }
            }
        }
        
        fragment fieldsForMediaTypes on Media {
            alt
            mediaContentType
            preview {
                image {
                    id
                }
            }
            status
            ... on Video {
                id
                sources {
                    format
                    height
                    mimeType
                    url
                    width
                }
            }
            ... on ExternalVideo {
                id
                host
                originUrl
            }
            ... on Model3d {
                sources {
                    format
                    mimeType
                    url
                }
                boundingBox {
                    size {
                        x
                        y
                        z
                    }
                }
            }
        }    
GRAPHQL;

        return $query;
    }

}
