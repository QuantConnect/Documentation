<?php
/**
 * Available global variables
 *
 * $BREADCRUMBS    Array<String>, Example ["cloud-platform","organizations","data-storage"]
 * $ANCHOR         String, Example "#remove-option-contract"
 * $DOCS_RESOURCES String path to the docs resources folder
 * $DOCS_ROOT      String path to the docs root folder
 *
 */

/**
 * Add if does not exist the a method to get the docs url key form the breadcrumbs
 */
if (!function_exists('DOCS_URL')) {

    /**
     * Example breadcrumb: ["cloud-platform","organizations","data-storage"]
     *
     * DOCS_URL() => "cloud-platform/organizations/data-storage"
     * DOCS_URL(0) => "cloud-platform"
     *
     * @return mixed
     */
    function DOCS_URL($key = null)
    {
        global $BREADCRUMBS;
        if ($key === null) {
            return join('/', $BREADCRUMBS);
        }
        return $BREADCRUMBS[$key];
    }
}

/**
 * Here you can add variables to the environment
 */
$isCloud = DOCS_URL(0) == 'cloud-platform';