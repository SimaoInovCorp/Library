<x-layout>
    <x-slot name="header">
        Contact
    </x-slot>

    <div class="prose max-w-none">
        <h1 class="text-2xl font-bold mb-4">Contact Information</h1>
        <p class="mb-4">For inquiries, feedback, or support regarding the Biblioteca project, please use the contact details or form below.</p>



        <h2 class="text-xl font-semibold mt-6 mb-2">Contact Form</h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside text-sm text-red-800">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4 max-w-lg">
            @csrf
            <div>
                <label for="name" class="block font-semibold mb-1">Your Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input w-full rounded border-gray-300" required>
            </div>
            <div>
                <label for="email" class="block font-semibold mb-1">Your Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input w-full rounded border-gray-300" required>
            </div>
            <div>
                <label for="message" class="block font-semibold mb-1">Message</label>
                <textarea id="message" name="message" rows="4" class="form-textarea w-full rounded border-gray-300" required>{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded bg-blue-600 text-white font-bold hover:bg-blue-700 transition">Send Message</button>
        </form>

        <p class="mt-8 text-sm text-gray-500">We aim to respond to all inquiries within 2 business days.</p>
    </div>
</x-layout>