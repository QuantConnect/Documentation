<?php

/**
 * Add if does not exist the a method to get the docs url key form the breadcrumbs
 */
if (function_exists('DOCS_URL')) {

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
        if ($key === null) {
            return join('/', $BREADCRUMBS);
        }
        return $BREADCRUMBS[$key];
    }
}