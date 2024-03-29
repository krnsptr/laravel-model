<?php

namespace Krnsptr\LaravelModel;

use Deiucanta\Smart\Field;
use Deiucanta\Smart\SmartModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class LaravelModel extends Model
{
    use SmartModel;

    public $modelLabel = null;

    public $fieldLabels = [
        'id' => 'ID',
    ];

    public function __construct(array $attributes = [])
    {
        $this->initSmartFields();

        parent::__construct($attributes);
    }

    protected function getData(): array
    {
        return $this->attributes;
    }

    protected function getRules(): array
    {
        return [];
    }

    protected function getLabels(): array
    {
        $smartFieldLabels = $this->getSmartFields()->map(function (Field $field) {
            return $field->label;
        })->toArray();

        return array_merge($smartFieldLabels, $this->fieldLabels);
    }

    public function getValidator(): Validator
    {
        return ValidatorFacade::make(
            $this->getData(),
            $this->getRules(),
            [],
            $this->getLabels(),
        );
    }

    public function save(array $options = []): bool
    {
        $this->getValidator()->validate();

        return parent::save($options);
    }
}
