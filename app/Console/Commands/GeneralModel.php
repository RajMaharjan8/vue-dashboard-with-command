<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Str;

class GeneralModel extends Command
{

    protected $signature = 'aku:model';
    protected $description = 'Custom Model';


    public function handle()
    {
        $model_name = $this->ask("What is the name of the Model?");
        if (isset($model_name)) {
            $model_title = Str::plural($model_name);
            $model_subtitle = Str::plural(strtolower($model_name));
            //Creating Model
            $model_template = file_get_contents(base_path() . '/app/Stubs/Model.stub');
            $model_variables = [
                "MODEL" => $model_name,
            ];
            foreach ($model_variables as $key => $value) {
                $model_template = str_replace("{{{$key}}}", $value, $model_template);
            }
            $model_path = base_path() . '/app/Models';
            file_put_contents($model_path . "/$model_name.php", $model_template);
            $this->info('Model Created Successfully!');

            //Creating Migration
            $this->createMigration($model_name);

            //Creating Request
            $request_name = "Store" . $model_name . "Request";
            $this->call("make:request", [
                "name" => $model_name."/".$request_name,
            ]);
            $this->info("Request Created Successfully!");

            //Creating Controller 
            $controller_name = $model_name . "Controller";
            $which_controller = $this->ask("Type 'admin' for creating the controller inside Admin!");
            if ($which_controller == "admin") {
                $controller_template = file_get_contents(base_path() . "/app/Stubs/Controller.stub");
                $controller_variables = [
                    "CONTROLLER" => $controller_name,
                    "MODEL" => $model_name,
                    "REQUEST" => $request_name,
                    "TABLE_NAME" => $model_subtitle
                ];
                foreach ($controller_variables as $key => &$value) {
                    $controller_template = str_replace("{{{$key}}}", $value, $controller_template);
                }
                $controller_path = base_path() . '/app/Http/Controllers/Admin';
                file_put_contents($controller_path . "/$controller_name.php", $controller_template);
                $this->createRoute($model_subtitle, $controller_name, $is_admin = true);
            } else {
                $this->call("make:controller", [
                    "name" => "Front/$controller_name",
                    "-r" => true,
                ]);
                $this->createRoute($model_subtitle, $controller_name, $is_admin = false);
            }
            $this->info('Controller Created Successfully!');

            $make_permissions = $this->ask('Do you want to make permission (EDIT,ADD,UPDATE,DELETE)? ("yes" or "no")');
            if ($make_permissions == "y" || $make_permissions == "yes" || $make_permissions == "Yes" || $make_permissions == "YES") {
                $this->createPermissions($model_subtitle, $model_name);
            }
        }
    }

    public function createMigration($model_name)
    {
        $migration_title = 'create_' . Str::plural(strtolower($model_name)) . '_table';
        $this->call('make:migration', [
            'name' => $migration_title,
            '--path' => 'database/migrations',
        ]);
        $this->info('Migration Created Successfully!');
    }
    public function createRoute($model_subtitle, $controller_name, $is_admin)
    {
        $route_path = base_path() . '/routes/web.php';
        $new_Route_content = <<<ROUTE
    Route::get('$model_subtitle/paginate',[$controller_name::class, 'paginate'])->name('$model_subtitle.paginate');
    Route::resource('$model_subtitle', {$controller_name}::class);

    ROUTE;

        $route_content = file_get_contents($route_path);

        if ($is_admin) {
            $import_statement = "use App\\Http\\Controllers\\Admin\\{$controller_name};";
        } else {
            $import_statement = "use App\\Http\\Controllers\\Front\\{$controller_name};";
        }

        if (strpos($route_content, $import_statement) === false) {
            $import_position = strpos($route_content, "<?php") + 5;
            $route_content = substr_replace($route_content, "\n$import_statement", $import_position, 0);
        }

        if ($is_admin) {
            $admin_group_start = strpos($route_content, "Route::middleware(['auth'])->prefix('admin')->group(function () {");
            if ($admin_group_start !== false) {
                $admin_group_end = strpos($route_content, "});", $admin_group_start);
                if ($admin_group_end !== false) {
                    $insert_position = $admin_group_end;
                    $route_content = substr_replace($route_content, "\n$new_Route_content", $insert_position, 0);
                }
            }
        } else {
            $route_content .= "\n$new_Route_content";
        }

        file_put_contents($route_path, $route_content);

        $this->info("Routes for $model_subtitle added to routes/web.php");
    }

    public function createPermissions($permission_model, $model_name)
    {
        // $permission_model = ucwords($permission_model);
        $permissions = [
            'edit ' . $permission_model,
            'add ' . $permission_model,
            'view ' . $permission_model,
            'delete ' . $permission_model
        ];

        foreach ($permissions as $permission) {
            $existingPermission = Permission::where('name', $permission)->first();

            if (!$existingPermission) {
                $newPermission = Permission::create([
                    "name" => $permission,
                    'guard_name' => 'web',
                    'group_name' => $model_name
                ]);

            }
        }

        $this->info('Permissions Created Successfully!');
    }
}
