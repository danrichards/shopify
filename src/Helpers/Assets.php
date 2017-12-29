<?php

namespace Dan\Shopify\Helpers;

use BadMethodCallException;
use Dan\Shopify\Models\Asset;
use Dan\Shopify\Models\Theme;
use Dan\Shopify\Shopify;

/**
 * Class Assets
 */
class Assets extends Endpoint
{

    /**
     * Get data using the `assets` endpoint
     *
     * @param  array   $payload
     * @param  string  $append
     * @return array
     */
    public function get($key = null)
    {
        $query = is_null($key) ? $key : ['asset' => compact('key')];

        return $this->api->get($query);
    }

    /**
     * Post to a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array|AbstractModel  $payload
     * @param  string  $append
     * @return array|AbstractModel
     * @throws BadMethodCallException
     */
    public function post($payload = [], $append = '')
    {
        // Only PUT is allowed on `Asset`
        $this->put($payload, $append);
    }

    /**
     * Delete a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  string  $id
     * @return array
     */
    public function delete($key)
    {
        return $this->api->delete(['asset' => compact('key')]);
    }

    /**
     * @param $id
     * @return Asset|null
     */
    public function find($key)
    {
        $data = $this->get($key);

        if (isset($data['asset'])) {
            $data = $data['asset'];
        }

        if (empty($data)) {
            return null;
        }

        $model = new Asset($data);
        $model->exists = true;

        return $model;
    }

    /**
     * Return an array of models or Collection (if Laravel present)
     *
     * @param string|array $ids
     * @param string $append
     * @return array|\Illuminate\Support\Collection
     */
    public function findMany($keys)
    {
        throw new BadMethodCallException('%s does not support findMany()', __CLASS__);
    }

    /**
     * PUT to `assets` endpoint using a `Asset` model
     *
     * @param  AbstractModel  $model
     * @param  string  $append
     * @return AbstractModel
     */
    public function save(Asset $model)
    {
        $response = $this->request(
            $method = 'PUT',
            $uri = $this->endpoint(),
            $options = ['json' => $model]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data[$model::$resource_name])) {
            $data = $data[$model::$resource_name];
        }

        $model->exists = true;
        $model->syncOriginal($data);

        return $model;
    }

    /**
     * @param Asset $model
     * @return bool
     */
    public function destroy(Asset $model)
    {
        $this->delete($model->getKey());
    }

}
