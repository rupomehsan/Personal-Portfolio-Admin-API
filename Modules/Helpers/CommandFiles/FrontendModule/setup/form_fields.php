<?php

use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Form Field Generator
|--------------------------------------------------------------------------
|
| Intelligently generates form field configurations from field definitions.
| Supports all common field types, relationships, and custom validation.
|
| Supported Types:
| - Basic: text, textarea, email, password, tel, url
| - Numeric: number, integer, float, decimal, bigint
| - Date/Time: date, datetime, time, month, year
| - Selection: select, enum, boolean, tinyint
| - File: file, binary
| - Advanced: json, uuid, longtext
| - Relationship: {ModuleName|display_field}
|
*/

if (!function_exists('FormField')) {
    /**
     * Generate form fields configuration
     *
     * @param array $fields Array of [field_name, field_type] pairs
     * @return string JavaScript configuration array
     */
    function FormField($fields)
    {
        $content = "/**\n";
        $content .= " * Form Fields Configuration\n";
        $content .= " *\n";
        $content .= " * Auto-generated form field definitions.\n";
        $content .= " * Each field includes type, validation, and display properties.\n";
        $content .= " */\n\n";
        $content .= "export default [\n";

        foreach ($fields as $fieldName) {
            // Skip fields with braces (relationship fields handled separately)
            if (isset($fieldName[1]) && preg_match('/\{.*\}/', $fieldName[1])) {
                continue;
            }

            $content .= generateFieldConfig($fieldName);
        }

        $content .= "];\n";
        return $content;
    }
}

if (!function_exists('generateFieldConfig')) {
    /**
     * Generate configuration for a single field
     *
     * @param array $fieldName [field_name, field_type]
     * @return string Field configuration object
     */
    function generateFieldConfig($fieldName)
    {
        $name = $fieldName[0];
        $type = $fieldName[1] ?? 'string';
        $label = Str::title(str_replace('_', ' ', $name));

        $config = "\t{\n";
        $config .= "\t\tname: \"$name\",\n";
        $config .= "\t\tlabel: \"Enter $label\",\n";
        $config .= getFieldTypeConfig($type, $label);
        $config .= "\t\tvalue: \"\",\n";
        $config .= "\t\tis_visible: true,\n";
        $config .= "\t\tclass: \"col-md-6\",\n";
        $config .= "\t},\n";

        return $config;
    }
}

if (!function_exists('getFieldTypeConfig')) {
    /**
     * Get field type configuration based on field type
     *
     * @param string $type Field type
     * @param string $label Field label
     * @return string Type-specific configuration
     */
    function getFieldTypeConfig($type, $label)
    {
        $originalType = $type;

        // Normalize type handling
        if (strpos($type, 'string-') === 0) {
            $type = 'string';
        }

        // Handle custom enum types (tinyint-yes.no, boolean-active.inactive, enum-option1.option2)
        if (preg_match('/^(tinyint|boolean|enum)-(.+)$/', $originalType, $matches)) {
            return generateSelectField($matches[2], $label);
        }

        return getBasicFieldTypeConfig($type, $originalType);
    }
}

if (!function_exists('getBasicFieldTypeConfig')) {
    /**
     * Get basic field type configuration
     *
     * @param string $type Normalized field type
     * @param string $originalType Original field type with modifiers
     * @return string Field configuration
     */
    function getBasicFieldTypeConfig($type, $originalType)
    {
        $config = '';

        switch ($type) {
            // Text area types
            case 'longtext':
            case 'text':
                $config .= "\t\ttype: \"textarea\",\n";
                $config .= "\t\trows: 4,\n";
                break;

            // Date and time types
            case 'date':
                $config .= "\t\ttype: \"date\",\n";
                break;

            case 'month':
                $config .= "\t\ttype: \"month\",\n";
                break;

            case 'datetime':
            case 'datetime-local':
            case 'timestamp':
                $config .= "\t\ttype: \"datetime-local\",\n";
                break;

            case 'time':
                $config .= "\t\ttype: \"time\",\n";
                break;

            // Numeric types
            case 'number':
            case 'unsigned_int':
            case 'unsignedInteger':
            case 'int':
            case 'integer':
            case 'intiger':
            case 'bigint':
            case 'biginteger':
                $config .= "\t\ttype: \"number\",\n";
                $config .= "\t\tstep: \"1\",\n";
                break;

            case 'year':
                $config .= "\t\ttype: \"number\",\n";
                $config .= "\t\tmin: \"1900\",\n";
                $config .= "\t\tmax: \"2100\",\n";
                $config .= "\t\tstep: \"1\",\n";
                break;

            case 'float':
            case 'double':
            case 'decimal':
                $config .= "\t\ttype: \"number\",\n";
                $config .= "\t\tstep: \"0.01\",\n";
                break;

            // File types
            case 'binary':
            case 'file':
            case 'stringfile':
                $config .= "\t\ttype: \"file\",\n";
                $config .= "\t\tmultiple: false,\n";
                $config .= "\t\taccept: \"image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document\",\n";
                break;

            // Special types
            case 'uuid':
                $config .= "\t\ttype: \"text\",\n";
                $config .= "\t\treadonly: true,\n";
                $config .= "\t\tplaceholder: \"Auto-generated UUID\",\n";
                break;

            case 'json':
                $config .= "\t\ttype: \"textarea\",\n";
                $config .= "\t\trows: 6,\n";
                $config .= "\t\tplaceholder: \"Enter valid JSON data\",\n";
                break;

            case 'password':
                $config .= "\t\ttype: \"password\",\n";
                $config .= "\t\tautocomplete: \"new-password\",\n";
                break;

            case 'email':
                $config .= "\t\ttype: \"email\",\n";
                $config .= "\t\tplaceholder: \"example@domain.com\",\n";
                break;

            case 'url':
                $config .= "\t\ttype: \"url\",\n";
                $config .= "\t\tplaceholder: \"https://example.com\",\n";
                break;

            case 'tel':
            case 'phone':
                $config .= "\t\ttype: \"tel\",\n";
                $config .= "\t\tplaceholder: \"+1 (555) 000-0000\",\n";
                break;

            // Boolean/Select types
            case 'tinyint':
            case 'boolean':
                // Default boolean with Yes/No options
                $config .= generateSelectField('1.0', '', ['Yes', 'No']);
                break;

            // Default to text input
            case 'string':
            default:
                $config .= "\t\ttype: \"text\",\n";
                break;
        }

        return $config;
    }
}

if (!function_exists('generateSelectField')) {
    /**
     * Generate select field configuration with options
     *
     * @param string $optionsString Dot-separated options (e.g., "active.inactive")
     * @param string $label Field label
     * @param array|null $customLabels Custom labels for options
     * @return string Select field configuration
     */
    function generateSelectField($optionsString, $label, $customLabels = null)
    {
        $options = explode('.', $optionsString);
        $config = "\t\ttype: \"select\",\n";

        if ($label) {
            $config .= "\t\tlabel: \"Select $label\",\n";
        }

        $config .= "\t\tmultiple: false,\n";
        $config .= "\t\tdata_list: [\n";

        foreach ($options as $index => $option) {
            $optionLabel = $customLabels[$index] ?? ucfirst($option);
            $config .= "\t\t\t{\n";
            $config .= "\t\t\t\tlabel: \"$optionLabel\",\n";
            $config .= "\t\t\t\tvalue: \"$option\",\n";
            $config .= "\t\t\t},\n";
        }

        $config .= "\t\t],\n";
        return $config;
    }
}
