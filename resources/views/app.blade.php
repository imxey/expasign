<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Expasign & Edutime dari Mars Project PNJ adalah program unggulan untuk meningkatkan kreativitas, inovasi, dan pengembangan diri mahasiswa melalui lomba dan seminar.">  
<meta name="keywords" content="Expasign, Edutime, Mars Project, PNJ, Politeknik Negeri Jakarta, Expasign pnj, LKTI, Esai, Poster, Seminar, Mahasiswa, Pendidikan, Inovasi, Lomba, Infografis Kreativitas">  
<meta name="author" content="Mars Project PNJ">  
        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>
        <link rel="icon" href="{{ asset('logo.jpg') }}" type="image/png">
        <title inertia>Expasign x Edutime</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

        @routes
        @viteReactRefresh
        @vite(['resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        @inertiaHead
    </head>
        @inertia
</html>
