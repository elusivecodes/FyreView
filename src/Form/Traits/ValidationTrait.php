<?php
declare(strict_types=1);

namespace Fyre\View\Form\Traits;

use Fyre\Validation\Validator;

use function count;
use function max;
use function min;

/**
 * ValidationTrait
 */
trait ValidationTrait
{
    /**
     * Get the maximum value.
     *
     * @param Validator $validator The Validator.
     * @param string $field The field name.
     * @return float|null The maximum value.
     */
    public static function getValidationMax(Validator $validator, string $field): float|null
    {
        $rules = $validator->getFieldRules($field);

        $max = null;
        foreach ($rules as $rule) {
            $ruleMax = null;
            switch ($rule->getName()) {
                case 'between':
                    $arguments = $rule->getArguments();

                    if (count($arguments) === 2) {
                        $ruleMax = $arguments[1];
                    }
                    break;
                case 'lessThan':
                    $arguments = $rule->getArguments();

                    if ($arguments !== []) {
                        $ruleMax = $arguments[0] - 1;
                    }
                    break;
                case 'lessThanOrEquals':
                    $arguments = $rule->getArguments();

                    if ($arguments !== []) {
                        $ruleMax = $arguments[0];
                    }
                    break;
            }

            if ($ruleMax === null) {
                continue;
            }

            $max ??= $ruleMax;
            $max = min($max, $ruleMax);
        }

        return $max;
    }

    /**
     * Get the maximum length.
     *
     * @param Validator $validator The Validator.
     * @param string $field The field name.
     * @return int|null The maximum length.
     */
    public static function getValidationMaxLength(Validator $validator, string $field): int|null
    {
        $rules = $validator->getFieldRules($field);

        $maxLength = null;
        foreach ($rules as $rule) {
            $ruleMaxLength = null;
            switch ($rule->getName()) {
                case 'maxLength':
                    $arguments = $rule->getArguments();

                    if ($arguments !== []) {
                        $ruleMaxLength = $arguments[0];
                    }
                    break;
            }

            if ($ruleMaxLength === null) {
                continue;
            }

            $maxLength ??= $ruleMaxLength;
            $maxLength = min($maxLength, $ruleMaxLength);
        }

        return $maxLength;
    }

    /**
     * Get the minimum value.
     *
     * @param Validator $validator The Validator.
     * @param string $field The field name.
     * @return float|null The minimum value.
     */
    public static function getValidationMin(Validator $validator, string $field): float|null
    {
        $rules = $validator->getFieldRules($field);

        $min = null;
        foreach ($rules as $rule) {
            $ruleMin = null;
            switch ($rule->getName()) {
                case 'between':
                    $arguments = $rule->getArguments();

                    if (count($arguments) === 2) {
                        $ruleMin = $arguments[0];
                    }
                    break;
                case 'greaterThan':
                    $arguments = $rule->getArguments();

                    if ($arguments !== []) {
                        $ruleMin = $arguments[0] + 1;
                    }
                    break;
                case 'greaterThanOrEquals':
                    $arguments = $rule->getArguments();

                    if ($arguments !== []) {
                        $ruleMin = $arguments[0];
                    }
                    break;
            }

            if ($ruleMin === null) {
                continue;
            }

            $min ??= $ruleMin;
            $min = max($min, $ruleMin);
        }

        return $min;
    }

    /**
     * Determine if the field is required.
     *
     * @param Validator $validator The Validator.
     * @param string $field The field name.
     * @return bool TRUE if the field is required, otherwise FALSE.
     */
    public static function isValidationRequired(Validator $validator, string $field): bool
    {
        $rules = $validator->getFieldRules($field);

        foreach ($rules as $rule) {
            if (!$rule->skipEmpty()) {
                return true;
            }
        }

        return false;
    }
}
