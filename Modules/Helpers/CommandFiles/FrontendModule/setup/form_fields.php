<?php

use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Form Field Generator
|--------------------------------------------------------------------------
|
| Auto-generates frontend form field configs from command field definitions.
|
| ── EXPLICIT TYPES ────────────────────────────────────────────────────────
|  Text       : string, string-N, text (textarea), longtext (textarea), email,
|               url, tel/phone, password, uuid (readonly)
|  Numeric    : integer/int, bigint, float, double, decimal, year, number
|  Date/Time  : date, datetime/timestamp, time, month
|  File       : image / image-N  (single image),  images (multi-image)
|               file / stringfile / binary        (generic doc/file)
|  Select     : boolean/tinyint              → Yes/No select
|               boolean-opt1.opt2            → custom options
|               tinyint-opt1.opt2            → custom options
|               enum-opt1.opt2.opt3          → enum select
|               select                       → empty select (fill data_list manually)
|               multiselect                  → empty multi-select
|  Relation   : relation                     → empty relation select
|  Rich text  : editor / richtext / wysiwyg  → rich-text editor
|  Color      : color                        → color picker
|  Range      : range / range-0.100.1        → range slider (min.max.step)
|  JSON       : json                         → textarea (6 rows, JSON hint)
|  UUID       : uuid                         → readonly text
|
| ── NAME-BASED AUTO-DETECTION (no type change needed) ─────────────────────
|  *_id           → relation select  (e.g. blog_category_id)
|  *_image / thumbnail / avatar / banner / icon / cover / logo / photo
|                 → single image file input
|  *_images / gallery / photos / pictures
|                 → multiple image file input
|  is_* / has_*  → boolean Yes/No select   (e.g. is_featured, has_discount)
|  email / *_email          → email input
|  phone/mobile/*_phone     → tel input
|  url/link/website/*_url   → url input
|  color/colour/*_color     → color picker
|  password/*_password      → password input
|  price/amount/cost/*_price/*_amount → decimal number
|
| ── RANGE SYNTAX ──────────────────────────────────────────────────────────
|  range           → min:0  max:100  step:1
|  range-0.200.5   → min:0  max:200  step:5
|
*/

if (!function_exists('FormField')) {
    function FormField($fields)
    {
        $content  = "/**\n";
        $content .= " * Form Fields Configuration\n";
        $content .= " * Auto-generated — edit data_list / class / is_visible as needed.\n";
        $content .= " */\n\n";
        $content .= "export default [\n";

        foreach ($fields as $fieldName) {
            if (isset($fieldName[1]) && preg_match('/\{.*\}/', $fieldName[1])) {
                continue; // relationship fields handled separately
            }
            $content .= generateFieldConfig($fieldName);
        }

        $content .= "];\n";
        return $content;
    }
}

if (!function_exists('generateFieldConfig')) {
    function generateFieldConfig($fieldName)
    {
        $name     = $fieldName[0];
        $type     = $fieldName[1] ?? 'string';
        $label    = Str::title(str_replace('_', ' ', $name));
        $baseType = strtolower(explode('-', $type)[0]);

        // ── Name-based auto-detection ────────────────────────────────────
        // Only applies when the explicit type gives no intent (string / text /
        // integer / bigint — types that don't carry semantic meaning by themselves).
        $semanticTypes = [
            'image','images','file','binary','email','url','tel','phone',
            'color','colour','password','editor','richtext','wysiwyg',
            'range','select','multiselect','relation','boolean','tinyint',
            'enum','json','uuid','date','datetime','time','month','year',
            'float','double','decimal',
        ];

        if (!in_array($baseType, $semanticTypes)) {
            $n = strtolower($name);

            // Relation: *_id  (skip bare 'id')
            if ($n !== 'id' && str_ends_with($n, '_id')) {
                $type = 'relation';
            }
            // Multiple images
            elseif (
                str_ends_with($n, '_images') || str_ends_with($n, '_photos') ||
                str_ends_with($n, '_pictures') || str_ends_with($n, '_gallery') ||
                $n === 'images' || $n === 'gallery' || $n === 'photos'
            ) {
                $type = 'images';
            }
            // Single image
            elseif (
                str_ends_with($n, '_image') || str_ends_with($n, '_thumbnail') ||
                str_ends_with($n, '_banner') || str_ends_with($n, '_avatar') ||
                str_ends_with($n, '_icon') || str_ends_with($n, '_cover') ||
                str_ends_with($n, '_photo') || str_ends_with($n, '_picture') ||
                str_ends_with($n, '_logo') || $n === 'image' || $n === 'thumbnail' ||
                $n === 'avatar' || $n === 'banner' || $n === 'logo'
            ) {
                $type = 'image';
            }
            // Boolean flags
            elseif (str_starts_with($n, 'is_') || str_starts_with($n, 'has_')) {
                $type = 'boolean';
            }
            // Email
            elseif ($n === 'email' || str_ends_with($n, '_email')) {
                $type = 'email';
            }
            // Phone
            elseif (
                in_array($n, ['phone', 'mobile', 'telephone', 'tel']) ||
                str_ends_with($n, '_phone') || str_ends_with($n, '_mobile')
            ) {
                $type = 'tel';
            }
            // URL / link
            elseif (
                in_array($n, ['url', 'link', 'website', 'webpage']) ||
                str_ends_with($n, '_url') || str_ends_with($n, '_link') ||
                str_ends_with($n, '_website')
            ) {
                $type = 'url';
            }
            // Color
            elseif (
                in_array($n, ['color', 'colour', 'bg_color', 'text_color']) ||
                str_ends_with($n, '_color') || str_ends_with($n, '_colour')
            ) {
                $type = 'color';
            }
            // Password
            elseif ($n === 'password' || str_ends_with($n, '_password')) {
                $type = 'password';
            }
            // Decimal / money
            elseif (
                in_array($n, ['price', 'amount', 'cost', 'fee', 'salary', 'budget', 'total', 'subtotal', 'discount', 'tax']) ||
                str_ends_with($n, '_price') || str_ends_with($n, '_amount') ||
                str_ends_with($n, '_cost') || str_ends_with($n, '_fee')
            ) {
                $type = 'decimal';
            }
        }

        // ── Smart label prefix ───────────────────────────────────────────
        $resolvedBase = strtolower(explode('-', $type)[0]);
        $selectTypes  = ['select', 'multiselect', 'relation', 'boolean', 'tinyint', 'enum'];
        $fileTypes    = ['image', 'images', 'file', 'binary', 'stringfile'];

        if (in_array($resolvedBase, $selectTypes)) {
            // For relation IDs strip "_id" suffix from the label
            $displayLabel = str_ends_with(strtolower($name), '_id')
                ? Str::title(str_replace('_', ' ', substr($name, 0, -3)))
                : $label;
            $prefix = 'Select';
        } elseif (in_array($resolvedBase, $fileTypes)) {
            $displayLabel = $label;
            $prefix = 'Upload';
        } else {
            $displayLabel = $label;
            $prefix = 'Enter';
        }

        $config  = "\t{\n";
        $config .= "\t\tname: \"$name\",\n";
        $config .= "\t\tlabel: \"$prefix $displayLabel\",\n";
        $config .= getFieldTypeConfig($type, $displayLabel);
        $config .= "\t\tvalue: \"\",\n";
        $config .= "\t\tis_visible: true,\n";
        $config .= "\t\tclass: \"col-md-6\",\n";
        $config .= "\t},\n";

        return $config;
    }
}

if (!function_exists('getFieldTypeConfig')) {
    function getFieldTypeConfig($type, $label)
    {
        $originalType = $type;

        // Normalize size-suffixed variants
        if (strpos($type, 'string-') === 0) { $type = 'string'; }
        if (strpos($type, 'image-')  === 0) { $type = 'image'; }

        // Custom options on boolean/tinyint/enum  →  enum-opt1.opt2
        if (preg_match('/^(tinyint|boolean|enum|select|multiselect)-(.+)$/', $originalType, $m)) {
            $multiple = $m[1] === 'multiselect';
            return generateSelectField($m[2], $label, null, $multiple);
        }

        // range-min.max.step
        if (strpos($originalType, 'range-') === 0) {
            $parts = explode('.', substr($originalType, 6));
            return generateRangeField(
                $parts[0] ?? '0',
                $parts[1] ?? '100',
                $parts[2] ?? '1'
            );
        }

        return getBasicFieldTypeConfig($type, $originalType);
    }
}

if (!function_exists('getBasicFieldTypeConfig')) {
    function getBasicFieldTypeConfig($type, $originalType)
    {
        $config = '';

        switch ($type) {

            // ── Textarea ─────────────────────────────────────────────────
            case 'longtext':
            case 'text':
                $config .= "\t\ttype: \"textarea\",\n";
                $config .= "\t\trows: 4,\n";
                break;

            case 'json':
                $config .= "\t\ttype: \"textarea\",\n";
                $config .= "\t\trows: 6,\n";
                $config .= "\t\tplaceholder: \"Enter valid JSON\",\n";
                break;

            // ── Rich text editor ─────────────────────────────────────────
            case 'editor':
            case 'richtext':
            case 'wysiwyg':
                $config .= "\t\ttype: \"editor\",\n";
                $config .= "\t\trows: 10,\n";
                break;

            // ── Date / Time ──────────────────────────────────────────────
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

            // ── Numeric ──────────────────────────────────────────────────
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

            // ── Range slider ─────────────────────────────────────────────
            case 'range':
                $config .= generateRangeField('0', '100', '1');
                break;

            // ── Image uploads ─────────────────────────────────────────────
            case 'image':
                $config .= "\t\ttype: \"file\",\n";
                $config .= "\t\tmultiple: false,\n";
                $config .= "\t\taccept: \"image/*\",\n";
                break;

            case 'images':
                $config .= "\t\ttype: \"file\",\n";
                $config .= "\t\tmultiple: true,\n";
                $config .= "\t\taccept: \"image/*\",\n";
                break;

            // ── Generic file upload ───────────────────────────────────────
            case 'binary':
            case 'file':
            case 'stringfile':
                $config .= "\t\ttype: \"file\",\n";
                $config .= "\t\tmultiple: false,\n";
                $config .= "\t\taccept: \"image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document\",\n";
                break;

            // ── Relation / Select ─────────────────────────────────────────
            case 'relation':
                $config .= "\t\ttype: \"select\",\n";
                $config .= "\t\tmultiple: false,\n";
                $config .= "\t\tdata_list: [],\n";
                break;

            case 'select':
                $config .= "\t\ttype: \"select\",\n";
                $config .= "\t\tmultiple: false,\n";
                $config .= "\t\tdata_list: [],\n";
                break;

            case 'multiselect':
                $config .= "\t\ttype: \"select\",\n";
                $config .= "\t\tmultiple: true,\n";
                $config .= "\t\tdata_list: [],\n";
                break;

            case 'tinyint':
            case 'boolean':
                $config .= generateSelectField('1.0', '', ['Yes', 'No']);
                break;

            // ── Specialised text ──────────────────────────────────────────
            case 'uuid':
                $config .= "\t\ttype: \"text\",\n";
                $config .= "\t\treadonly: true,\n";
                $config .= "\t\tplaceholder: \"Auto-generated UUID\",\n";
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

            case 'color':
            case 'colour':
                $config .= "\t\ttype: \"color\",\n";
                break;

            // ── Default text ──────────────────────────────────────────────
            case 'string':
            default:
                $config .= "\t\ttype: \"text\",\n";
                break;
        }

        return $config;
    }
}

if (!function_exists('generateSelectField')) {
    function generateSelectField($optionsString, $label, $customLabels = null, $multiple = false)
    {
        $options  = explode('.', $optionsString);
        $config   = "\t\ttype: \"select\",\n";

        if ($label) {
            $config .= "\t\tlabel: \"Select $label\",\n";
        }

        $config .= "\t\tmultiple: " . ($multiple ? 'true' : 'false') . ",\n";
        $config .= "\t\tdata_list: [\n";

        foreach ($options as $i => $option) {
            $optLabel = $customLabels[$i] ?? ucfirst($option);
            $config .= "\t\t\t{ label: \"$optLabel\", value: \"$option\" },\n";
        }

        $config .= "\t\t],\n";
        return $config;
    }
}

if (!function_exists('generateRangeField')) {
    function generateRangeField($min, $max, $step)
    {
        $config  = "\t\ttype: \"range\",\n";
        $config .= "\t\tmin: \"$min\",\n";
        $config .= "\t\tmax: \"$max\",\n";
        $config .= "\t\tstep: \"$step\",\n";
        return $config;
    }
}
