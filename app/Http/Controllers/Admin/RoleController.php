<?php

namespace App\Http\Controllers\Admin;

use App\CrudTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use CrudTrait;

    protected $index_path, $store_rules, $route, $model, $edit_path, $table_name, $permissions;

    public function __construct(Role $model, Permission $permissions)
    {
        $this->model = $model;
        $this->route = 'roles.index';
        $this->index_path = 'admin/roles/List';
        $this->edit_path = 'admin/roles/EditAdd';
        $this->table_name = 'roles';
        $this->permissions = $permissions;
    }

    public function paginate(Request $request)
    {
        $query = $this->model->query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->paginate(10);

        return response()->json($data);
    }
    public function create()
    {
        if (!auth()->user()->can('add ' . $this->table_name)) {
            abort(403, 'Unauthorized');
        }
        $permissions = $this->permissions->select('id', 'name', 'group')->get()->groupBy('group')->map(function ($group) {
            return $group->map(function ($permission) {
                return $permission->only(['id', 'name', 'group']);
            })->values();
        });
        return Inertia::render($this->edit_path, [
            'permissions' => $permissions
        ]);
    }

    public function edit(string $id)
    {
        if (!auth()->user()->can('edit ' . $this->table_name)) {
            abort(403, 'Unauthorized');
        }

        $data = $this->model->with('permissions')->findOrFail($id);
        $permissions = $this->permissions->select('id', 'name', 'group')->get()->groupBy('group')->map(function ($group) {
            return $group->map(function ($permission) {
                return $permission->only(['id', 'name', 'group']);
            })->values();
        });
        return Inertia::render($this->edit_path, ['role' => $data, 'permissions' => $permissions]);
    }
    public function store(StoreRoleRequest $request)
    {
        try {
            if (!auth()->user()->can('add ' . $this->table_name)) {
                abort(403, 'Unauthorized');
            }

            $req_title = $request->input("role");
            $req_permissions = $request->input('permissions', []);

            $role = $this->model->create([
                'name' => $req_title,
                'guard_name' => 'web',
            ]);

            if (!empty($req_permissions)) {
                $permissions = Permission::whereIn('id', $req_permissions)->get();
                $role->syncPermissions($permissions);
            }

            return redirect()->route('roles.index')->with([
                'success' => true,
                'message' => 'Role created successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function update(StoreRoleRequest $request)
    {
        if (!auth()->user()->can('edit ' . $this->table_name)) {
            abort(403, 'Unauthorized');
        }
        try {
            $req_permissions = $request->input('permissions') ?? "";
            $req_title = $request->input("role") ?? "";
            $role = $this->model->findOrFail($request->id);
            if (!empty($req_permissions)) {
                $permissions = $this->permissions->whereIn('id', $req_permissions)->get();
                $role->syncPermissions($permissions);
            }
            $role->update([
                'name' => $req_title
            ]);
            $role->save();
            return redirect()->route('roles.index')->with(['success' => true, 'message' => 'Role updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


}
