<?php
foreach (['string-150', 'text', 'decimal', 'boolean-active.inactive'] as $fieldType) {
    echo "Type: $fieldType -> ";
    switch (true) {
        case strpos($fieldType, 'string') === 0:
            echo "string\n"; break;
        case in_array($fieldType, ['text', 'longtext']):
            echo "paragraph\n"; break;
        case in_array($fieldType, ['float', 'decimal', 'double']):
            echo "randomFloat\n"; break;
        case strpos($fieldType, 'boolean-') === 0:
            echo "boolOptions\n"; break;
        default:
            echo "word\n"; break;
    }
}
