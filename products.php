<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Products</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Inter font for nicer typography -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --deep-1: #0f1724; /* near slate-900 */
      --deep-2: #334155; /* slate-700 */
      --cream: #fbfaf8;
      --accent: #3b82f6;
    }
    html,body { font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    /* subtle card glass */
    .card-glass { background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border: 1px solid rgba(255,255,255,0.04); backdrop-filter: blur(6px); }
    /* smooth-image fit */
    .img-fit { object-fit: contain; }
    /* fade-in util */
    .fade-up { transform: translateY(18px); opacity: 0; transition: all .6s cubic-bezier(.16,.84,.24,1); }
    .fade-up.in-view { transform: translateY(0); opacity: 1; }
    /* product hover */
    .product-card { transition: transform .28s cubic-bezier(.16,.84,.24,1), box-shadow .28s; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(10,15,30,0.28); }
    /* hero parallax container */
    .hero-parallax { will-change: transform; }
    /* carousel */
    .carousel-scroll { scroll-behavior: smooth; -webkit-overflow-scrolling: touch; }
    /* custom scrollbar (subtle) */
    .no-scrollbar::-webkit-scrollbar { height: 8px; }
    .no-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.06); border-radius: 999px; }
  </style>
</head>
<body class="bg-gradient-to-b from-[color:var(--deep-1)] via-[color:var(--deep-2)] to-slate-700 text-slate-100 antialiased">

  <!-- NAV -->
  <header class="fixed w-full z-40">
    <div class="max-w-7xl mx-auto px-6">
      <nav class="flex items-center justify-between py-4">
        <a href="index.php" class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-white/8 flex items-center justify-center shadow-sm">
            <svg width="22" height="22" viewBox="0 0 24 24" class="text-cream" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 12C3 8 7 3 12 3s9 5 9 9-4 9-9 9S3 16 3 12z" stroke="white" stroke-opacity=".9" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h1 class="text-xl font-bold tracking-tight leading-none">PawVerse</h1>
            <p class="text-xs text-slate-300 -mt-1">Care • Comfort • Community</p>
          </div>
        </a>

        <div class="hidden md:flex items-center gap-6">
          <a href="index.php" class="text-slate-200 hover:text-white">Home</a>
          <a href="products.php" class="text-white font-semibold">Products</a>
          <a href="services.php" class="text-slate-200 hover:text-white">Services</a>
          <a href="about.php" class="text-slate-200 hover:text-white">About</a>
          <a href="contact.php" class="text-slate-200 hover:text-white">Contact</a>
          <a href="auth/login.php" class="ml-4 inline-flex items-center gap-2 bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white font-medium shadow-sm hover:bg-blue-500 transition">Log in</a>
        </div>

        <!-- mobile menu button -->
        <div class="md:hidden">
          <button aria-label="open menu" id="mobileToggle" class="p-2 rounded-lg bg-white/6">
            <svg width="22" height="22" viewBox="0 0 24 24" class="text-white" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 7h16M4 12h16M4 17h16" stroke="white" stroke-width="1.6" stroke-linecap="round"/></svg>
          </button>
        </div>
      </nav>
    </div>

    <!-- mobile menu (hidden) -->
    <div id="mobileMenu" class="md:hidden hidden bg-[rgba(8,10,20,0.4)] backdrop-blur-sm border-t border-white/6">
      <div class="px-6 py-4 flex flex-col gap-3">
        <a href="index.php" class="text-slate-200">Home</a>
        <a href="products.php" class="text-white font-semibold">Products</a>
        <a href="services.php" class="text-slate-200">Services</a>
        <a href="about.php" class="text-slate-200">About</a>
        <a href="contact.php" class="text-slate-200">Contact</a>
        <a href="auth/login.php" class="mt-2 inline-block bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white text-center">Log in</a>
      </div>
    </div>
  </header>

  <main class="pt-28">

    <!-- HERO (parallax) -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10">
        <!-- layered gradient + subtle texture -->
        <div class="h-full w-full bg-gradient-to-b from-slate-900/60 to-slate-700/70"></div>
        <div class="h-full w-full opacity-30" style="background-image: radial-gradient(circle at 10% 20%, rgba(59,130,246,0.08), transparent 10%), radial-gradient(circle at 90% 80%, rgba(255,255,255,0.02), transparent 20%);"></div>
      </div>

      <div class="max-w-7xl mx-auto px-6 py-20 lg:py-28 flex flex-col lg:flex-row items-center gap-10">
        <div class="lg:w-6/12">
          <div class="fade-up" data-delay="0">
            <p class="text-sm text-amber-200 font-semibold mb-3">New · Curated for comfort</p>
            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight text-white mb-4">Because every paw deserves premium care.</h2>
            <p class="text-slate-200 max-w-xl mb-6">PawVerse brings together thoughtfully selected products and trusted veterinary services — crafted for lasting well-being.</p>

            <div class="flex items-center gap-4">
              <a href="#featured" class="inline-flex items-center gap-3 bg-[color:var(--accent)] text-white px-5 py-3 rounded-lg shadow hover:scale-[1.01] transition">
                <svg width="18" height="18" viewBox="0 0 24 24" class="opacity-90" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7z" stroke="rgba(255,255,255,0.95)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Shop Featured
              </a>

              <a href="services.php" class="inline-flex items-center gap-2 px-4 py-3 rounded-lg border border-white/8 text-white/90 hover:bg-white/4 transition">
                Book a Vet
              </a>
            </div>
          </div>
        </div>

        <!-- hero image side (parallax) -->
        <div class="lg:w-1/2 flex justify-center hero-parallax" id="heroParallax" aria-hidden="true">
          <figure class="w-[520px] max-w-full rounded-3xl overflow-hidden shadow-2xl card-glass ring-1 ring-white/6">
            <!-- replace hero-pet.png with your generated hero image -->
            <img src="assets/images/hero-pet.png" alt="Happy dog with owner - PawVerse" class="w-full h-[420px] object-cover">
          </figure>
        </div>
      </div>
    </section>

    <!-- FILTER / SEARCH (floating card style) -->
    <section class="max-w-7xl mx-auto px-6 -mt-10">
      <div class="bg-[color:var(--cream)] rounded-xl p-5 md:p-6 shadow-xl card-glass border border-white/6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
          <div class="flex-1">
            <label for="search" class="sr-only">Search products</label>
            <input id="search" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]" placeholder="Search for treats, toys, supplies..." oninput="filterGrid()" />
          </div>

          <div class="w-full md:w-56">
            <select id="catFilter" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none" onchange="filterGrid()">
              <option value="all">All categories</option>
              <option value="dog">Dogs</option>
              <option value="cat">Cats</option>
              <option value="bird">Birds</option>
              <option value="fish">Fish</option>
            </select>
          </div>

          <div class="flex gap-3 justify-end">
            <button class="px-4 py-3 rounded-lg bg-white/6 border border-white/6 hover:bg-white/8 transition" onclick="clearFilters()">Reset</button>
            <button class="px-4 py-3 rounded-lg bg-[color:var(--accent)] text-white hover:bg-blue-500 transition">View All</button>
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURED CAROUSEL -->
    <section id="featured" class="max-w-7xl mx-auto px-6 mt-8">
      <h3 class="text-xl text-slate-100 font-semibold mb-4 fade-up" data-delay="120">Featured picks</h3>

      <div class="relative">
        <div id="carousel" class="flex gap-6 overflow-x-auto no-scrollbar carousel-scroll py-3" tabindex="0" aria-label="Featured products">
          <!-- each card is curated to look premium -->
          <article class="min-w-[320px] max-w-[320px] rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="dog">
            <img src="assets/images/dog.png" alt="Chew toy" class="w-full h-40 object-contain rounded-lg bg-white/4 mb-3">
            <h4 class="text-white font-semibold">Cloud Chew Toy</h4>
            <p class="text-slate-300 text-sm mt-1">Gentle on gums, built for joyful play.</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-amber-100 font-bold">$12.99</div>
              <button class="bg-[color:var(--accent)] px-3 py-2 rounded-lg text-white">Add</button>
            </div>
          </article>

          <article class="min-w-[320px] max-w-[320px] rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="cat">
            <img src="assets/images/cat.png" alt="Premium cat food" class="w-full h-40 object-contain rounded-lg bg-white/4 mb-3">
            <h4 class="text-white font-semibold">Feline Delight 2kg</h4>
            <p class="text-slate-300 text-sm mt-1">Balanced nutrition for active cats.</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-amber-100 font-bold">$19.99</div>
              <button class="bg-[color:var(--accent)] px-3 py-2 rounded-lg text-white">Add</button>
            </div>
          </article>

          <article class="min-w-[320px] max-w-[320px] rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="bird">
            <img src="assets/images/bird.png" alt="Bird cage" class="w-full h-40 object-contain rounded-lg bg-white/4 mb-3">
            <h4 class="text-white font-semibold">Feather Haven Cage</h4>
            <p class="text-slate-300 text-sm mt-1">Safe & cozy home for small birds.</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-amber-100 font-bold">$25.00</div>
              <button class="bg-[color:var(--accent)] px-3 py-2 rounded-lg text-white">Add</button>
            </div>
          </article>

          <article class="min-w-[320px] max-w-[320px] rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="fish">
            <img src="assets/images/fish.png" alt="Aquarium decor" class="w-full h-40 object-contain rounded-lg bg-white/4 mb-3">
            <h4 class="text-white font-semibold">Reefscape Decor Set</h4>
            <p class="text-slate-300 text-sm mt-1">Vibrant corals for calm aquariums.</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-amber-100 font-bold">$14.50</div>
              <button class="bg-[color:var(--accent)] px-3 py-2 rounded-lg text-white">Add</button>
            </div>
          </article>
        </div>

        <!-- carousel controls -->
        <button aria-label="previous" onclick="slideCarousel(-1)" class="absolute -left-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/6 flex items-center justify-center shadow">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white"><path d="M15 18l-6-6 6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <button aria-label="next" onclick="slideCarousel(1)" class="absolute -right-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/6 flex items-center justify-center shadow">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white"><path d="M9 6l6 6-6 6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
    </section>

    <!-- PRODUCT GRID -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h3 class="text-2xl font-semibold text-white mb-6 fade-up" data-delay="160">All Products</h3>

      <div id="grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <!-- Card template - static for now; will be populated by PHP later -->
        <article class="rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="dog">
          <div class="relative">
            <div class="aspect-[4/3] rounded-lg overflow-hidden bg-white/4 flex items-center justify-center">
              <img src="assets/images/dog.png" alt="Chew Toy for Dogs" class="w-40 img-fit">
            </div>
            <div class="absolute top-3 left-3 px-2 py-1 rounded-full bg-amber-100/10 text-amber-100 text-xs font-medium">Best seller</div>
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Chew Toy for Dogs</h4>
            <p class="text-slate-300 text-sm mt-2">Durable rubber toy that keeps teeth healthy and your pup entertained.</p>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <div class="text-amber-100 font-bold">$12.99</div>
            <button class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white">Add to cart</button>
          </div>
        </article>

        <article class="rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="cat">
          <div class="relative">
            <div class="aspect-[4/3] rounded-lg overflow-hidden bg-white/4 flex items-center justify-center">
              <img src="assets/images/cat.png" alt="Premium Cat Food" class="w-40 img-fit">
            </div>
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Premium Cat Food</h4>
            <p class="text-slate-300 text-sm mt-2">Complete nutrition for indoor and outdoor cats.</p>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <div class="text-amber-100 font-bold">$19.99</div>
            <button class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white">Add to cart</button>
          </div>
        </article>

        <article class="rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="bird">
          <div class="relative">
            <div class="aspect-[4/3] rounded-lg overflow-hidden bg-white/4 flex items-center justify-center">
              <img src="assets/images/bird.png" alt="Compact Bird Cage" class="w-40 img-fit">
            </div>
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Compact Bird Cage</h4>
            <p class="text-slate-300 text-sm mt-2">Portable and comfy cage for small to medium birds.</p>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <div class="text-amber-100 font-bold">$25.00</div>
            <button class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white">Add to cart</button>
          </div>
        </article>

        <article class="rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="fish">
          <div class="relative">
            <div class="aspect-[4/3] rounded-lg overflow-hidden bg-white/4 flex items-center justify-center">
              <img src="assets/images/fish.png" alt="Aquarium Decor Set" class="w-40 img-fit">
            </div>
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Aquarium Decor Set</h4>
            <p class="text-slate-300 text-sm mt-2">Vibrant corals and plants to beautify your tank.</p>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <div class="text-amber-100 font-bold">$14.50</div>
            <button class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white">Add to cart</button>
          </div>
        </article>

        <!-- duplicate more cards for grid visual density -->
        <article class="rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="dog">
          <div class="aspect-[4/3] rounded-lg overflow-hidden bg-white/4 flex items-center justify-center">
            <img src="assets/images/dog.png" alt="Plush Dog Bed" class="w-40 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Plush Dog Bed</h4>
            <p class="text-slate-300 text-sm mt-2">Ultra-soft bed for restful naps.</p>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <div class="text-amber-100 font-bold">$34.00</div>
            <button class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white">Add to cart</button>
          </div>
        </article>

        <article class="rounded-2xl bg-white/6 p-4 product-card fade-up" data-category="cat">
          <div class="aspect-[4/3] rounded-lg overflow-hidden bg-white/4 flex items-center justify-center">
            <img src="assets/images/cat.png" alt="Cat Tower" class="w-40 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Deluxe Cat Tower</h4>
            <p class="text-slate-300 text-sm mt-2">Multi-level play & rest area.</p>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <div class="text-amber-100 font-bold">$79.00</div>
            <button class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white">Add to cart</button>
          </div>
        </article>

      </div>
    </section>

    <!-- Lifestyle CTA -->
    <section class="max-w-7xl mx-auto px-6 pb-20">
      <div class="rounded-3xl overflow-hidden grid lg:grid-cols-2 gap-6 bg-white/5 p-8 items-center">
        <div class="px-6">
          <h3 class="text-3xl font-bold text-white mb-3">Because they’re family.</h3>
          <p class="text-slate-300 mb-6">From everyday essentials to compassionate care — PawVerse supports every moment of their life.</p>
          <div>
            <a href="about.php" class="inline-block bg-[color:var(--accent)] px-5 py-3 rounded-lg text-white font-semibold">Learn Our Story</a>
          </div>
        </div>
        <div class="flex items-center justify-center">
          <img src="assets/images/hero-pet.png" alt="Happy pet family" class="w-96 rounded-2xl shadow-2xl object-cover">
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="bg-slate-900 border-t border-white/6">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <h4 class="text-white font-bold">PawVerse</h4>
        <p class="text-slate-300 mt-2">Trusted pet products & veterinary services.</p>
      </div>

      <div>
        <h5 class="text-slate-200 font-semibold mb-2">Explore</h5>
        <ul class="text-slate-300 space-y-1">
          <li><a href="products.php">Shop</a></li>
          <li><a href="services.php">Services</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>

      <div>
        <h5 class="text-slate-200 font-semibold mb-2">Contact</h5>
        <p class="text-slate-300 text-sm">support@pawverse.com<br/>+880 1XXX-XXXXXX</p>
      </div>
    </div>
    <div class="text-center text-slate-500 text-sm py-6">© <?php echo date('Y'); ?> PawVerse. All rights reserved.</div>
  </footer>

  <!-- SCRIPTS: parallax, fade-in, carousel, filtering -->
  <script>
    /* mobile menu toggle */
    document.getElementById('mobileToggle').addEventListener('click', function(){
      const m = document.getElementById('mobileMenu');
      m.classList.toggle('hidden');
    });

    /* HERO PARALLAX */
    const heroParallax = document.getElementById('heroParallax');
    window.addEventListener('scroll', () => {
      const scrolled = window.scrollY;
      // small translate for parallax feel
      if (heroParallax) heroParallax.style.transform = `translateY(${Math.min(scrolled * 0.08, 40)}px)`;
    });

    /* FADE-IN ON SCROLL via IntersectionObserver */
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(entry.isIntersecting){
          entry.target.classList.add('in-view');
        }
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.fade-up').forEach((el, idx) => {
      // stagger using CSS variable (data-delay)
      const delay = el.dataset.delay ? Number(el.dataset.delay) : (idx * 40);
      el.style.transitionDelay = `${delay}ms`;
      observer.observe(el);
    });

    /* CAROUSEL - basic slide functionality */
    const carousel = document.getElementById('carousel');
    function slideCarousel(dir){
      if(!carousel) return;
      const width = carousel.querySelector('article').offsetWidth + 24; // gap
      carousel.scrollBy({ left: dir * width, behavior: 'smooth' });
    }
    // auto-advance every 4.5s
    let carouselTimer = setInterval(()=> slideCarousel(1), 4500);
    // pause on hover
    carousel.addEventListener('mouseenter', ()=> clearInterval(carouselTimer));
    carousel.addEventListener('mouseleave', ()=> carouselTimer = setInterval(()=> slideCarousel(1), 4500));

    /* FILTER & SEARCH */
    function filterGrid(){
      const search = document.getElementById('search').value.toLowerCase();
      const cat = document.getElementById('catFilter').value;
      document.querySelectorAll('#grid > article').forEach(card => {
        const title = card.querySelector('h4').textContent.toLowerCase();
        const catAttr = card.getAttribute('data-category');
        const match = (cat === 'all' || cat === catAttr) && title.includes(search);
        card.style.display = match ? '' : 'none';
      });
    }
    function clearFilters(){
      document.getElementById('search').value = '';
      document.getElementById('catFilter').value = 'all';
      filterGrid();
    }

    /* Accessibility: allow arrow keys for carousel */
    document.addEventListener('keydown', (e)=>{
      if(document.activeElement === carousel || document.activeElement === document.body){
        if(e.key === 'ArrowRight') slideCarousel(1);
        if(e.key === 'ArrowLeft') slideCarousel(-1);
      }
    });

    // small improvement: ensure fade-up elements become visible if JS disabled (progressive)
    document.documentElement.classList.remove('no-js');
    document.querySelectorAll('.fade-up').forEach(el => { el.classList.add('in-view'); }); // graceful if IntersectionObserver unsupported
  </script>
</body>
</html>
