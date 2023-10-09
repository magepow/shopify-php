<?php

namespace App\Shopify\Admin\Graphql;

class App extends Metafield
{
    /*
    * requirement @var [
    *        "apiKey" => "apikey_string"
    *   ]
    */

    public function getAppByKey()
    {
        $query = <<<GRAPHQL
        query getAppInfo(apiKey: String!) {
            appByKey(apiKey: \$apiKey) {
                id
                apiKey
                appStoreAppUrl
                appStoreDeveloperUrl
                embedded
                handle
                features
                developerUrl
                description
                developerName
                developerType
                installUrl
                launchUrl
                isPostPurchaseAppInUse
                pricingDetails
                pricingDetailsSummary
                privacyPolicyUrl
                publicCategory
                published
                title
                uninstallUrl
            }
        }
GRAPHQL;

        return $query;
    }


    /*
    * requirement @var [
    *        "id" => "gid://shopify/App/13697449985"
    *   ]
    */
    public function getAppById()
    {
        $query = <<<GRAPHQL
        query getAppInfo(id: ID!) {
            app(id: \$id) {
                appStoreAppUrl
                appStoreDeveloperUrl
                installUrl
                launchUrl
                apiKey
                embedded
                developerUrl
                developerType
                developerName
                description
                id
                published
                title
                webhookApiVersion
                uninstallUrl
                shopifyDeveloped
            }
        }
GRAPHQL;
  
        return $query;
    }

    public function getCurrentAppInfo()
    {
        $query = <<<GRAPHQL
        query getCurrentApp {
            currentAppInstallation {
                id
                app {
                    apiKey
                    appStoreAppUrl
                    embedded
                    features
                    handle
                    id
                    installUrl
                    isPostPurchaseAppInUse
                    launchUrl
                    pricingDetails
                    pricingDetailsSummary
                    privacyPolicyUrl
                    published
                    title
                }
            }
        }  
GRAPHQL;

        return $query;
    } 

    public function getCurrentAppInstallation() 
    {
        $query = <<<QUERY
        query {
            currentAppInstallation {
                id
            }
        }
QUERY;

        return $query;
    }

    /*
    * requirement @var ['metafields' => [
    *        "namespace" => "magepowapps",
    *        "key" => "lookbook",
    *        // "type" => "single_line_text_field",
    *        "type" => "multi_line_text_field",
    *        // "type" => "json",
    *        "value" => "$value",
    *        "ownerId" => "$appInstallationId"  
    *   ]]
    */
    public function createAppOwnedMetafield() 
    {
        $query = <<<GRAPHQL
        mutation CreateAppOwnedMetafield(\$metafields: [MetafieldsSetInput!]!) {
            metafieldsSet(metafields: \$metafields) {
              metafields {
                id
                namespace
                key
                value
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
    * requirement @var ['metafields' => [
    *        "namespace" => "magepowapps",
    *        "key" => "lookbook"
    *   ]]
    */
    public function getMetafieldAppInstallation() 
    {
        $query = <<<GRAPHQL
        query(\$namespace: String!, \$key: String!) {
            currentAppInstallation {
                metafield(namespace: \$namespace, key: \$key){
                    id
                    type
                    namespace
                    key
                    value
                    reference {
                        ... on GenericFile {
                          id
                          url
                        }
                        ... on MediaImage {
                          id
                          image {
                            transformedSrc
                            url
                            src
                          }
                        }
                      }      
                }
            }
        }
GRAPHQL;

        return $query;
    }

    /*
    * requirement @var [
    *        "namespace" => "magepowapps",
    *        "limit" => 50
    *   ]
    */
    public function getMetafieldsAppInstallation() 
    {
        $query = <<<GRAPHQL
        query(\$namespace: String!, \$limit: Int) {
            currentAppInstallation {
                metafields(first: \$limit, namespace: \$namespace) {
                    edges {
                        node {
                            id
                            namespace
                            key
                            value
                        }
                    }
                }
            }
        }
GRAPHQL;

        return $query;
    }

    /* ########################## */
    /* query for privateMetafield */
    /* ########################## */

    /*
    * requirement @var ['input' => [
            "owner" => "gid://shopify/Product/8159048204528",
            "namespace" => "magepowapps",
            "key" => "lookbook",
            "valueInput" => [
                "value" => "Private Metafield AppInstallation Text",
                "valueType" => "STRING"
            ]
        ]]
    */
    public function createPrivateMetafield() 
    {
        $query = <<<GRAPHQL
        mutation(\$input: PrivateMetafieldInput!) {
            privateMetafieldUpsert(input: \$input) {
              privateMetafield {
                namespace
                key
                value
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
    * requirement @var ['metafields' => [
    *        "id"   => "gid://shopify/Product/8159048204528",
    *        "namespace" => "magepowapps",
    *        "key" => "lookbook"
    *   ]]
    */

    public function getPrivateMetafield() 
    {
        // query {
        //     product(id: "gid://shopify/Product/8159048204528") {
        //         privateMetafield(namespace: "magepowapps", key: "lookbook") {
        //             value
        //         }
        //     }
        // }
        $query = <<<GRAPHQL
        query(\$id: ID!, \$namespace: String!, \$key: String!){
            product(id: \$id) {
                privateMetafield(namespace: \$namespace, key: \$key) {
                    value
                }
            }
        }
GRAPHQL;

        return $query;
    }

    /* ########################## */
    /* End query for privateMetafield */
    /* ########################## */

}
