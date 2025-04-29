<?php

declare(strict_types=1);

namespace Corcel\WooCommerce\Traits;

use Closure;
use Corcel\Concerns\MetaFields;
use Corcel\Model\Meta\Meta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use InvalidArgumentException;
use LogicException;

/**
 * @template TRelatedModel of Model
 * @template TDeclaringModel of Model
 */
trait HasRelationsThroughMeta
{
    /**
     * Define a one-to-many relation through meta.
     *
     * @param  class-string<Model>  $related
     * @return HasMany<TRelatedModel, TDeclaringModel>
     *
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function hasManyThroughMeta(string $related, string $metaKey, ?string $foreignKey = null, ?string $localKey = null): HasMany
    {
        /** @var Model */
        $model = $this->newRelatedInstance($related);
        $meta = $this->metaInstance($model);

        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->getKeyName();

        return $this->hasMany($related, $meta->qualifyColumn('meta_value'))
            ->leftJoin($meta->getTable(), $this->joinClause(
                $model->qualifyColumn($localKey),
                $meta->qualifyColumn($foreignKey),
                $metaKey
            ))
            ->select($model->qualifyColumn('*'));
    }

    /**
     * Make meta model instance.
     *
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    private function metaInstance(Model $model): Meta
    {
        if (! method_exists($model, 'meta')) {
            throw new LogicException(sprintf(
                'The model "%s" must have defined "meta" method. Adding "%s" trait will likely solve this problem.',
                get_class($model),
                MetaFields::class
            ));
        }

        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<Model, Model> */
        $relation = $model->meta();
        $meta = $relation->getRelated();

        if (! $meta instanceof Meta) {
            throw new InvalidArgumentException(sprintf(
                'The meta method of "%s" model must extends "%s" model.',
                get_class($meta),
                Meta::class
            ));
        }

        return $meta;
    }

    /**
     * Build join clause between model and meta tables.
     */
    private function joinClause(string $localKey, string $foreignKey, string $metaKey): Closure
    {
        return function (JoinClause $join) use ($localKey, $foreignKey, $metaKey) {
            $join->on($localKey, '=', $foreignKey)
                ->where('meta_key', '=', $metaKey);
        };
    }
}
