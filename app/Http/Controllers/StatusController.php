<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{
    public function index()
    {
        // 'withCount('asets')' akan otomatis menghitung jumlah aset per status
        $statuses = Status::withCount('asets')->latest()->get(); 
        return view('status.index', compact('statuses'));
    }

    public function create()
    {
        return view('status.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:statuses,name']);
        Status::create($request->all());

        // Jika ada permintaan redirect, kembali ke halaman sebelumnya. Jika tidak, ke halaman index.
        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Status baru berhasil ditambahkan.');
        }
        return redirect()->route('status.index')->with('success', 'Status baru berhasil ditambahkan.');
    }

    public function edit(Status $status)
    {
        return view('status.edit', compact('status'));
    }

    public function update(Request $request, Status $status)
    {
        $request->validate(['name' => ['required', 'string', 'max:255', Rule::unique('statuses')->ignore($status->id)]]);
        $status->update(['name' => $request->name]);
        return redirect()->route('status.index')->with('success', 'Status berhasil diperbarui.');
    }

    public function destroy(Status $status)
    {
        $status->delete();
        return redirect()->route('status.index')->with('success', 'Status berhasil dihapus.');
    }
}