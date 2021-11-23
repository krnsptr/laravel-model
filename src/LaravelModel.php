<?php

namespace Krnsptr\LaravelModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class LaravelModel extends Model
{
    public ?string $modelLabel;

    protected array $fieldLabels = [
        'id' => 'ID',
    ];

    protected function getData(): array
    {
        return $this->getAttributes();
    }

    protected function getRules(): array
    {
        return [];
    }

    public function getValidator(): Validator
    {
        return ValidatorFacade::make(
            $this->getData(),
            $this->getRules(),
            [],
            $this->fieldLabels,
        );
    }

    public function save(array $options = []): bool
    {
        $this->getValidator()->validate();

        return parent::save($options);
    }
}
