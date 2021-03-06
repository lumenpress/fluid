<?php

namespace LumenPress\Nimble\Models;

use LumenPress\Nimble\Concerns\HasAliases;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    use HasAliases;

    /**
     * Unset an attribute on the model.
     *
     * @param string $key
     *
     * @return void
     */
    public function __unset($key)
    {
        $this->unsetAttribute($key);
    }

    public function __toString()
    {
        return '';
    }
}
