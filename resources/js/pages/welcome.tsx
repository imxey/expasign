import CompeHome from '@/components/compe-home';
import Navbar from '@/components/navbar';
import { Link } from '@inertiajs/react';
import { useEffect } from 'react';
export default function Welcome() {
    useEffect(() => {
        const anchorElements: NodeListOf<HTMLAnchorElement> = document.querySelectorAll('a[href^="#"]');
        const clickHandlers: { anchor: HTMLAnchorElement, handler: (e: Event) => void }[] = [];
        
        anchorElements.forEach(anchor => {
            const handler = function(e: Event) {
                e.preventDefault();
                const href = anchor.getAttribute('href');
                if (href) {
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            };
            
            anchor.addEventListener('click', handler);
            clickHandlers.push({ anchor, handler });
        });

        const handleScroll = () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.absolute.inset-0') as HTMLElement;
            if (parallax) {
                parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        };
        window.addEventListener('scroll', handleScroll);

        const burger = document.getElementById('burger');
        const navLinks = document.getElementById('nav-links');
        if (burger && navLinks) {
            const burgerClickHandler = () => {
                navLinks.classList.toggle('hidden');
            };
            burger.addEventListener('click', burgerClickHandler);
            
            return () => {
                clickHandlers.forEach(({ anchor, handler }) => {
                    anchor.removeEventListener('click', handler);
                });
                window.removeEventListener('scroll', handleScroll);
                burger.removeEventListener('click', burgerClickHandler);
            };
        }
        
        return () => {
            clickHandlers.forEach(({ anchor, handler }) => {
                anchor.removeEventListener('click', handler);
            });
            window.removeEventListener('scroll', handleScroll);
        };
    }, []);
    
    const compData = [
        {
            name: 'KTI',
            detail: 'lorem ipsum',
        },
        {
            name: 'Business Plan',
            detail: 'lorem ipsum',
        },
        {
            name: 'Infografis',
            detail: 'lorem ipsum'
        }
    ];
    return (
        <>
            <div className="bg-gray-900 text-white" style={{ fontFamily: 'Orbitron, monospace', fontWeight: 400 }}>
                <Navbar />
                <section className="relative flex min-h-screen items-center justify-center overflow-hidden">
                    <div
                        className="absolute inset-0"
                        style={{ background: 'linear-gradient(90deg, rgba(30, 58, 138, 0.20) 0%, rgba(0, 0, 0, 0.50) 100%)' }}
                    ></div>
                    <div className="relative z-10 mx-auto max-w-4xl px-6 text-center">
                        <h1 className="font-white mb-5 flex flex-col text-3xl md:text-6xl">
                            Expasign<span>x</span>
                            <span>Edutime</span> <span className="text-blue-400">2025</span>
                        </h1>

                        <p className="mx-auto mb-5 max-w-5xl text-xs leading-relaxed text-gray-300 md:text-xl">
                            Expasign dan Edutime adalah program unggulan dari UKM Mars Project PNJ yang bertujuan meningkatkan kreativitas, inovasi, dan
                            potensi mahasiswa. Expasign melibatkan lomba seperti LKTI, Esai, dan Desain Poster, sementara Edutime adalah seminar untuk
                            pengembangan keterampilan di bidang pendidikan dan bisnis. Tahun ini, kedua program ini digabungkan untuk mengoptimalkan
                            visi UKM Mars Project, menggabungkan aspek teoritis dan praktis, serta memberikan dampak yang lebih luas dalam
                            pengembangan diri, kreativitas, dan kolaborasi antar mahasiswa.
                        </p>
                        <Link href="/register">
                            <button className="mb-5 transform rounded-full bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-4 text-lg font-bold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:from-blue-600 hover:to-purple-700 hover:shadow-xl">
                                Register Now
                            </button>
                        </Link>
                    </div>

                    <div className="absolute bottom-8 left-1/2 -translate-x-1/2 transform animate-bounce">
                        <div className="flex h-10 w-6 justify-center rounded-full border-2 border-white">
                            <div className="mt-2 h-3 w-1 animate-pulse rounded-full bg-white"></div>
                        </div>
                    </div>
                </section>
                <section className="bg-gray-800 px-6 py-20">
                    <div className="mx-auto max-w-7xl">
                        <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
                            <CompeHome {...compData[0]} />
                            <CompeHome {...compData[1]} />
                            <CompeHome {...compData[2]} />
                        </div>
                    </div>
                </section>
                <footer className="bg-gray-900 px-6 py-12">
                    <div className="mx-auto max-w-7xl text-center">
                        <div className="mb-8">
                            <h3 className="mb-4 text-3xl font-bold text-white">Expasign x Edutime 2025</h3>
                            <p className="mx-auto max-w-2xl text-gray-400">
                                Join us in the ultimate competition experience. Register now and be part of something extraordinary.
                            </p>
                        </div>

                        <div className="mb-8 flex justify-center space-x-6">
                            <a href="#" className="text-gray-400 transition-colors duration-300 hover:text-cyan-400">
                                <svg
                                    className="h-6 w-6"
                                    fill="currentColor"
                                    width="800px"
                                    height="80<0px"
                                    viewBox="0 0 32 32"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path d="M20.445 5h-8.891A6.559 6.559 0 0 0 5 11.554v8.891A6.559 6.559 0 0 0 11.554 27h8.891a6.56 6.56 0 0 0 6.554-6.555v-8.891A6.557 6.557 0 0 0 20.445 5zm4.342 15.445a4.343 4.343 0 0 1-4.342 4.342h-8.891a4.341 4.341 0 0 1-4.341-4.342v-8.891a4.34 4.34 0 0 1 4.341-4.341h8.891a4.342 4.342 0 0 1 4.341 4.341l.001 8.891z" />
                                    <path d="M16 10.312c-3.138 0-5.688 2.551-5.688 5.688s2.551 5.688 5.688 5.688 5.688-2.551 5.688-5.688-2.55-5.688-5.688-5.688zm0 9.163a3.475 3.475 0 1 1-.001-6.95 3.475 3.475 0 0 1 .001 6.95zM21.7 8.991a1.363 1.363 0 1 1-1.364 1.364c0-.752.51-1.364 1.364-1.364z" />
                                </svg>
                            </a>
                            <a href="#" className="text-gray-400 transition-colors duration-300 hover:text-cyan-400">
                                <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                                </svg>
                            </a>
                            <a href="#" className="text-gray-400 transition-colors duration-300 hover:text-cyan-400">
                                <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.74.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z" />
                                </svg>
                            </a>
                        </div>

                        <div className="border-t border-gray-700 pt-8">
                            <p className="text-gray-400">© {new Date().getFullYear()} Expasign x Edutime. All rights reserved.</p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
