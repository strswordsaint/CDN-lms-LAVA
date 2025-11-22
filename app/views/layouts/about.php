<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables === */
    :root {
        --bg-body: #eef2f6; 
        --primary-soft: #eff6ff;
        --primary-border: #bfdbfe;
        --primary-text: #1d4ed8;
    }
    
    body {
        background-color: var(--bg-body);
        font-family: 'Inter', sans-serif;
    }

    /* === DESIGNED BANNER === */
    .page-banner {
        background: white;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        padding: 3rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        margin-bottom: 3rem;
    }
    
    .banner-decoration {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.6;
        z-index: 0;
    }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #dbeafe; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #e0e7ff; }

    .banner-content {
        position: relative;
        z-index: 10;
    }

    /* === INFO CARDS === */
    .info-card {
        @apply bg-white rounded-2xl shadow-sm border border-neutral-200 p-8 h-full transition-transform duration-300;
    }
    .info-card:hover {
        @apply shadow-md transform -translate-y-1;
    }

    .icon-box {
        @apply w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-6;
    }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-neutral-900 tracking-tight mb-4">
            <?php echo $page_title ?? 'About Us'; ?>
        </h1>
        <p class="text-lg text-neutral-500 max-w-2xl mx-auto">
            Shaping the future through academic excellence, innovation, and community service in Oriental Mindoro.
        </p>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-16">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        
        <div class="info-card relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="icon-box bg-blue-50 text-blue-600">
                    <i class="fas fa-eye"></i>
                </div>
                <h2 class="text-2xl font-bold text-neutral-900 mb-4">Our Vision</h2>
                <p class="text-neutral-600 leading-relaxed text-lg">
                    Colegio de Naujan is envisioned as a center of excellence in academic, business, technical education, and farming technology, catering to the demands of the competitive higher education market—not only in Oriental Mindoro but nationally and globally.
                </p>
            </div>
        </div>

        <div class="info-card relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="icon-box bg-amber-50 text-amber-600">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h2 class="text-2xl font-bold text-neutral-900 mb-4">Our Mission</h2>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-amber-500 mt-1 flex-shrink-0"></i>
                        <span class="text-neutral-600">Providing advanced, accessible, and high-quality instruction across its core pillars.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-amber-500 mt-1 flex-shrink-0"></i>
                        <span class="text-neutral-600">Developing competent, innovative, and ethical professionals who meet global standards.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-amber-500 mt-1 flex-shrink-0"></i>
                        <span class="text-neutral-600">Engaging in research and extension services for the sustainable development of the nation.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden mb-16">
        <div class="p-8 border-b border-neutral-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-neutral-50">
            <div>
                <h2 class="text-2xl font-bold text-neutral-900">Our Campus</h2>
                <p class="text-neutral-500">Visit us in the heart of Naujan.</p>
            </div>
            <a href="https://www.google.com/maps" target="_blank" class="btn btn-white border border-neutral-300 text-neutral-700 hover:bg-neutral-100 px-4 py-2 rounded-lg shadow-sm font-medium transition-colors">
                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i> Open in Maps
            </a>
        </div>
        <div class="relative h-[450px] bg-neutral-100 w-full">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3882.702680757701!2d121.28968137146867!3d13.306503887040469!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bcc10073ad8811%3A0xa6c83c0ae9ff799a!2sColegio%20De%20Naujan!5e0!3m2!1sen!2sph!4v1763449673681!5m2!1sen!2sph" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                class="absolute inset-0">
            </iframe>
        </div>
    </div>

    <div class="text-center max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-neutral-900 mb-3">Connect With Us</h2>
        <p class="text-neutral-500 mb-8">Have questions? Reach out to our administration or follow us online.</p>
        
        <div class="flex flex-wrap justify-center gap-4">
            <a href="https://www.facebook.com/colegiodenaujan" target="_blank" class="flex items-center px-6 py-3 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fab fa-facebook-f mr-2 text-xl"></i> Facebook
            </a>
            <a href="mailto:miacasanova519@gmail.com" class="flex items-center px-6 py-3 bg-white border border-neutral-200 text-neutral-700 rounded-full font-semibold hover:bg-neutral-50 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                <i class="fas fa-envelope mr-2 text-xl text-red-500"></i> Email Us
            </a>
            <a href="https://en.wikipedia.org/wiki/Naujan" target="_blank" class="flex items-center px-6 py-3 bg-white border border-neutral-200 text-neutral-700 rounded-full font-semibold hover:bg-neutral-50 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                <i class="fas fa-globe mr-2 text-xl text-indigo-500"></i> About Naujan
            </a>
        </div>
    </div>

</div>

<?php include 'app/views/layouts/footer.php'; ?>