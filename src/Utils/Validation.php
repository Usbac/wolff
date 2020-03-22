<?php

namespace Wolff\Utils;

use Wolff\Utils\Str;

class Validation
{

    /**
     * The data to evaluate.
     *
     * @var array
     */
    private $data = [];

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


    /**
     * Sets the data array to validate
     *
     * @param  array  $data  the data array
     *
     * @return self
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }


    /**
     * Sets the fields rules
     *
     * @param  array  $fields  the associative array
     * with the fields rules
     *
     * @return self
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }


    /**
     * Returns an associative array with all the invalid values.
     * This method runs the isValid method.
     *
     * @return array an associative array with all the invalid values
     */
    public function getInvalidValues()
    {
        $this->isValid();
        return $this->invalid_values;
    }


    /**
     * Adds an invalid value
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
        $field = $this->data[$key] ?? null;

        foreach ($this->fields[$key] as $rule => $val) {
            $rule = trim(strtolower($rule));

            //Complies min length, max length, min value, max value and regex
            if (($rule == 'minlen' && strlen($field) < $val) ||
                ($rule == 'maxlen' && strlen($field) > $val) ||
                ($rule == 'minval' && $field < $val) ||
                ($rule == 'maxval' && $field > $val) ||
                ($rule == 'regex' && !preg_match($val, $field))) {
                $this->addInvalidValue($key, $rule);
            }

            //Complies type
            if ($rule == 'type') {
                if (($val == 'email' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) ||
                    ($val == 'alphanumeric' && !preg_match('/[A-Za-z0-9 ]+$/', $str)) ||
                    ($val == 'alpha' && !preg_match('/[A-Za-z ]+$/', $str)) ||
                    ($val == 'int' && !isInt($field)) ||
                    ($val == 'float' && !isFloat($field)) ||
                    ($val == 'bool' && !isBool($field))) {
                    $this->addInvalidValue($key, $rule);
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
    public function isValid()
    {
        foreach ($this->fields as $key => $val) {
            $this->validateField($key);
        }

        return empty($this->invalid_values);
    }


    /**
     * Returns true if the given data matches the fields.
     * This is a proxy method to the setFields, setData
     * and isValid methods (in that order)
     *
     * @param  array  $fields  the associative array
     * with the fields rules
     *
     * @param array $data the data array to validate
     *
     * @return bool true if the current data complies all the fields rules,
     * false otherwise
     */
    public function check($fields, $data)
    {
        return $this->setFields($fields)
                    ->setData($data)
                    ->isValid();
    }

}
