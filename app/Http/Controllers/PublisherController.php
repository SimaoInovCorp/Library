<?php

namespace App\Http\Controllers;


use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Display a listing of the publishers.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc');
        $validSorts = ['name'];
        $validDirections = ['asc', 'desc'];
        $sort = in_array($sort, $validSorts) ? $sort : 'name';
        $direction = in_array($direction, $validDirections) ? $direction : 'asc';

        $publishers = Publisher::orderBy($sort, $direction)
            ->paginate(10)
            ->appends(['sort' => $sort, 'direction' => $direction]);
        return view('publishers.index', compact('publishers', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new publisher.
     */
    public function create()
    {
        return view('publishers.create');
    }

    /**
     * Store a newly created publisher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name',
            'logo' => 'nullable|image|max:2048',
        ]);

        $publisher = new Publisher();
        $publisher->name = $validated['name'];
        if ($request->hasFile('logo')) {
            $publisher->logo = $request->file('logo')->store('publishers', 'public');
        }
        $publisher->save();

        return redirect()->route('publishers.index')->with('success', 'Publisher created successfully.');
    }

    /**
     * Display the specified publisher.
     */
    public function show(Publisher $publisher)
    {
        return view('publishers.show', compact('publisher'));
    }

    /**
     * Show the form for editing the specified publisher.
     */
    public function edit(Publisher $publisher)
    {
        return view('publishers.edit', compact('publisher'));
    }

    /**
     * Update the specified publisher in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name,' . $publisher->id,
            'logo' => 'nullable|image|max:2048',
        ]);

        $publisher->name = $validated['name'];
        if ($request->hasFile('logo')) {
            $publisher->logo = $request->file('logo')->store('publishers', 'public');
        }
        $publisher->save();

        return redirect()->route('publishers.index')->with('success', 'Publisher updated successfully.');
    }

    /**
     * Remove the specified publisher from storage.
     */
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        return redirect()->route('publishers.index')->with('success', 'Publisher deleted successfully.');
    }
}
