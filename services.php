<?php include('includes/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Services</title>

  <!-- Tailwind & Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    /* ===============================
       THEME: Light (default) + Dark Mode
       =============================== */
    :root {
      --deep-1: #ffffff;
      --deep-2: #f8fafc;
      --cream: #ffffff;
      --accent: #3b82f6;
      --text: #0f172a;
      --panel: #ffffff;
      --soft: #f1f5f9;
    }

    body.theme-dark {
      --deep-1: #0f1724;
      --deep-2: #334155;
      --cream: rgba(255,255,255,0.03);
      --accent: #3b82f6;
      --text: #e6eef8;
      --panel: rgba(255,255,255,0.05);
      --soft: rgba(255,255,255,0.04);
      background: linear-gradient(135deg, var(--deep-1), var(--deep-2));
      color: var(--text);
    }

    body.theme-transition {
      transition: background-color 0.4s ease, color 0.4s ease;
    }

    /* Toggle switch */
    .switch {
      width: 52px;
      height: 30px;
      border-radius: 999px;
      padding: 3px;
      display: inline-flex;
      align-items: center;
      cursor: pointer;
      background: rgba(0, 0, 0, 0.1);
      transition: background 0.25s ease;
    }
    .switch .knob {
      width: 24px;
      height: 24px;
      border-radius: 999px;
      background: white;
      transform: translateX(0);
      transition: transform 0.25s ease, background 0.25s;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .switch.on { background: linear-gradient(90deg, var(--accent), #60a5fa); }
    .switch.on .knob { transform: translateX(22px); }

    /* Shared utilities */
    html,body { font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .card-glass { background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.03)); border: 1px solid rgba(255,255,255,0.06); backdrop-filter: blur(6px); }
    .fade-up { transform: translateY(18px); opacity: 0; transition: all .6s cubic-bezier(.16,.84,.24,1); }
    .fade-up.in-view { transform: translateY(0); opacity: 1; }
    .product-card, .vet-card { transition: transform .28s cubic-bezier(.16,.84,.24,1), box-shadow .28s; }
    .product-card:hover, .vet-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(10,15,30,0.24); }
    .hero-parallax { will-change: transform; }
    .modal-backdrop { background: rgba(3,7,18,0.6); }
    .img-fit { object-fit: contain; }
  </style>
</head>

<body class="bg-[color:var(--cream)] text-[color:var(--text)] antialiased">

  <!-- NAV -->
  <header class="w-full z-40 bg-[color:var(--cream)]/80 backdrop-blur-md border-b border-white/10">
    <div class="max-w-7xl mx-auto px-6">
      <nav class="flex items-center justify-between py-4">
        <a href="index.php" class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-white/8 flex items-center justify-center shadow-sm">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 12C3 8 7 3 12 3s9 5 9 9-4 9-9 9S3 16 3 12z" stroke="currentColor" stroke-opacity=".9" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h1 class="text-xl font-bold tracking-tight leading-none">PawVerse</h1>
            <p class="text-xs text-slate-500 -mt-1">Care • Comfort • Community</p>
          </div>
        </a>

        <div class="hidden md:flex items-center gap-6">
          <a href="index.php" class="hover:text-[color:var(--accent)]">Home</a>
          <a href="products.php" class="hover:text-[color:var(--accent)]">Products</a>
          <a href="services.php" class="text-[color:var(--accent)] font-semibold">Services</a>
          <a href="about.php" class="hover:text-[color:var(--accent)]">About</a>
          <a href="contact.php" class="hover:text-[color:var(--accent)]">Contact</a>
          <a href="auth/login.php" class="ml-4 inline-flex items-center gap-2 bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white font-medium shadow-sm hover:bg-blue-500 transition">Log in</a>

          <!-- THEME TOGGLE -->
          <div class="flex items-center gap-2 ml-4">
            <div class="text-xs opacity-70">☀️</div>
            <div id="themeSwitch" role="switch" aria-checked="false" tabindex="0" class="switch" title="Toggle dark mode">
              <div class="knob"></div>
            </div>
            <div class="text-xs opacity-70">🌙</div>
          </div>
        </div>

        <div class="md:hidden">
          <button aria-label="open menu" id="mobileToggle" class="p-2 rounded-lg bg-white/6">
            <svg width="22" height="22" viewBox="0 0 24 24" class="text-[color:var(--text)]" fill="none"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
          </button>
        </div>
      </nav>
    </div>

    <!-- mobile menu -->
    <div id="mobileMenu" class="md:hidden hidden bg-[rgba(8,10,20,0.1)] backdrop-blur-sm border-t border-white/6">
      <div class="px-6 py-4 flex flex-col gap-3">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="services.php" class="font-semibold text-[color:var(--accent)]">Services</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="auth/login.php" class="mt-2 inline-block bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white text-center">Log in</a>
      </div>
    </div>
  </header>

  <main class="pt-28">
    <!-- HERO -->
    <section class="relative overflow-hidden bg-[color:var(--soft)]">
      <div class="max-w-7xl mx-auto px-6 py-20 lg:py-28 flex flex-col lg:flex-row items-center gap-10">
        <div class="lg:w-6/12">
          <p class="text-sm font-semibold mb-3 text-[color:var(--accent)]">Trusted care · Local vets</p>
          <h2 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4">Your pet’s health, simplified.</h2>
          <p class="opacity-80 mb-6">Connect with verified veterinarians and schedule hands-on appointments — quick, safe, and compassionate care for every paw.</p>
          <div class="flex items-center gap-4">
            <a href="#vetGrid" class="inline-flex items-center gap-3 bg-[color:var(--accent)] text-white px-5 py-3 rounded-lg shadow hover:scale-[1.01] transition">Meet Vets</a>
            <button id="openModalCTA" class="inline-flex items-center gap-2 px-4 py-3 rounded-lg border border-[color:var(--accent)] text-[color:var(--accent)] hover:bg-[color:var(--accent)] hover:text-white transition">Book Now</button>
          </div>
        </div>
        <div class="lg:w-1/2 flex justify-center hero-parallax" id="heroParallax">
          <img src="assets/images/hero-pet.png" alt="PawVerse Vet" class="rounded-3xl shadow-lg w-[520px] h-[420px] object-cover">
        </div>
      </div>
    </section>

    <!-- VETS GRID -->
    <section id="vetGrid" class="max-w-7xl mx-auto px-6 py-10">
      <h3 class="text-2xl font-semibold mb-6">Our Vets</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Each card -->
        <?php
        $vets = [
          ["id"=>1,"name"=>"Dr. Lina Chowdhury","spec"=>"Small animals","img"=>"vet1.png","desc"=>"Experienced in surgery & internal medicine.","fee"=>18,"rating"=>"4.9★"],
          ["id"=>2,"name"=>"Dr. Rahim Ahmed","spec"=>"Dermatology","img"=>"vet2.png","desc"=>"Specialized in skin & allergy care.","fee"=>20,"rating"=>"4.8★"],
          ["id"=>3,"name"=>"Dr. Sara Khan","spec"=>"Avian Specialist","img"=>"vet3.png","desc"=>"Expert in avian care and nutrition.","fee"=>22,"rating"=>"4.7★"],
          ["id"=>4,"name"=>"Dr. Imran Hossain","spec"=>"General Practice","img"=>"vet4.png","desc"=>"Reliable care for all small pets.","fee"=>19,"rating"=>"4.6★"],
          ["id"=>5,"name"=>"Dr. Nabila Rahman","spec"=>"Nutritionist","img"=>"vet5.png","desc"=>"Helps pets stay fit & healthy.","fee"=>21,"rating"=>"4.9★"],
          ["id"=>6,"name"=>"Dr. Omar Faruque","spec"=>"Aquatic Health","img"=>"vet6.png","desc"=>"Specialist in fish & aquarium health.","fee"=>17,"rating"=>"4.5★"]
        ];
        foreach($vets as $v){
          echo "
          <article class='vet-card rounded-2xl bg-[color:var(--panel)] p-5 shadow-md fade-up'>
            <img src='assets/images/{$v['img']}' alt='{$v['name']}' class='w-full h-52 object-contain rounded-lg mb-4'>
            <h4 class='font-semibold text-lg'>{$v['name']}</h4>
            <p class='text-sm opacity-80 mb-2'>Veterinarian · {$v['spec']}</p>
            <p class='text-sm opacity-70 mb-4'>{$v['desc']}</p>
            <div class='flex items-center justify-between'>
              <div class='text-[color:var(--accent)] font-semibold'>Fee: \${$v['fee']}</div>
              <button class='book-now-btn bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white' data-vet='{$v['id']}' data-vet-name='{$v['name']}'>Book Now</button>
            </div>
          </article>";
        }
        ?>
      </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h3 class="text-2xl font-semibold mb-6">How it works</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl bg-[color:var(--panel)] p-6 shadow-md fade-up">
          <h4 class="font-semibold mb-2">1. Search & Select</h4>
          <p class="opacity-80 text-sm">Find a vet by specialty, rating, or availability.</p>
        </div>
        <div class="rounded-2xl bg-[color:var(--panel)] p-6 shadow-md fade-up">
          <h4 class="font-semibold mb-2">2. Book Appointment</h4>
          <p class="opacity-80 text-sm">Choose a date and share pet details in the form.</p>
        </div>
        <div class="rounded-2xl bg-[color:var(--panel)] p-6 shadow-md fade-up">
          <h4 class="font-semibold mb-2">3. Visit & Care</h4>
          <p class="opacity-80 text-sm">Arrive at the clinic and get professional care for your pet.</p>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="max-w-7xl mx-auto px-6 pb-20">
      <div class="rounded-3xl overflow-hidden grid lg:grid-cols-2 gap-6 bg-[color:var(--panel)] p-8 items-center shadow-lg">
        <div class="px-6">
          <h3 class="text-3xl font-bold mb-3">Ready for a checkup?</h3>
          <p class="opacity-80 mb-6">Book quickly and keep your pet healthy — our vets are here when you need them.</p>
          <button id="openModalCTA2" class="inline-block bg-[color:var(--accent)] px-5 py-3 rounded-lg text-white font-semibold">Book Your Visit</button>
        </div>
        <div class="flex items-center justify-center">
          <img src="assets/images/hero-pet.png" alt="Happy pet family" class="w-96 rounded-2xl shadow-2xl object-cover">
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="bg-[color:var(--deep-2)] text-[color:var(--text)] border-t border-white/10">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <h4 class="font-bold text-lg">PawVerse</h4>
        <p class="opacity-70 mt-2">Trusted pet products & veterinary services.</p>
      </div>
      <div>
        <h5 class="font-semibold mb-2">Explore</h5>
        <ul class="opacity-80 space-y-1">
          <li><a href="products.php">Shop</a></li>
          <li><a href="services.php">Services</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div>
        <h5 class="font-semibold mb-2">Contact</h5>
        <p class="opacity-80 text-sm">support@pawverse.com<br/>+880 1XXX-XXXXXX</p>
      </div>
    </div>
    <div class="text-center opacity-60 text-sm py-6">© <?php echo date('Y'); ?> PawVerse. All rights reserved.</div>
  </footer>

  <!-- MODAL -->
  <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 modal-backdrop" id="modalBackdrop"></div>
    <div class="relative max-w-2xl w-full mx-4">
      <div class="bg-[color:var(--panel)] rounded-2xl p-6 text-[color:var(--text)] shadow-2xl">
        <header class="flex items-center justify-between mb-3">
          <h4 class="text-lg font-semibold">Book Appointment</h4>
          <button id="modalClose" aria-label="Close" class="p-2 rounded-lg hover:bg-[color:var(--soft)]">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </header>

        <form id="bookingForm" class="space-y-3">
          <input type="hidden" id="selectedVetId" name="vet_id" />
          <div>
            <label class="text-sm font-medium">Veterinarian</label>
            <div id="selectedVetName" class="text-sm opacity-80">—</div>
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

          <textarea id="b_message" name="message" rows="3" placeholder="Any notes (symptoms, preferences)" class="w-full px-3 py-2 border rounded-lg"></textarea>

          <div class="flex items-center justify-between mt-2">
            <div class="text-sm opacity-60">We’ll email confirmation. Demo only — no payment.</div>
            <div class="flex gap-3">
              <button type="button" id="modalCancel" class="px-4 py-2 rounded-lg border">Cancel</button>
              <button type="submit" class="px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white">Confirm</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="assets/js/theme.js"></script>
  <script>
    // Mobile menu toggle
    document.getElementById('mobileToggle').addEventListener('click',()=>document.getElementById('mobileMenu').classList.toggle('hidden'));

    // Hero parallax
    const heroParallax=document.getElementById('heroParallax');
    window.addEventListener('scroll',()=>{const s=window.scrollY;if(heroParallax)heroParallax.style.transform=`translateY(${Math.min(s*0.06,36)}px)`});

    // Fade-in observer
    const obs=new IntersectionObserver((es)=>{es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('in-view');});},{threshold:0.12});
    document.querySelectorAll('.fade-up').forEach((el,i)=>{el.style.transitionDelay=`${i*40}ms`;obs.observe(el);});

    // Modal logic
    const modal=document.getElementById('modal');
    const backdrop=document.getElementById('modalBackdrop');
    const closeBtn=document.getElementById('modalClose');
    const cancelBtn=document.getElementById('modalCancel');
    const form=document.getElementById('bookingForm');
    const vetNameEl=document.getElementById('selectedVetName');
    const vetIdEl=document.getElementById('selectedVetId');
    function openModal(vetId,vetName){vetNameEl.textContent=vetName||'—';vetIdEl.value=vetId||'';modal.classList.remove('hidden');document.body.style.overflow='hidden';setTimeout(()=>document.getElementById('b_name').focus(),200);}
    function closeModal(){modal.classList.add('hidden');document.body.style.overflow='';form.reset();}
    document.querySelectorAll('.book-now-btn').forEach(b=>b.addEventListener('click',()=>openModal(b.dataset.vet,b.dataset.vetName)));
    document.getElementById('openModalCTA').addEventListener('click',()=>openModal('',''));
    document.getElementById('openModalCTA2').addEventListener('click',()=>openModal('',''));
    closeBtn.addEventListener('click',closeModal);
    cancelBtn.addEventListener('click',closeModal);
    backdrop.addEventListener('click',closeModal);
    document.addEventListener('keydown',(e)=>{if(e.key==='Escape'&&!modal.classList.contains('hidden'))closeModal();});

    // Submit (demo)
    form.addEventListener('submit',(e)=>{
      e.preventDefault();
      const n=b_name.value.trim(),em=b_email.value.trim(),d=b_date.value,t=b_time.value;
      if(!n||!em||!d||!t){alert('Please complete required fields.');return;}
      closeModal();
      const nEl=document.createElement('div');
      nEl.className='fixed right-6 bottom-6 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg';
      nEl.textContent='Booking confirmed (demo). Check your email.';
      document.body.appendChild(nEl);
      setTimeout(()=>nEl.remove(),4000);
    });
  </script>
</body>
</html>
