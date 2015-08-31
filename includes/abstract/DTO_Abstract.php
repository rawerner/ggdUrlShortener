<?php

abstract class DTO_Abstract
{
    public $errorStack = array();

    public function __get($property_name)
    {
        return $this->{$property_name};
    }

    public function __set($property_name, $new_value)
    {
        $this->{$property_name} = $new_value;
    }

    public function __isset($property_name)
    {
        return isset($this->{$property_name});
    }

    public function __unset($property_name)
    {
        unset($this->{$property_name});
    }

    public function __call($methodName, $arguments)
    {
        // Expected Format getPropertyName/setPropertyName
        $class = get_class($this);
        $options = $class::$options;
        $type = strtolower(substr($methodName, 0, 3));
        $property_name = strtolower(substr($methodName, 3));

        if (in_array($type, array('get', 'set')) && property_exists($this, $property_name)) {
            switch ($type) {
                case 'set':
                    if (count($arguments) == 1) {
                        // Format for save
                        $formatted = $arguments[0];
                        if (isset($options['filters'][$property_name]['save']) && !empty($options['filters'][$property_name]['save'])) {
                            // Regex Filtering Only
                            $filter = $options['filters'][$property_name]['save'];
                            $formatted = preg_replace($filter['pattern'], $filter['replace'], $formatted);
                        }
                        return $this->{$property_name} = $formatted;
                    } else {
                        throw new Exception("Invalid Argument count '" . count($arguments) . "', '1' expected", 418);
                    }
                    break;
                case 'get':
                    // Check Formatting for display
                    $formatted = $this->{$property_name};
                    if (isset($options['filters'][$property_name]['load']) && !empty($options['filters'][$property_name]['load'])) {
                        // Regex Filtering Only
                        $filter = $options['filters'][$property_name]['load'];
                        $formatted = preg_replace($filter['pattern'], $filter['replace'], $formatted);
                    }
                    return $formatted;
                    break;
            }
        } else {
            throw new Exception("Property '{$property_name}' does not exist for context '{$type}'.", 418);
        }
        // Calls should never get here.
        return null;
    }

    public function createDTOFromArray($array)
    {
        foreach ($array as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
        // Reset Error Stack after loading from DB
        $this->errorStack = array();
        return $this;
    }

    public function createDTOFromPost($array)
    {
        foreach ($array as $property => $value) {
            if (property_exists($this, $property)) {
                $setter = 'set' . $property;
                $this->{$setter}($value);
            }
        }
        // Reset Error Stack after loading from post.
        $this->errorStack = array();
        return $this;
    }

    /**
     * Is DTO Valid?
     * @return bool
     */
    public function isValid()
    {
        $class = get_class($this);
        $options = $class::$options;
        $is_valid = true;
        // Validate Object
        foreach ($options['validations'] as $property_name => $validation) {
            $value = $this->{$property_name};
            if (!empty($options['validations'][$property_name])) {
                $validators = $options['validations'][$property_name];
                foreach ($validators as $validator => $constraint) {
                    if (!$this->{$validator}($property_name, $constraint, $value)) {
                        $is_valid = false;
                    }
                }
            }
        }
        return $is_valid;
    }

    /**
     * Minimum Length Check - Inclusive
     * @param $property_name
     * @param $constraint
     * @param $property_value
     * @return bool
     */
    public function minLength($property_name, $constraint, $property_value)
    {
        if (strlen($property_value) >= $constraint) {
            return true;
        } else {
            $this->errorStack[] = "Property '{$property_name}' cannot be shorter than '{$constraint}' length.";
            return false;
        }
    }

    /**
     * Maximum Length Check - Inclusive
     * @param $property_name
     * @param $constraint
     * @param $property_value
     * @return bool
     */
    public function maxLength($property_name, $constraint, $property_value)
    {
        if (strlen($property_value) <= $constraint) {
            return true;
        } else {
            $this->errorStack[] = "Property '{$property_name}' cannot be longer than '{$constraint}' length.";
            return false;
        }
    }

    /**
     * Validator for Not Null
     * @param $property_name
     * @param $constraint
     * @param $property_value
     * @return bool
     */
    public function notNull($property_name, $constraint, $property_value)
    {
        if ($constraint) {
            if (isset($property_value) && !empty($property_value)) {
                return true;
            } else {
                $this->errorStack[] = "Property '{$property_name}' cannot be null";
                return false;
            }
        }
        // Always return true if notNull is set to false.
        // This means it doesn't matter if it's null or not.
        return true;
    }

    /**
     * Check for Valid Email
     * @param $property_name
     * @param $constraint
     * @param $property_value
     * @return bool
     */
    public function validEmail($property_name, $constraint, $property_value)
    {
        // Constraint doesn't matter here, we validate email against the RFC.
        if (filter_var($property_value, FILTER_VALIDATE_EMAIL)) {
            // Valid Email!
            return true;
        } else {
            $this->errorStack[] = "The value '{$property_value}' is not a valid email.";
            return false;
        }
    }
}