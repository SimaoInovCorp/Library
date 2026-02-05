<x-dropdown align="right" width="48">
	<x-slot name="trigger">
		<button type="button" class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
			<span class="absolute -inset-1.5"></span>
			<span class="sr-only">Open user menu</span>
			<img class="h-8 w-8 rounded-full" src="https://www.svgrepo.com/show/164239/bookshelf.svg" alt="">
		</button>
	</x-slot>

	<x-slot name="content">
		<div class="py-1">
			<x-dropdown-link href="{{ route('profile.show') }}">Profile</x-dropdown-link>
			<x-dropdown-link href="#">Settings</x-dropdown-link>
			<form method="POST" action="{{ route('logout') }}" x-data>
				@csrf
				<x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
					Logout
				</x-dropdown-link>
			</form>
		</div>
	</x-slot>
</x-dropdown>
