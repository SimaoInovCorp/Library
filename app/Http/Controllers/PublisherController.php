<?php

namespace App\Http\Controllers;


use App\Models\Publisher;
use App\Services\Publishers\PublisherService;
use App\Services\Publishers\PublisherQueryService;
use App\Http\Requests\Publishers\StorePublisherRequest;
use App\Http\Requests\Publishers\UpdatePublisherRequest;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['show']);
        $this->middleware(['auth', 'admin'])->except(['show']);
    }

    /**
     * Display a listing of the publishers.
     */
    public function index(Request $request, PublisherQueryService $publisherQueryService)
    {
        $params = [
            'sort' => $request->query('sort', 'name'),
            'direction' => $request->query('direction', 'asc'),
            'search' => $request->query('search'),
        ];
        $publishers = $publisherQueryService->getFilteredPublishers($params)
            ->paginate(10)
            ->appends($request->except('page'));
        $sort = $params['sort'];
        $direction = $params['direction'];
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
    public function store(StorePublisherRequest $request, PublisherService $publisherService)
    {
        $publisherService->create($request->validated());
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
    public function update(UpdatePublisherRequest $request, Publisher $publisher, PublisherService $publisherService)
    {
        $publisherService->update($publisher, $request->validated());
        return redirect()->route('publishers.index')->with('success', 'Publisher updated successfully.');
    }

    /**
     * Remove the specified publisher from storage.
     */
    public function destroy(Publisher $publisher, PublisherService $publisherService)
    {
        $publisherService->delete($publisher);
        return redirect()->route('publishers.index')->with('success', 'Publisher deleted successfully.');
    }
}
