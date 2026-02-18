<x-layout>
    <x-slot name="header">
        About Biblioteca
    </x-slot>

    <div class="prose max-w-none">
        <h1 class="text-3xl font-bold mb-2">Biblioteca - Library Management System</h1>
        <p class="text-lg mb-4">A modern, efficient, and user-friendly platform for managing books, authors, publishers, and library operations.</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Mission</h2>
        <p>To empower libraries and organizations with a robust, intuitive, and scalable system for cataloging, tracking, and managing their collections and users.</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Key Features</h2>
        <ul class="list-disc ml-6">
            <li>Comprehensive book, author, and publisher management</li>
            <li>Advanced search and filtering capabilities</li>
            <li>Role-based user access (Admin/User)</li>
            <li>Book requisition and waitlist management</li>
            <li>Automated notifications and reminders</li>
            <li>Audit logging of key actions (book requests, approvals, returns, etc.)</li>
            <li>Admin log viewer with filtering and CSV export</li>
            <li>Modern UI with Tailwind CSS, daisyUI, and responsive design</li>
            <li>Reusable, DRY Blade components for tables and UI elements</li>
            <li>Data import/export (CSV for books and logs, Google Books integration)</li>
            <li>Automated feature and scenario testing with Pest</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">Roadmap</h2>
        <ul class="list-disc ml-6">
            <li>Integration with additional external book APIs</li>
            <li>Analytics and reporting dashboard</li>
            <li>Multi-language support</li>
            <li>Mobile-friendly enhancements</li>
            <li>More granular user permissions</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">Technologies Used</h2>
        <ul class="list-disc ml-6">
            <li>PHP (Laravel Framework)</li>
            <li>Blade Templates (with reusable, DRY components)</li>
            <li>Tailwind CSS &amp; <span class="font-semibold">daisyUI</span> (UI framework for beautiful, consistent components)</li>
            <li>Alpine.js</li>
            <li><span class="font-semibold">Pest</span> (modern PHP testing framework for automated feature/scenario tests)</li>
            <li>SQLite</li>
        </ul>
        <p class="text-sm text-gray-600 mt-2">daisyUI is used for rapid, consistent UI styling. Pest enables expressive, maintainable automated tests. Blade components keep the UI DRY and maintainable.</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">License</h2>
        <p>This project is licensed for educational and demonstration purposes. For commercial use, please contact the author.</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Contact</h2>
        <ul class="list-none ml-0">
            <li><strong>Author:</strong> Simao Morais</li>
            <li><strong>Email:</strong> <a href="mailto:spmmazb@gmail.com">spmmazb@gmail.com</a>, <a href="mailto:simao.morais@trainee.inovcorp.com">simao.morais@trainee.inovcorp.com</a></li>
            <li><strong>Date:</strong> January 15, 2026</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">Acknowledgments</h2>
        <p>Special thanks to <strong>InovCorp</strong> for the opportunity, support, and resources provided throughout the development of this project.</p>

        <p class="mt-6 text-sm text-gray-500"><em>Note: Biblioteca is a work in progress. Features and design may change as development continues.</em></p>
    </div>
</x-layout>