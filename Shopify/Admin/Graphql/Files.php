<?php

namespace App\Shopify\Admin\Graphql;

class Files
{

    /*
    * refer: https://shopify.dev/api/admin-graphql/2022-10/mutations/filecreate
    * requirement @var ['files' => [
    *        "alt" => "alt text"
    *        "contentType" => "IMAGE"
    *        "originalSource" => "https://example.com/image.jpg"
    *   ]]
    */
    
    public function fileCreate()
    {
        $query = <<<GRAPHQL
        mutation fileCreate(\$files: [FileCreateInput!]!) {
            fileCreate(files: \$files) {
                files {
                    __typename
                    alt
                    createdAt
                    fileStatus
                    fileErrors {
                        code
                        message
                        details
                    }
                    ... on MediaImage {
                        id
                        image {
                            transformedSrc
                        }
                    }
                    ... on GenericFile {
                        url
                    } 
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

    /*
    * refer: https://shopify.dev/api/usage/bulk-operations/imports#upload-the-file-to-shopify
    * requirement @var ['files' => [
    *        "alt" => "alt text"
    *        "contentType" => "IMAGE"
    *        "originalSource" => "https://example.com/image.jpg"
    *   ]]
    */
    public function stagedUploadsCreate($var=true)
    {
        $query = <<<GRAPHQL
        mutation {
            stagedUploadsCreate(input:{
                resource: BULK_MUTATION_VARIABLES,
                filename: "bulk_op_vars",
                mimeType: "text/jsonl",
                httpMethod: POST
            }){
                userErrors{
                    field,
                    message
                },
                stagedTargets{
                    url,
                    resourceUrl,
                    parameters {
                        name,
                        value
                    }
                }
            }
        }
GRAPHQL;

        $queryVar = <<<GRAPHQL
        mutation stagedUploadsCreate(\$input: [StagedUploadInput!]!) {
            stagedUploadsCreate(input: \$input) {
                stagedTargets {
                    # StagedMediaUploadTarget fields
                    url,
                    resourceUrl,
                    parameters {
                        name,
                        value
                    }
                }
                userErrors {
                    field
                    message
                }
            }
        }
GRAPHQL;

        return $var ? $queryVar : $query;
    }
    
    /* https://shopify.dev/api/admin-graphql/2022-10/mutations/fileupdate */
    public function fileUpdate()
    {
        $query = <<<GRAPHQL
        mutation fileUpdate(\$files: [FileUpdateInput!]!) {
            fileUpdate(files: \$files) {
                files {
                    # File fields
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
    /*
    * requirement @var ['id' => $gid]
    */
    public function getFileUrl()
    {
        $query = <<<GRAPHQL
        query getFileUrl(\$id: ID!) {
            node(id: \$id) {
                __typename
                ... on GenericFile {
                    id
                    url
                }
                ... on MediaImage {
                    id
                    image {
                        url
                        originalSrc
                    }
                }
            }
        }
GRAPHQL;

        return $query;
    }

    /*
    * refer: https://shopify.dev/apps/online-store/media/products#step-1-upload-media-to-shopify
    */
    public function generateStagedUploads()
    {
        $query = <<<GRAPHQL
        mutation generateStagedUploads {
            stagedUploadsCreate(input: [
                {
                    filename: "watches_comparison.mp4",
                    mimeType: "video/mp4",
                    resource: VIDEO,
                    fileSize: "899765"
                },
                {
                    filename: "another_watch.glb",
                    mimeType: "model/gltf-binary",
                    resource: MODEL_3D,
                    fileSize: "456"
                }
            ])
            {
                stagedTargets {
                    url
                    resourceUrl
                    parameters {
                        name
                        value
                    }
                }
                userErrors {
                    field, message
                }
            }
        }
GRAPHQL;
        return $query;
    }

    public function searchImage()
    {
        $query = <<<GRAPHQL
        query {
          files(first: \$limit, query: "filename:\$name, media_type:IMAGE") {
            edges {
                node {
                    createdAt
                    alt
                    ... on GenericFile {
                        id
                        #originalFileSize
                        url
                    }
                    ... on MediaImage {
                        id
                        #originalFileSize
                        image {
                            id
                            originalSrc: url
                            width
                            height
                        }
                    }
                    ... on Video {
                        id
                        #originalFileSize
                        duration
                        preview {
                            status
                            image {
                                id
                                width
                                height
                                url
                            }
                        }
                        originalSource {
                            url
                            width
                            height
                            format
                            mimeType
                        }
                        sources {
                            url
                            width
                            height
                            format
                            mimeType
                        }
                    }
                }
            }
        }
GRAPHQL;

        return $query;
    }
    /*
        files(first: 10,  query: "filename:\$name, media_type:IMAGE" )
    */
    public function getFiles()
    {
        $query = <<<GRAPHQL
        query {
            files(first: \$limit,  query: \$term ) {
                edges {
                    node {
                        createdAt
                        alt
                        ... on GenericFile {
                            id
                            #originalFileSize
                            url
                        }
                        ... on MediaImage {
                            id
                            #originalFileSize
                            image {
                                id
                                originalSrc: url
                                width
                                height
                            }
                        }
                        ... on Video {
                            id
                            #originalFileSize
                            duration
                            preview {
                                status
                                image {
                                id
                                width
                                height
                                url
                            }
                        }
                        originalSource {
                            url
                            width
                            height
                            format
                            mimeType
                        }
                        sources {
                            url
                            width
                            height
                            format
                            mimeType
                        }
                    }
                }
            }
        }
GRAPHQL;

        return $query;
    }

}
