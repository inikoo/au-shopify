<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 12 May 2021 21:51:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */


namespace Spatie\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;

class FiltersPartial extends FiltersExact implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($this->addRelationConstraint) {
            if ($this->isRelationProperty($query, $property)) {
                $this->withRelationConstraint($query, $value, $property);

                return false;
            }
        }

        $wrappedProperty = $query->getQuery()->getGrammar()->wrap($query->qualifyColumn($property));

        $sql = "LOWER($wrappedProperty) LIKE ? COLLATE \"tr-TR-x-icu\" ";

        if (is_array($value)) {
            if (count(array_filter($value, 'strlen')) === 0) {
                return $query;
            }

            $query->where(function (Builder $query) use ($value, $sql) {
                foreach (array_filter($value, 'strlen') as $partialValue) {
                    $partialValue = mb_strtolower($partialValue, 'UTF8');

                    $query->orWhereRaw($sql, ["%$partialValue%"]);
                }
            });

            return false;
        }

        $value = mb_strtolower($value, 'UTF8');


        $query->whereRaw($sql, ["%$value%"]);

        return false;
    }
}



