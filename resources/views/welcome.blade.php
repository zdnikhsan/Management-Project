<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ManageProjek - Simplify Your Workflow</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-light: #f8fafc;
            --bg-dark: #0f172a;
            --text-light: #1e293b;
            --text-dark: #f1f5f9;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-dark: rgba(15, 23, 42, 0.7);
            --border: rgba(226, 232, 240, 0.8);
            --border-dark: rgba(51, 65, 85, 0.8);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-light);
            line-height: 1.6;
            overflow-x: hidden;
            transition: background-color 0.3s ease;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background-color: var(--bg-dark);
                color: var(--text-dark);
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            backdrop-filter: blur(10px);
            background: var(--glass);
            border-bottom: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        @media (prefers-color-scheme: dark) {
            nav {
                background: var(--glass-dark);
                border-bottom: 1px solid var(--border-dark);
            }
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo span {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: inherit;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary);
            color: white !important;
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.39);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary) !important;
        }

        .btn-outline:hover {
            background: rgba(79, 70, 229, 0.1);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 100px;
            position: relative;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 4rem;
            align-items: center;
        }

        @media (max-width: 968px) {
            .hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .hero-content {
                order: 1;
            }
            .hero-image {
                order: 2;
                margin-top: 2rem;
            }
        }

        .hero-content h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            font-weight: 700;
            animation: fadeInUp 0.8s ease-out;
        }

        @media (max-width: 640px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
        }

        .hero-content p {
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 2.5rem;
            max-width: 500px;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        @media (prefers-color-scheme: dark) {
            .hero-content p {
                color: #94a3b8;
            }
        }

        .hero-btns {
            display: flex;
            gap: 1rem;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        @media (max-width: 968px) {
            .hero-btns {
                justify-content: center;
            }
        }

        .hero-image {
            position: relative;
            animation: fadeInRight 1s ease-out;
        }

        .hero-image img {
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            transition: transform 0.5s ease;
        }

        .hero-image:hover img {
            transform: scale(1.02);
        }

        /* Floating elements effect */
        .blob {
            position: absolute;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(168, 85, 247, 0.2));
            filter: blur(60px);
            border-radius: 50%;
            z-index: -1;
        }

        .blob-1 { top: 10%; left: -5%; }
        .blob-2 { bottom: 10%; right: -5%; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Footer */
        footer {
            padding: 4rem 0;
            text-align: center;
            border-top: 1px solid var(--border);
            margin-top: 4rem;
            font-size: 0.9rem;
            color: #64748b;
        }

        @media (prefers-color-scheme: dark) {
            footer {
                border-top: 1px solid var(--border-dark);
            }
        }

    </style>
</head>
<body>
    <nav>
        <div class="container" style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
            <a href="/" class="logo">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="32" height="32" rx="8" fill="#4F46E5"/>
                    <path d="M10 16L14 20L22 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>ManageProjek</span>
            </a>

            @if (Route::has('login'))
                <div class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Sign up</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <h1>Elevate Your <br><span>Project Strategy</span></h1>
                    <p>The all-in-one platform to streamline your project workflows, manage team tasks, and achieve your goals faster than ever before.</p>
                    
                    <div class="hero-btns">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">Get Started — It's Free</a>
                            <a href="#features" class="btn btn-outline">Learn More</a>
                        @endauth
                    </div>
                </div>
                <div class="hero-image">
                    <img src="{{ asset('images/hero.png') }}" alt="Project Management Dashboard Illustration">
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} ManageProjek. Built with passion for productive teams.</p>
            <p style="margin-top: 0.5rem; font-size: 0.8rem; opacity: 0.7;">v{{ app()->version() }}</p>
        </div>
    </footer>
</body>
</html>
