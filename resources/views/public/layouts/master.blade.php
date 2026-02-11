<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $settings->aciklama ?? 'Vavilla Çeşme - Lüks Villa Kiralama' }}">
    <meta name="keywords" content="{{ $settings->anahtar_kelime ?? 'villa, çeşme, kiralık villa' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Çeşme Otel')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Flatpickr Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Swiper Slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    
    <!-- Lightbox2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #c5a47e; /* Gold/Beige */
            --secondary-color: #1a1a1a;
            --dark-bg: #111111;
            --footer-bg: #0a0a0a;
            --text-dark: #cccdc6; 
            --text-light: #999999;
            --white: #ffffff;
            --gold-accent: #c5a47e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background-color: var(--dark-bg);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            color: var(--white);
        }
        
        /* ===== HEADER ===== */
        .main-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 25px 0;
            transition: all 0.3s ease;
            background: rgba(0,0,0,0.2); /* Slight tint for visibility */
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .main-header.scrolled {
            position: fixed;
            background-color: rgba(17, 17, 17, 0.98);
            padding: 15px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            border: none;
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }
        
        .logo a {
            font-family: 'Cormorant Garamond', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--white);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .main-nav {
            display: flex;
            align-items: center;
            gap: 40px;
            list-style: none;
            margin: 0; 
            padding: 0;
        }

        .main-nav li {
            position: relative;
        }

        .main-nav a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.3s ease;
            padding: 5px 0;
            display: block;
        }
        
        .main-nav a:hover,
        .main-nav a.active {
            color: var(--gold-accent);
        }

        /* Header Button */
        .header-cta .btn-reserve {
            background-color: var(--gold-accent);
            color: #000;
            padding: 12px 30px;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            border-radius: 0; /* Sharp edges as per design */
            transition: all 0.3s ease;
            display: inline-block;
        }

        /* Global Button Styles & Shimmer Effect */
        .btn-gold, .btn-submit, .btn-reserve {
            border: 1px solid var(--gold-accent); /* Ensure border for glow consistency */
        }

        .btn-outline-gold {
            border: 1px solid var(--gold-accent);
            color: var(--gold-accent);
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-gold:hover {
            background: var(--gold-accent);
            color: #000;
        }

        /* Subtle Shimmer Animation for all Gold Buttons on Hover */
        .btn-reserve:hover,
        .btn-gold:hover,
        .btn-outline-gold:hover,
        .btn-submit:hover {
            animation: buttonGlow 1.5s infinite ease-in-out;
            border-color: var(--gold-accent) !important;
            transform: translateY(-2px); /* Slight lift */
        }

        @keyframes buttonGlow {
            0% { box-shadow: 0 0 5px rgba(197, 164, 126, 0.4); }
            50% { box-shadow: 0 0 15px rgba(197, 164, 126, 0.8), 0 0 5px rgba(255, 215, 0, 0.4); }
            100% { box-shadow: 0 0 5px rgba(197, 164, 126, 0.4); }
        }
        
        .header-cta .btn-reserve:hover {
            background-color: #fff;
            color: #000;
        }
        
        .mobile-toggle {
            display: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        /* Common Classes */
        .section-subtitle {
            font-family: 'Cormorant Garamond', serif;
            color: var(--gold-accent);
            font-size: 20px;
            font-style: italic;
            display: block;
            margin-bottom: 10px;
        }
        .section-title h2 {
            font-size: 48px;
            font-weight: 400;
            margin-bottom: 30px;
        }
        
        /* Buttons */
        .btn-gold {
            background-color: var(--gold-accent);
            color: #000;
            padding: 15px 40px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: .3s;
        }
        .btn-gold:hover { background: #fff; color: #000; }

        .btn-outline-gold {
            border: 1px solid var(--gold-accent);
            color: var(--gold-accent);
            padding: 12px 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: .3s;
        }
        .btn-outline-gold:hover { background: var(--gold-accent); color: #000; }

        /* ===== FOOTER ===== */
        .main-footer {
            background: #0b0b0b;
            padding: 80px 0 30px;
            border-top: 1px solid rgba(255,255,255,0.05);
            font-size: 14px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 60px;
        }

        .footer-col {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-col h4 {
            font-size: 18px;
            margin-bottom: 25px;
            color: #fff;
            font-family: 'Cormorant Garamond', serif;
        }

        /* Footer Links List */
        .footer-col ul { list-style: none; padding: 0; }
        .footer-col ul li { margin-bottom: 12px; }
        .footer-col ul li a {
            color: #888;
            text-decoration: none;
            transition: .3s;
        }
        .footer-col ul li a:hover { color: var(--gold-accent); padding-left: 5px; }

        /* Social Icons */
        .social-links a {
            display: inline-flex;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            margin-right: 10px;
            text-decoration: none;
            transition: .3s;
        }
        .social-links a:hover {
            background: var(--gold-accent);
            border-color: var(--gold-accent);
            color: #000;
        }
        
        /* Newsletter Form */
        .footer-newsletter input {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 15px;
            width: 100%;
            margin-bottom: 10px;
        }
        .footer-newsletter input:focus {
            outline: none;
            border-color: var(--gold-accent);
        }
        .footer-newsletter button {
            width: 100%;
            padding: 15px;
            background: var(--gold-accent);
            color: #000;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: .3s;
        }
        .footer-newsletter button:hover { background: #fff; }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #666;
            font-size: 13px;
        }

        @media (max-width: 991px) {
            .main-nav { display: none; } /* Add mobile menu logic later if needed */
            .mobile-toggle { display: block; }
            .header-cta { display: none; }
        }

        @stack('styles')
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header" id="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="{{ url('/') }}">Vavilla Çeşme</a>
            </div>
            
            <nav>
                <ul class="main-nav" id="main-nav">
                    <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Anasayfa</a></li>
                    <li><a href="{{ route('villas.index') }}" class="{{ request()->is('villalar*') ? 'active' : '' }}">Villalar & Süitler</a></li>
                    <li><a href="{{ url('/hakkimizda') }}" class="{{ request()->is('hakkimizda') ? 'active' : '' }}">Hakkımızda</a></li>
                    <li><a href="{{ url('/galeri') }}" class="{{ request()->is('galeri') ? 'active' : '' }}">Galeri</a></li>
                    <li><a href="{{ url('/iletisim') }}" class="{{ request()->is('iletisim') ? 'active' : '' }}">İletişim</a></li>
                </ul>
            </nav>
            
            <div class="header-cta">
                <a href="{{ route('villas.index') }}" class="btn-reserve">REZERVASYON YAP</a>
            </div>
            
            <div class="mobile-toggle" id="mobile-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>
    
    @yield('content')
    
    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <!-- Brand & Text -->
                <div class="footer-col" style="flex: 2;">
                    <div class="logo mb-4">
                        <a href="{{ url('/') }}" style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #fff; text-decoration: none; text-transform: uppercase; letter-spacing: 2px;">Vavilla Çeşme</a>
                    </div>
                    <p style="color: #888; margin-bottom: 30px; line-height: 1.8;">
                        En özel anlarınız için tasarlanmış lüks villalarımızda, konforun ve huzurun tadını çıkarın. Çeşme'nin kalbinde unutulmaz bir tatil deneyimi sizi bekliyor.
                    </p>
                    <div class="social-links">
                        @if($settings->facebook ?? true)<a href="#"><i class="fab fa-facebook-f"></i></a>@endif
                        @if($settings->instagram ?? true)<a href="#"><i class="fab fa-instagram"></i></a>@endif
                        @if($settings->twitter ?? true)<a href="#"><i class="fab fa-twitter"></i></a>@endif
                        @if($settings->youtube ?? true)<a href="#"><i class="fab fa-youtube"></i></a>@endif
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>Hızlı Erişim</h4>
                    <ul>
                        <li><a href="{{ url('/') }}">Anasayfa</a></li>
                        <li><a href="{{ route('villas.index') }}">Villalar</a></li>
                        <li><a href="{{ url('/hakkimizda') }}">Hakkımızda</a></li>
                        <li><a href="{{ url('/galeri') }}">Galeri</a></li>
                        <li><a href="{{ url('/iletisim') }}">İletişim</a></li>
                    </ul>
                </div>
                
                <!-- Latest Villas -->
                <div class="footer-col">
                    <h4>Villalar</h4>
                    <ul>
                        @php
                            $footerVillas = \App\Models\Villa::where('durum', 1)->orderBy('created_at', 'desc')->take(5)->get();
                        @endphp
                        @forelse($footerVillas as $villa)
                        <li><a href="{{ route('villas.show', $villa->sef) }}">{{ $villa->baslik }}</a></li>
                        @empty
                        <li><a href="#">Özel Havuzlu Villa</a></li>
                        <li><a href="#">Deniz Manzaralı Süit</a></li>
                        @endforelse
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div class="footer-col" style="flex: 1.5;">
                    <h4>Bülten</h4>
                    <p style="color: #888; margin-bottom: 20px;">Fırsatlardan haberdar olmak için kayıt olun.</p>
                    <form action="{{ route('newsletter.store') }}" method="POST" class="footer-newsletter">
                        @csrf
                        <input type="email" name="email" placeholder="E-posta Adresiniz" required>
                        <button type="submit">ABONE OL</button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div>&copy; {{ date('Y') }} Vavilla Çeşme. Tüm hakları saklıdır.</div>
                <div>
                    <a href="#" style="color: #666; text-decoration: none; margin-left: 20px;">Gizlilik</a>
                    <a href="#" style="color: #666; text-decoration: none; margin-left: 20px;">Şartlar</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <!-- Flatpickr - Using unpkg CDN as alternative -->
    <link rel="stylesheet" href="https://unpkg.com/flatpickr/dist/flatpickr.min.css">
    <script src="https://unpkg.com/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://unpkg.com/flatpickr@4.6.13/dist/l10n/tr.js"></script>
    
    <script>
        // Note: Turkish locale for Flatpickr is configured inline in reservation page
        // to avoid conflicts with global initialization
    
        // Global AJAX Setup for CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('main-header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Mobile menu toggle
        document.getElementById('mobile-toggle').addEventListener('click', function() {
            document.getElementById('main-nav').classList.toggle('active');
        });
        
        // Initialize Flatpickr (Datepicker) - for generic .datepicker elements
        document.addEventListener('DOMContentLoaded', function() {
            // Only initialize elements with .datepicker class that aren't already initialized
            var datepickerElements = document.querySelectorAll('.datepicker');
            datepickerElements.forEach(function(el) {
                if (!el._flatpickr) {
                    flatpickr(el, {
                        dateFormat: "Y-m-d",
                        minDate: "today"
                    });
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
