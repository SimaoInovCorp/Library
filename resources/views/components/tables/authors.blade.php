@props(['authors', 'sort' => null, 'direction' => null])

<table class="table-auto w-full">
    <thead>
        <tr>
            <th class="px-4 py-2 text-left">
                @php
                    $isSorted = (isset($sort) && $sort === 'name');
                    $nextDirection = ($isSorted && isset($direction) && $direction === 'asc') ? 'desc' : 'asc';
                @endphp
                <a href="{{ route('authors.index', ['sort' => 'name', 'direction' => $nextDirection]) }}" class="hover:underline flex items-center gap-1">
                    Name
                    @if($isSorted)
                        @if(isset($direction) && $direction === 'asc')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        @endif
                    @endif
                </a>
            </th>
            <th class="px-4 py-2 text-left">Picture</th>
            <th class="px-4 py-2 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($authors as $author)
            <tr>
                <td class="px-4 py-2 align-middle">{{ $author->name }}</td>
                <td class="px-4 py-2 align-middle">
                    <x-image-display :src="$author->picture" alt="Picture" />
                </td>
                <td class="px-4 py-2 align-middle">
                    <div class="flex flex-row gap-2">
                        <form method="GET" action="{{ route('authors.show', $author) }}" style="display:inline;">
                            <x-buttons.details type="submit">Details</x-buttons.details>
                        </form>
                        <form method="GET" action="{{ route('authors.edit', $author) }}" style="display:inline;">
                            <x-buttons.edit type="submit">Edit</x-buttons.edit>
                        </form>
                        <div x-data="{ showModal: false }">
                            <x-buttons.danger type="button" @click="showModal = true">Delete</x-buttons.danger>
                            <form x-ref="deleteForm" action="{{ route('authors.destroy', $author) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <x-modal id="delete-author-modal" show="showModal" maxWidth="md">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Delete Author</h2>
                                    <p class="mb-4 text-gray-700">Are you sure you want to delete the author <span class="font-bold">{{ $author->name }}</span>? This action cannot be undone.</p>
                                    <div class="flex justify-end gap-2">
                                        <x-buttons.secondary type="button" @click="showModal = false">Cancel</x-buttons.secondary>
                                        <x-buttons.danger type="button" @click="$refs.deleteForm.submit(); showModal = false;">Delete</x-buttons.danger>
                                    </div>
                                </div>
                            </x-modal>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
