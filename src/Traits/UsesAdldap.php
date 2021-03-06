<?php

namespace Adldap\Laravel\Traits;

use Adldap\Laravel\Facades\Adldap;

trait UsesAdldap
{
    /**
     * Returns a new Adldap user query.
     *
     * @param string|null $provider
     * @param string|null $filter
     *
     * @return \Adldap\Query\Builder
     */
    protected function newAdldapUserQuery($provider = null, $filter = null)
    {
        $query = $this->getAdldap($provider)->search()->users();

        if ($filter = $this->getLimitationFilter() ?: $filter) {
            // If we're provided a login limitation filter,
            // we'll add it to the user query.
            $query->rawFilter($filter);
        }

        return $query->select($this->getSelectAttributes());
    }

    /**
     * Returns Adldap's current attribute schema.
     *
     * @return \Adldap\Contracts\Schemas\SchemaInterface
     */
    protected function getSchema()
    {
        return $this->getAdldap()->getSchema();
    }

    /**
     * Returns the root Adldap provider instance.
     *
     * @param string $provider
     *
     * @return \Adldap\Contracts\Connections\ProviderInterface
     */
    protected function getAdldap($provider = null)
    {
        $provider = $provider ?: $this->getDefaultConnectionName();

        return Adldap::getProvider($provider);
    }

    /**
     * Returns the configured select attributes when performing
     * queries for authentication and binding for users.
     *
     * @return array
     */
    protected function getSelectAttributes()
    {
        return config('adldap_auth.select_attributes', []);
    }

    /**
     * Returns the configured login limitation filter.
     *
     * @return string|null
     */
    protected function getLimitationFilter()
    {
        return config('adldap_auth.limitation_filter');
    }

    /**
     * Returns the configured default connection name.
     *
     * @return mixed
     */
    protected function getDefaultConnectionName()
    {
        return config('adldap_auth.connection', 'default');
    }
}
