<?php

namespace App;

use Illuminate\Http\Request;
use Inertia\Inertia;

trait CrudTrait
{
    public function index()
    {
        if (!auth()->user()->can('add ' . $this->table_name)) {
            abort(403, 'Unauthorized');
        }
        return Inertia::render($this->index_path);
    }


    public function create()
    {
        return Inertia::render($this->edit_path);
    }


    public function store(Request $request)
    {
        dd($request->all());
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        if (!auth()->user()->can('edit ' . $this->table_name)) {
            abort(403, 'Unauthorized');
        }
        $data = $this->model->findOrFail($id);
        return Inertia::render($this->edit_path, ['data'=> $data]);
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}