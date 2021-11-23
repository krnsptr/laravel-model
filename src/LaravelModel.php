<?php

namespace Krnsptr\LaravelModel;

use Based\Fluent\Fluent;
use Deiucanta\Smart\Field;
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

    protected function getLabels(): array
    {
        $smartFieldLabels = $this->getSmartFields()->map(function (Field $field) {
            return $field->label;
        })->toArray();

        return array_merge($smartFieldLabels, $this->fieldLabels);
    }

    protected function getValidatorData($skip = [])
    {
        $values = $rules = $labels = [];
        $fields = $this->getSmartFields();

        foreach ($fields as $key => $field) {
            if (in_array($key, $skip)) {
                continue;
            }

            if ($field->validateRawValue && !isset($this->rawAttributes[$key])) {
                continue;
            }

            $fieldRules = $field->getValidationRules($this);

            if (count($fieldRules) === 0) {
                continue;
            }

            $rules[$key] = $fieldRules;

            $values[$key] = isset($this->rawAttributes[$key])
                ? $this->rawAttributes[$key]
                : ($this->getData()[$key] ?? $this->getAttribute($key));

            $labels[$key] = isset($field->label) ? $field->label : $key;
        }

        return compact('values', 'rules', 'labels');
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
