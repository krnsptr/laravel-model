<?php

namespace Krnsptr\LaravelModel;

use Based\Fluent\Fluent;
use Deiucanta\Smart\SmartModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class LaravelModel extends Model
{
    use Fluent;
    use SmartModel;

    public ?string $modelLabel;

    protected array $fieldLabels = [
        'id' => 'ID',
    ];

    public function __construct(array $attributes = [])
    {
        $this->initSmartFields();

        parent::__construct($attributes);
    }

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
