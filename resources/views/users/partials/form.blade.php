<div class="mb-4">
    <label for="name" class="block text-gray-700 font-medium mb-2">Name <span class="text-red-500">*</span></label>
    <input type="text" name="name" id="name" class="form-input w-full border border-gray-300 rounded px-4 py-2" value="{{ old('name', $user->name ?? '') }}" required>
    @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label for="email" class="block text-gray-700 font-medium mb-2">Email <span class="text-red-500">*</span></label>
    <input type="email" name="email" id="email" class="form-input w-full border border-gray-300 rounded px-4 py-2" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label for="password" class="block text-gray-700 font-medium mb-2">
        Password
        @if(isset($user))
            <span class="text-gray-500 text-sm">(leave blank to keep current password)</span>
        @else
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input type="password" name="password" id="password" class="form-input w-full border border-gray-300 rounded px-4 py-2" {{ !isset($user) ? 'required' : '' }}>
    @error('password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">
        Confirm Password
        @if(isset($user))
            <span class="text-gray-500 text-sm">(required only if changing password)</span>
        @else
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input w-full border border-gray-300 rounded px-4 py-2" {{ !isset($user) ? 'required' : '' }}>
</div>

<div class="mb-4">
    <label for="is_admin" class="block text-gray-700 font-medium mb-2">Role <span class="text-red-500">*</span></label>
    <select name="is_admin" id="is_admin" class="form-input w-full border border-gray-300 rounded px-4 py-2" required>
        <option value="0" {{ old('is_admin', $user->is_admin ?? 0) == 0 ? 'selected' : '' }}>User</option>
        <option value="1" {{ old('is_admin', $user->is_admin ?? 0) == 1 ? 'selected' : '' }}>Admin</option>
    </select>
    @error('is_admin')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
</div>

<div class="flex gap-2">
    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">{{ $submitLabel }}</button>
    <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
</div>
