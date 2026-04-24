<?php

use Illuminate\Support\Str;

if (!function_exists('Model')) {
    function Model($moduleName, $module_Name, $jsonFields = [], $hasJsonUploads = false, $fieldsWithBraces = [])
    {
        $targetClass = null;
        $formated_module = explode('/', $moduleName);

        if (count($formated_module) > 1) {
            $moduleName = implode('\\', $formated_module);
            $targetClass = $moduleName;
            $moduleName = Str::replace("/", "\\", $moduleName);
        } else {
            $moduleName = Str::replace("/", "\\", $moduleName);
            $targetClass = $moduleName;
        }

        // Determine table name
        $table_name = Str::plural(Str::snake($module_Name));

        // Build model relationships
        $modelRelations = '';
        if (count($fieldsWithBraces)) {
            foreach ($fieldsWithBraces as $field) {
                $brace_content = $field['brace_content'];
                $methodName = Str::camel(Str::replace('_', ' ', $field['field']));
                $modelRelations .= "\n    public function {$methodName}()\n    {\n        return \$this->belongsTo(\\Modules\\Management\\{$brace_content}\\Database\\Models\\Model::class, '{$field['field']}');\n    }";
            }
        }

        $content = <<<"EOD"
<?php

namespace Modules\\Management\\{$moduleName}\\Database\\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends EloquentModel
{
    use HasFactory, SoftDeletes;

    protected \$table = "$table_name";
    protected \$guarded = [];

    protected static function booted()
    {
        static::created(function (\$data) {
            \$random_no = random_int(100, 999) . \$data->id . random_int(100, 999);
            \$slug = \$data->title ?? \$data->name ?? 'item';
            \$slug = \$slug . " " . \$random_no;
            \$data->slug = Str::slug(\$slug);
            if (strlen(\$data->slug) > 50) {
                \$data->slug = substr(\$data->slug, strlen(\$data->slug) - 50, strlen(\$data->slug));
            }
            if (auth()->check()) {
                \$data->creator = auth()->user()->id ?? null;
            }
            \$data->save();
        });
    }

    public function scopeActive(\$q)
    {
        return \$q->where('status', 'active');
    }

    public function scopeInactive(\$q)
    {
        return \$q->where('status', 'inactive');
    }
{$modelRelations}
}
EOD;

        return $content;
    }
}
if (!function_exists('TableModel')) {
    function TableModel($modulePath, $finalModule)
    {
        $namespace = str_replace('/', '\\', $modulePath);
        $parts = explode('/', $modulePath);
        $moduleDirectory = implode('\\', array_slice($parts, 0, -1)); // e.g., BlogManagement\BlogCategory

        $tableName = Str::plural(Str::snake($finalModule));

        $content = <<<"EOD"
<?php

namespace Modules\Management\\{$moduleDirectory}\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class {$finalModule}Model extends EloquentModel
{
    protected \$table = "$tableName";
    protected \$guarded = [];
}
EOD;

        return $content;
    }
}