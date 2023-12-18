<?php

namespace App\Http\Controllers;

use App\Models\Crud;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Storage;

class CrudController extends Controller
{    
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {

        $Cruds = Crud::latest()->get();

        return view('Crud.index', compact('Cruds'));
    }

    /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('Crud.create');
    }
 
    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,jpg,png',
            'title'     => 'min:1',
            'content'   => 'min:2'
        ]);

        $image = $request->file('image');
        $image->storeAs('public/Crud', $image->hashName());

        Crud::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        //redirect to index
        return redirect()->route('crud.index')->with(['success' => 'Berhasil Disimpan!']);
    }
    
    /**
     * show
     *
     * @param  mixed $id
     * @return View
     */
    public function show(string $id): View
    {
        $crud = Crud::findOrFail($id);

        return view('Crud.show', compact('crud'));
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit(string $id): View
    {
        $crud = Crud::findOrFail($id);

        return view('Crud.edit', compact('crud'));
    }
        
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,jpg,png',
            'title'     => 'min:1',
            'content'   => 'min:2'
        ]);

        $crud = Crud::findOrFail($id);

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $image->storeAs('public/Crud', $image->hashName());

            Storage::delete('public/Crud/'.$crud->image);

            $crud->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);

        } else {

            $crud->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        return redirect()->route('crud.index')->with(['success' => 'Berhasil Diubah!']);
    }

    /**
     * destroy
     *
     * @param  mixed $crud
     * @return void
     */
    public function destroy($id): RedirectResponse
    {
        $crud = Crud::findOrFail($id);

        Storage::delete('public/Crud/'. $crud->image);

        $crud->delete();

        return redirect()->route('crud.index')->with(['success' => 'Berhasil Dihapus!']);
    }
}