<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class BaseBuilder extends Builder
{
    /**
     * Define si busca en los datos eliminados o no
     * @param $ctx = [
     *    null => 'Solo los activos',
     *    D => 'Solo los eliminados',
     *    A => 'Incluyendo eliminados'
     * ]
     */
    public function softDeleteScope(string $ctx = null): Builder
    {
        return $this->when($ctx === 'D', function ($query) {
            $query->onlyTrashed();
        })
            ->when($ctx === 'A', function ($query) {
                $query->withTrashed();
            });
    }
}
