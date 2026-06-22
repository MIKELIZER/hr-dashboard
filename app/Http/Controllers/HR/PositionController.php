<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        return view('hr.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('hr.positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:positions',
            'name' => 'required'
        ]);
        Position::create($request->all());
        return redirect()->route('hr.positions.index')->with('success', 'Position created successfully.');
    }

    public function edit(Position $position)
    {
        return view('hr.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'code' => 'required|unique:positions,code,' . $position->id,
            'name' => 'required'
        ]);
        $position->update($request->all());
        return redirect()->route('hr.positions.index')->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        try {
            $position->delete();
            return redirect()->route('hr.positions.index')->with('success', 'Position deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('hr.positions.index')->with('error', 'Cannot delete position because it is assigned to employees.');
        }
    }
}
