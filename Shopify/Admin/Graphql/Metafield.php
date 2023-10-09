<?php

namespace App\Shopify\Admin\Graphql;

class Metafield
{

    /*
    * requirement @var ['input' => [
    *        "id"   => "gid://shopify/Metafield/1063298196",
    *   ]]
    */
    public function metafieldDelete() 
    {
        $query = <<<GRAPHQL
        mutation metafieldDelete(\$input: MetafieldDeleteInput!) {
            metafieldDelete(input: \$input) {
                deletedId
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
