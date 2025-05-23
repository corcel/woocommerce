<?php

declare(strict_types=1);

namespace Tests\Unit\Traits;

use Corcel\Model;
use Corcel\WooCommerce\Traits\HasRelationsThroughMeta;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;
use LogicException;
use Tests\TestCase;

class HasRelationsThroughMetaTest extends TestCase
{
    public function test_invalid_model(): void
    {
        $this->expectException(LogicException::class);

        $model = new class extends Model
        {
            /** @use HasRelationsThroughMeta<Model, Model> */
            use HasRelationsThroughMeta;

            /**
             * @return HasMany<Model, Model>
             */
            public function relatedObjects(): HasMany
            {
                return $this->hasManyThroughMeta(Model::class, '_meta_key', 'foreign_key', 'local_key');
            }
        };

        $model->relatedObjects; // @phpstan-ignore-line
    }

    public function test_invalid_meta(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $model = new class extends Model
        {
            /** @use HasRelationsThroughMeta<Model, Model> */
            use HasRelationsThroughMeta;

            /** @var \Corcel\Model */
            private $relatedModel;

            public function setRelatedModel(Model $relatedModel): void
            {
                $this->relatedModel = $relatedModel;
            }

            /**
             * @return HasMany<Model, Model>
             */
            public function relatedObjects(): HasMany
            {
                return $this->hasManyThroughMeta(get_class($this->relatedModel), '_meta_key', 'foreign_key', 'local_key');
            }
        };

        $model->setRelatedModel(new class extends Model
        {
            /**
             * @return HasMany<\Corcel\Model, Model>
             */
            public function meta(): HasMany
            {
                return $this->hasMany(Model::class);
            }
        });

        $model->relatedObjects; // @phpstan-ignore-line
    }
}
