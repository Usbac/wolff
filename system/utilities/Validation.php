<?php

namespace Utilities;

use Core\Request;
use Utilities\Str;

class Validation
{

    /**
     * The data to evaluate.
     *
     * @var array
     */
    private $data = [];

    /**
     * The request method data to evaluate.
     *
     * @var array
     */
    private $type;

    /**
     * The fields rules.
     *
     * @var array
     */
    private $fields = [];

    /**
     * The fields rules.
     *
     * @var array
     */
    private $invalid_values = [];

    const HTTP_METHODS = [
        'GET',
        'POST'
    ];


    /**
     * Set the data array to validate
     *
     * @param  array  $data  the data array
     *
     * @return Core\Validation this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }


    /**
     * Returns the data array to validate
     *
     * @return array the data array to validate
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Set a request method array as the data to validate
     *
     * @param  string  $type  the method type
     *
     * @return Core\Validation this
     */
    public function setType(string $type)
    {
        if (in_array(strtoupper($type), self::HTTP_METHODS)) {
            $this->data = Request::{strtolower($type)}();
        }

        return $this;
    }


    /**
     * Set the fields rules
     *
     * @param  array  $fields  the associative array
     * with the fields rules
     *
     * @return Core\Validation this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }


    /**
     * Returns an associative array with all the invalid values
     *
     * @return array an associative array with all the invalid values
     */
    public function getInvalidValues()
    {
        return $this->invalid_values;
    }


    /**
     * Add an invalid value
     *
     * @param  string  $key  the value key
     * @param  string  $field  the field that the value doesn't complies
     */
    private function addInvalidValue(string $key, string $field)
    {
        $this->invalid_values[$key][] = $field;
    }


    /**
     * Returns true if the given data key value matches
     * the current rules, false otherwise
     *
     * @param  string  $key  the data key value to evaluate
     * with the field rules
     *
     * @return bool true if the given data key value matches
     * the current rules, false otherwise
     */
    private function validateField(string $key)
    {
        $rules = explode(',', $this->fields[$key]);
        $val = $this->data[$key] ?? null;

        foreach ($rules as $rule) {
            $rule = explode('=', $rule);
            $rule[0] = trim(strtolower($rule[0]));

            //Complies required
            if ($rule[0] == 'required' && !isset($val)) {
                $this->addInvalidValue($key, $rule[0]);
            }

            if (!isset($rule[1]) || !isset($val)) {
                continue;
            }

            //Complies min length, max length, min value, max value and regex
            if (($rule[0] == 'minlen' && strlen($val) < $rule[1]) ||
                ($rule[0] == 'maxlen' && strlen($val) > $rule[1]) ||
                ($rule[0] == 'minval' && $val < $rule[1]) ||
                ($rule[0] == 'maxval' && $val > $rule[1]) ||
                ($rule[0] == 'regex' && !preg_match($rule[1], $val))) {
                $this->addInvalidValue($key, $rule[0]);
            }

            //Complies type
            if ($rule[0] == 'type') {
                if (($rule[1] == 'email' && !Str::isEmail($val)) ||
                    ($rule[1] == 'alphanumeric' && !Str::isAlphanumeric($val)) ||
                    ($rule[1] == 'letters' && !Str::isAlpha($val)) ||
                    ($rule[1] == 'int' && !isInt($val)) ||
                    ($rule[1] == 'float' && !isFloat($val)) ||
                    ($rule[1] == 'bool' && !isBool($val))) {
                    $this->addInvalidValue($key, $rule[0]);
                }
            }
        }
    }


    /**
     * Returns true if the current data complies all the fields rules,
     * false otherwise
     *
     * @return bool true if the current data complies all the fields rules,
     * false otherwise
     */
    public function validate()
    {
        foreach ($this->fields as $key => $val) {
            $this->validateField($key);
        }

        return empty($this->invalid_values);
    }
}
