<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Services</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --deep-1: #0f1724;
      --deep-2: #334155;
      --cream: #fbfaf8;
      --accent: #3b82f6;
    }
    html,body { font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .card-glass { background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border: 1px solid rgba(255,255,255,0.04); backdrop-filter: blur(6px); }
    .fade-up { transform: translateY(18px); opacity: 0; transition: all .6s cubic-bezier(.16,.84,.24,1); }
    .fade-up.in-view { transform: translateY(0); opacity: 1; }
    .product-card, .vet-card { transition: transform .28s cubic-bezier(.16,.84,.24,1), box-shadow .28s; }
    .product-card:hover, .vet-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(10,15,30,0.24); }
    .hero-parallax { will-change: transform; }
    .modal-backdrop { background: rgba(3,7,18,0.6); }
    .img-fit { object-fit: contain; }
  </style>
</head>
<body class="bg-gradient-to-b from-[color:var(--deep-1)] via-[color:var(--deep-2)] to-slate-700 text-slate-100 antialiased">

  <!-- NAV -->
  <header class="fixed w-full z-40">
    <div class="max-w-7xl mx-auto px-6">
      <nav class="flex items-center justify-between py-4">
        <a href="index.php" class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-white/8 flex items-center justify-center shadow-sm">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 12C3 8 7 3 12 3s9 5 9 9-4 9-9 9S3 16 3 12z" stroke="white" stroke-opacity=".9" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h1 class="text-xl font-bold tracking-tight leading-none">PawVerse</h1>
            <p class="text-xs text-slate-300 -mt-1">Care • Comfort • Community</p>
          </div>
        </a>

        <div class="hidden md:flex items-center gap-6">
          <a href="index.php" class="text-slate-200 hover:text-white">Home</a>
          <a href="products.php" class="text-slate-200 hover:text-white">Products</a>
          <a href="services.php" class="text-white font-semibold">Services</a>
          <a href="about.php" class="text-slate-200 hover:text-white">About</a>
          <a href="contact.php" class="text-slate-200 hover:text-white">Contact</a>
          <a href="auth/login.php" class="ml-4 inline-flex items-center gap-2 bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white font-medium shadow-sm hover:bg-blue-500 transition">Log in</a>
        </div>

        <div class="md:hidden">
          <button aria-label="open menu" id="mobileToggle" class="p-2 rounded-lg bg-white/6">
            <svg width="22" height="22" viewBox="0 0 24 24" class="text-white" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 7h16M4 12h16M4 17h16" stroke="white" stroke-width="1.6" stroke-linecap="round"/></svg>
          </button>
        </div>
      </nav>
    </div>

    <div id="mobileMenu" class="md:hidden hidden bg-[rgba(8,10,20,0.4)] backdrop-blur-sm border-t border-white/6">
      <div class="px-6 py-4 flex flex-col gap-3">
        <a href="index.php" class="text-slate-200">Home</a>
        <a href="products.php" class="text-slate-200">Products</a>
        <a href="services.php" class="text-white font-semibold">Services</a>
        <a href="about.php" class="text-slate-200">About</a>
        <a href="contact.php" class="text-slate-200">Contact</a>
        <a href="auth/login.php" class="mt-2 inline-block bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white text-center">Log in</a>
      </div>
    </div>
  </header>

  <main class="pt-28">

    <!-- HERO -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10">
        <div class="h-full w-full bg-gradient-to-b from-slate-900/60 to-slate-700/70"></div>
        <div class="h-full w-full opacity-30" style="background-image: radial-gradient(circle at 10% 20%, rgba(59,130,246,0.06), transparent 10%), radial-gradient(circle at 90% 80%, rgba(255,255,255,0.02), transparent 20%);"></div>
      </div>

      <div class="max-w-7xl mx-auto px-6 py-20 lg:py-28 flex flex-col lg:flex-row items-center gap-10">
        <div class="lg:w-6/12">
          <div class="fade-up" data-delay="0">
            <p class="text-sm text-amber-200 font-semibold mb-3">Trusted care · Local vets</p>
            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight text-white mb-4">Your pet’s health, simplified.</h2>
            <p class="text-slate-200 max-w-xl mb-6">Connect with verified veterinarians and schedule hands-on appointments — quick, safe, and compassionate care for every paw.</p>
            <div class="flex items-center gap-4">
              <a href="#vetGrid" class="inline-flex items-center gap-3 bg-[color:var(--accent)] text-white px-5 py-3 rounded-lg shadow hover:scale-[1.01] transition">Meet Vets</a>
              <button id="openModalCTA" class="inline-flex items-center gap-2 px-4 py-3 rounded-lg border border-white/8 text-white/90 hover:bg-white/4 transition">Book Now</button>
            </div>
          </div>
        </div>

        <div class="lg:w-1/2 flex justify-center hero-parallax" id="heroParallax">
          <figure class="w-[520px] max-w-full rounded-3xl overflow-hidden shadow-2xl card-glass ring-1 ring-white/6">
            <img src="assets/images/hero-pet.png" alt="Happy dog with owner - PawVerse" class="w-full h-[420px] object-cover">
          </figure>
        </div>
      </div>
    </section>

    <!-- VET PROFILES GRID -->
    <section id="vetGrid" class="max-w-7xl mx-auto px-6 py-10">
      <h3 class="text-2xl font-semibold text-white mb-6 fade-up" data-delay="120">Our Vets</h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Vet card example -->
        <article class="vet-card rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="160" data-specialty="dog">
          <div class="flex flex-col items-start">
            <div class="w-full rounded-xl overflow-hidden bg-white/4 aspect-[4/3] flex items-center justify-center">
              <img src="assets/images/vet1.png" alt="Dr. Lina" class="w-40 img-fit">
            </div>
            <div class="mt-4 w-full">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-white font-semibold">Dr. Lina Chowdhury</h4>
                  <p class="text-slate-300 text-sm">Veterinarian · Small animals</p>
                </div>
                <div class="text-amber-100 font-bold">4.9★</div>
              </div>
              <p class="text-slate-300 text-sm mt-3">Experienced in surgery & internal medicine. Available Mon–Sat.</p>

              <div class="mt-4 flex items-center justify-between">
                <div class="text-slate-300 text-sm">Fee: <span class="font-semibold text-white">$18</span></div>
                <button class="book-now-btn bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white" data-vet="1" data-vet-name="Dr. Lina Chowdhury">Book Now</button>
              </div>
            </div>
          </div>
        </article>

        <article class="vet-card rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="180" data-specialty="cat">
          <div class="flex flex-col items-start">
            <div class="w-full rounded-xl overflow-hidden bg-white/4 aspect-[4/3] flex items-center justify-center">
              <img src="assets/images/vet2.png" alt="Dr. Rahim" class="w-40 img-fit">
            </div>
            <div class="mt-4 w-full">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-white font-semibold">Dr. Rahim Ahmed</h4>
                  <p class="text-slate-300 text-sm">Veterinarian · Dermatology</p>
                </div>
                <div class="text-amber-100 font-bold">4.8★</div>
              </div>
              <p class="text-slate-300 text-sm mt-3">Specialized in skin & allergy care. Available Tue–Sun.</p>

              <div class="mt-4 flex items-center justify-between">
                <div class="text-slate-300 text-sm">Fee: <span class="font-semibold text-white">$20</span></div>
                <button class="book-now-btn bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white" data-vet="2" data-vet-name="Dr. Rahim Ahmed">Book Now</button>
              </div>
            </div>
          </div>
        </article>

        <article class="vet-card rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="200" data-specialty="bird">
          <div class="flex flex-col items-start">
            <div class="w-full rounded-xl overflow-hidden bg-white/4 aspect-[4/3] flex items-center justify-center">
              <img src="assets/images/vet3.png" alt="Dr. Sara" class="w-40 img-fit">
            </div>
            <div class="mt-4 w-full">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-white font-semibold">Dr. Sara Khan</h4>
                  <p class="text-slate-300 text-sm">Veterinarian · Avian Specialist</p>
                </div>
                <div class="text-amber-100 font-bold">4.7★</div>
              </div>
              <p class="text-slate-300 text-sm mt-3">Expert in avian care and nutrition. Available Wed–Sun.</p>

              <div class="mt-4 flex items-center justify-between">
                <div class="text-slate-300 text-sm">Fee: <span class="font-semibold text-white">$22</span></div>
                <button class="book-now-btn bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white" data-vet="3" data-vet-name="Dr. Sara Khan">Book Now</button>
              </div>
            </div>
          </div>
        </article>

        <!-- add 3 more for a full grid -->
        <article class="vet-card rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="220" data-specialty="dog">
          <div class="w-full rounded-xl overflow-hidden bg-white/4 aspect-[4/3] flex items-center justify-center">
            <img src="assets/images/vet4.png" alt="Dr. Imran" class="w-40 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Dr. Imran Hossain</h4>
            <p class="text-slate-300 text-sm mt-2">Veterinarian · General Practice</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-amber-100 font-bold">4.6★</div>
              <button class="book-now-btn bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white" data-vet="4" data-vet-name="Dr. Imran Hossain">Book Now</button>
            </div>
          </div>
        </article>

        <article class="vet-card rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="240" data-specialty="cat">
          <div class="w-full rounded-xl overflow-hidden bg-white/4 aspect-[4/3] flex items-center justify-center">
            <img src="assets/images/vet5.png" alt="Dr. Nabila" class="w-40 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Dr. Nabila Rahman</h4>
            <p class="text-slate-300 text-sm mt-2">Veterinarian · Nutritionist</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-amber-100 font-bold">4.9★</div>
              <button class="book-now-btn bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white" data-vet="5" data-vet-name="Dr. Nabila Rahman">Book Now</button>
            </div>
          </div>
        </article>

        <article class="vet-card rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="260" data-specialty="fish">
          <div class="w-full rounded-xl overflow-hidden bg-white/4 aspect-[4/3] flex items-center justify-center">
            <img src="assets/images/vet6.png" alt="Dr. Omar" class="w-40 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Dr. Omar Faruque</h4>
            <p class="text-slate-300 text-sm mt-2">Veterinarian · Aquatic Health</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-amber-100 font-bold">4.5★</div>
              <button class="book-now-btn bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white" data-vet="6" data-vet-name="Dr. Omar Faruque">Book Now</button>
            </div>
          </div>
        </article>

      </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h3 class="text-2xl font-semibold text-white mb-6 fade-up" data-delay="280">How it works</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl bg-white/6 p-6 card-glass fade-up" data-delay="300">
          <div class="w-12 h-12 rounded-lg bg-[color:var(--accent)]/10 flex items-center justify-center text-[color:var(--accent)] font-semibold mb-4">1</div>
          <h4 class="text-white font-semibold">Search & Select</h4>
          <p class="text-slate-300 text-sm mt-2">Find a vet by specialty, rating, or availability.</p>
        </div>

        <div class="rounded-2xl bg-white/6 p-6 card-glass fade-up" data-delay="340">
          <div class="w-12 h-12 rounded-lg bg-[color:var(--accent)]/10 flex items-center justify-center text-[color:var(--accent)] font-semibold mb-4">2</div>
          <h4 class="text-white font-semibold">Book an Appointment</h4>
          <p class="text-slate-300 text-sm mt-2">Choose date & time, and share pet details in the modal form.</p>
        </div>

        <div class="rounded-2xl bg-white/6 p-6 card-glass fade-up" data-delay="380">
          <div class="w-12 h-12 rounded-lg bg-[color:var(--accent)]/10 flex items-center justify-center text-[color:var(--accent)] font-semibold mb-4">3</div>
          <h4 class="text-white font-semibold">Visit & Care</h4>
          <p class="text-slate-300 text-sm mt-2">Arrive at the clinic and get professional care for your pet.</p>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="max-w-7xl mx-auto px-6 pb-20">
      <div class="rounded-3xl overflow-hidden grid lg:grid-cols-2 gap-6 bg-white/5 p-8 items-center">
        <div class="px-6">
          <h3 class="text-3xl font-bold text-white mb-3">Ready for a checkup?</h3>
          <p class="text-slate-300 mb-6">Book quickly and keep your pet healthy — our vets are here when you need them.</p>
          <div>
            <button id="openModalCTA2" class="inline-block bg-[color:var(--accent)] px-5 py-3 rounded-lg text-white font-semibold">Book Your Visit</button>
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

  <!-- MODAL (hidden by default) -->
  <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 modal-backdrop" id="modalBackdrop"></div>
    <div class="relative max-w-2xl w-full mx-4">
      <div class="bg-white rounded-2xl p-6 text-slate-900 shadow-2xl">
        <header class="flex items-center justify-between mb-3">
          <h4 class="text-lg font-semibold">Book Appointment</h4>
          <button id="modalClose" aria-label="Close" class="p-2 rounded-lg hover:bg-slate-100">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="#0f1724" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </header>

        <form id="bookingForm" class="space-y-3">
          <input type="hidden" id="selectedVetId" name="vet_id" />
          <div>
            <label class="text-sm font-medium">Veterinarian</label>
            <div id="selectedVetName" class="text-sm text-slate-600">—</div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <input id="b_name" name="name" required placeholder="Your name" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[color:var(--accent)]" />
            <input id="b_email" name="email" type="email" required placeholder="Email" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[color:var(--accent)]" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <select id="b_pet_type" name="pet_type" required class="px-3 py-2 border rounded-lg">
              <option value="">Pet type</option>
              <option value="dog">Dog</option>
              <option value="cat">Cat</option>
              <option value="bird">Bird</option>
              <option value="fish">Fish</option>
              <option value="other">Other</option>
            </select>

            <input id="b_pet_name" name="pet_name" placeholder="Pet's name" class="px-3 py-2 border rounded-lg" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <input id="b_date" name="date" type="date" required class="px-3 py-2 border rounded-lg" />
            <input id="b_time" name="time" type="time" required class="px-3 py-2 border rounded-lg" />
          </div>

          <div>
            <textarea id="b_message" name="message" rows="3" placeholder="Any notes (symptoms, preferences)" class="w-full px-3 py-2 border rounded-lg"></textarea>
          </div>

          <div class="flex items-center justify-between mt-2">
            <div class="text-sm text-slate-500">We will email a confirmation. This is a demo booking (no payment).</div>
            <div class="flex gap-3">
              <button type="button" id="modalCancel" class="px-4 py-2 rounded-lg border">Cancel</button>
              <button type="submit" class="px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white">Confirm Booking</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // mobile toggle
    document.getElementById('mobileToggle').addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // parallax hero
    const heroParallax = document.getElementById('heroParallax');
    window.addEventListener('scroll', () => {
      const scrolled = window.scrollY;
      if(heroParallax) heroParallax.style.transform = `translateY(${Math.min(scrolled * 0.06, 36)}px)`;
    });

    // fade-in observer
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(entry.isIntersecting) entry.target.classList.add('in-view');
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.fade-up').forEach((el, idx) => {
      const delay = el.dataset.delay ? Number(el.dataset.delay) : (idx * 40);
      el.style.transitionDelay = `${delay}ms`;
      observer.observe(el);
    });

    // modal logic
    const modal = document.getElementById('modal');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalClose = document.getElementById('modalClose');
    const modalCancel = document.getElementById('modalCancel');
    const bookingForm = document.getElementById('bookingForm');
    const selectedVetName = document.getElementById('selectedVetName');
    const selectedVetId = document.getElementById('selectedVetId');

    function openModalForVet(vetId, vetName) {
      selectedVetName.textContent = vetName || '—';
      selectedVetId.value = vetId || '';
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      // focus first input slightly delayed
      setTimeout(()=> document.getElementById('b_name').focus(), 200);
    }

    document.querySelectorAll('.book-now-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const vetId = btn.getAttribute('data-vet');
        const vetName = btn.getAttribute('data-vet-name');
        openModalForVet(vetId, vetName);
      });
    });

    // hero CTA open modal
    document.getElementById('openModalCTA').addEventListener('click', ()=> openModalForVet('', ''));
    document.getElementById('openModalCTA2').addEventListener('click', ()=> openModalForVet('', ''));

    const closeModal = () => {
      modal.classList.add('hidden');
      document.body.style.overflow = '';
      bookingForm.reset();
    };

    modalClose.addEventListener('click', closeModal);
    modalCancel.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', closeModal);

    // simple client-side validation + demo submit
    bookingForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const name = document.getElementById('b_name').value.trim();
      const email = document.getElementById('b_email').value.trim();
      const date = document.getElementById('b_date').value;
      const time = document.getElementById('b_time').value;

      if(!name || !email || !date || !time) {
        alert('Please complete required fields (name, email, date, time).');
        return;
      }

      // simulate success (in real app, submit via AJAX or form POST to PHP endpoint)
      closeModal();
      // small success UI
      const notice = document.createElement('div');
      notice.className = 'fixed right-6 bottom-6 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg';
      notice.textContent = 'Booking confirmed (demo). Check your email for details.';
      document.body.appendChild(notice);
      setTimeout(()=> notice.remove(), 4000);
    });

    // allow book-now buttons in grid to open modal
    document.querySelectorAll('.book-now-btn').forEach(btn => {
      btn.addEventListener('keydown', (e) => {
        if(e.key === 'Enter') {
          const vetId = btn.getAttribute('data-vet');
          const vetName = btn.getAttribute('data-vet-name');
          openModalForVet(vetId, vetName);
        }
      });
    });

    // keyboard accessible close (Esc)
    document.addEventListener('keydown', (e) => {
      if(e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    // progressive enhancement fallback
    document.querySelectorAll('.fade-up').forEach(el => el.classList.add('in-view'));
  </script>
</body>
</html>
