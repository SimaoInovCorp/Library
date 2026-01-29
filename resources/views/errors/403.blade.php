<x-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900"></h1>
    </x-slot>
    <style>
        @keyframes agent-move {
            0% { transform: translateX(0); }
            10% { transform: translateX(30px) rotate(-10deg); }
            20% { transform: translateX(60px) rotate(10deg); }
            30% { transform: translateX(90px) rotate(-10deg); }
            40% { transform: translateX(120px) rotate(10deg); }
            50% { transform: translateX(150px) scaleX(-1); }
            60% { transform: translateX(120px) scaleX(-1) rotate(-10deg); }
            70% { transform: translateX(90px) scaleX(-1) rotate(10deg); }
            80% { transform: translateX(60px) scaleX(-1) rotate(-10deg); }
            90% { transform: translateX(30px) scaleX(-1) rotate(10deg); }
            100% { transform: translateX(0) scaleX(-1); }
        }
        .agent-move {
            animation: agent-move 3s infinite linear;
            display: inline-block;
        }
    </style>
    <div class="flex flex-col items-center justify-center py-12">
        <h1 class="text-4xl font-bold text-red-600 mb-4">403 Forbidden</h1>
        <p class="mb-6 text-gray-700">You do not have permission to access this page.</p>
        <div class="text-6xl mb-4"><span class="agent-move">🕵️‍♂️</span></div>
        <p class="text-gray-500">Nice try, secret agent! But this area is off-limits.<br>Return to safety before the security llamas arrive.</p>
    </div>
</x-layout>
