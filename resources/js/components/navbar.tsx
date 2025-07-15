import { Link } from '@inertiajs/react'
export default function Navbar() {
    return (
        <nav className="relative z-50 bg-gray-900 px-6 py-4 shadow-lg">
            <div className="mx-auto flex max-w-7xl items-center justify-between">
                <Link href="/" >
                    <h1 className="text-xl font-bold text-white">Expasign x Edutime</h1>
                </Link>

                <button id="burger" className="text-white focus:outline-none md:hidden">
                    <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div className="hidden items-center space-x-8 md:flex">
                    <Link href="#about" className="text-gray-300 transition-colors duration-300 hover:text-white">
                        About
                    </Link>
                    <Link href="#competitions" className="text-gray-300 transition-colors duration-300 hover:text-white">
                        Competitions
                    </Link>
                    <Link href="/edutime" className="text-gray-300 transition-colors duration-300 hover:text-white">
                        Edutime
                    </Link>
                    <Link href="/register">
                        <button
                            style={{ background: 'linear-gradient(90deg, #06B6D4 0%, #3B82F6 100%)' }}
                            className="transform rounded-full px-6 py-2 text-white transition-all duration-300 hover:scale-105 hover:cursor-pointer"
                        >
                            Register
                        </button>
                    </Link>
                </div>
            </div>

            <div id="nav-links" className="mt-4 flex hidden flex-col space-y-4 px-6 md:hidden">
                <Link href="#about" className="block text-gray-300 transition-colors duration-300 hover:text-white">
                    About
                </Link>
                <Link href="#competitions" className="block text-gray-300 transition-colors duration-300 hover:text-white">
                    Competitions
                </Link>
                <Link href="/edutime" className="block text-gray-300 transition-colors duration-300 hover:text-white">
                    Edutime
                </Link>
                <Link href="/register">
                    <button className="w-full transform rounded-full bg-cyan-500 px-6 py-2 text-white transition-all duration-300 hover:scale-105 hover:bg-cyan-600">
                        Register
                    </button>
                </Link>
            </div>
        </nav>
    );
}
