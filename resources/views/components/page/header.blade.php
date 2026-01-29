<header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="marquee-container">
            <h1 class="marquee classy-marquee">
                {{ $slot }}
            </h1>
        </div>
    </div>
</header>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Playfair+Display:wght@700&display=swap');
    @keyframes marquee {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-100%);
        }
    }
    .marquee {
        display: inline-block;
        animation: marquee 15s linear infinite;
        white-space: nowrap;
    }
    .classy-marquee {
        font-family: 'Playfair Display', 'Montserrat', serif;
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        color: #23272f;
        text-shadow: 0 2px 8px rgba(0,0,0,0.07);
        background: linear-gradient(90deg, #f8fafc 0%, #e0e7ef 100%);
        padding: 0.25em 1em;
        border-radius: 0.5em;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.04);
    }
    .marquee-container {
        overflow: hidden;
        position: relative;
        width: 100%;
    }
    .marquee-container:hover .marquee {
        animation-play-state: paused;
    }
</style>
