<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-4xl">

    <h1 class="text-3xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'About Us'; ?></h1>

    <div class="card p-6 md:p-8 space-y-8">

        <div>
            <h2 class="text-2xl font-semibold text-neutral-800 mb-4">Our Location</h2>
            <div class="overflow-hidden rounded-lg border border-neutral-200">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3882.702680757701!2d121.28968137146867!3d13.306503887040469!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bcc10073ad8811%3A0xa6c83c0ae9ff799a!2sColegio%20De%20Naujan!5e0!3m2!1sen!2sph!4v1763449673681!5m2!1sen!2sph" 
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-neutral-800 mb-4">Our Vision & Mission</h2>
            <div class="prose max-w-none text-neutral-700">

                <h3 class="text-xl font-semibold text-primary-800">Vision</h3>
                <p class="text-lg font-medium">
                    Colegio de Naujan is envisioned as a center of excellence
                    in academic, business, technical education, and farming technology,
                    catering to the demands of the competitive higher education market—not only
                    in Oriental Mindoro but nationally and globally.
                </p>

                <h3 class="text-xl font-semibold text-primary-800 mt-6">Mission</h3>
                <p>
                    To achieve this vision, Colegio de Naujan is committed to:
                </p>
                <ul class="list-disc list-outside space-y-2 pl-5">
                    <li>
                        Providing advanced, accessible, and high-quality instruction across its core pillars of academics, business, technical education, and farming technology.
                    </li>
                    <li>
                        Developing competent, innovative, and ethical professionals who are prepared to meet and exceed national and global industry standards.
                    </li>
                    <li>
                        Engaging in relevant research and community extension services that directly contribute to the sustainable development of Naujan, Oriental Mindoro, and the nation.
                    </li>
                </ul>

            </div>
        </div>
        <div>
            <h2 class="text-2xl font-semibold text-neutral-800 mb-4">Connect With Us</h2>
            <div class="flex flex-wrap gap-4">
                <a href="https://www.facebook.com/colegiodenaujan" class="btn btn-primary">
                    <i class="fab fa-facebook-f mr-2"></i> Facebook
                </a>
                <a href="mailto:miacasanova519@gmail.com" class="btn btn-secondary">
                    <i class="fas fa-envelope mr-2"></i> Email Us
                </a>
                <a href="https://en.wikipedia.org/wiki/Naujan" class="btn btn-secondary">
                    <i class="fas fa-globe mr-2"></i> About Naujan
                </a>
            </div>
        </div>

    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>