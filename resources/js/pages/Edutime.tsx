import { useState, useEffect } from 'react';
import Nav from '../components/navbar';
export default function Edutime() {
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
    const [formData, setFormData] = useState({
        name: '',
        nim: 0,
        email: '',
        phone: '',
        address: '',
        school: '',
    });
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState([]);
    const [success, setSuccess] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        const newErrors = [];
        if (!formData.name) newErrors.push('Nama lengkap harus diisi!');
        if (!formData.email) newErrors.push('Email gak boleh kosong!');
        if (!formData.phone) newErrors.push('Nomor telepon harus diisi!');
        if (!formData.address) newErrors.push('Alamat harus diisi!');
        if (!formData.school) newErrors.push('Universitas harus diisi!');
        if (newErrors.length > 0) {
            setError(newErrors);
            return;
        }
        setIsLoading(true);
        setError([]);
        setSuccess(''); 

        const formDataSend = new FormData();
        formDataSend.append('name', formData.name);
        formDataSend.append('email', formData.email);
        formDataSend.append('nim', formData.nim);
        formDataSend.append('phone', formData.phone);
        formDataSend.append('address', formData.address);
        formDataSend.append('school', formData.school);
        try {
            const response = await fetch('/api/edutime/handle', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formDataSend,
            });
            if (!response.ok) {
                const errorData = await response.json();
                setError(errorData.errors || ['Terjadi kesalahan saat mengirim data.']);
            } else {
                const data = await response.json();
                console.log('Response Data:', data);
                if (data.status === 'success') {
                    setSuccess(data.message);
                } else {
                    setError(Array.isArray(data.message) ? data.message : [data.message || 'Terjadi kesalahan saat memproses pendaftaran.']);
                }
            }
        } catch (err) {
            console.error('Network error:', err);
            setError(['Terjadi kesalahan jaringan. Silakan coba lagi.']);
        }
        setIsLoading(false);
    };

    return (
        <>
            <Nav/>
            <div
                className="flex min-h-screen items-center justify-center bg-gray-900 py-12 text-white"
                style={{ fontFamily: 'Orbitron', fontWeight: 400 }}
            >
                <div className="relative z-10 w-11/12 max-w-2xl rounded-lg border border-gray-700 bg-gray-800 p-8 shadow-2xl md:p-12">
                    <h2 className="mb-8 text-center text-3xl font-bold text-blue-400 md:text-4xl">Pendaftaran Edutime 2025</h2>
                    {success && (
                        <div className="mb-6 rounded-lg bg-green-500 px-4 py-3 text-center text-white">
                            <p>{success}</p>
                        </div>
                    )}
                    {error.length > 0 && (
                        <div className="mb-6 rounded-lg bg-red-500 px-4 py-3 text-white">
                            <ul className="list-disc pl-5">
                                {error.map((err, index) => (
                                    <li key={index}>{err}</li>
                                ))}
                            </ul>
                        </div>
                    )}

                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label htmlFor="name" className="mb-2 block text-sm font-bold text-gray-300">
                                Nama Lengkap
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Input Nama Lengkap"
                                value={formData.name}
                                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                required
                                className="form-input w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label htmlFor="email" className="mb-2 block text-sm font-bold text-gray-300">
                                Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Input Email"
                                value={formData.email}
                                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                                required
                                className="form-input w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                    </div>
                    <div className="grid grid-cols-1 gap-6 ">
                        <div>
                            <label htmlFor="nim" className="mb-2 block text-sm font-bold text-gray-300">
                                NIM
                            </label>
                            <input
                                type="number"
                                name="nim"
                                id="nim"
                                placeholder="Input NIM"
                                value={formData.nim}
                                onChange={(e) => setFormData({ ...formData, nim: e.target.value })}
                                required
                                className="form-input w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label htmlFor="phone" className="mb-2 block text-sm font-bold text-gray-300">
                                Nomor Telepon
                            </label>
                            <input
                                type="tel"
                                name="phone"
                                id="phone"
                                placeholder="Input Nomor Telepon"
                                value={formData.phone}
                                onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                                required
                                className="form-input w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label htmlFor="address" className="mb-2 block text-sm font-bold text-gray-300">
                                Alamat
                            </label>
                            <input
                                type="text"
                                name="address"
                                id="address"
                                placeholder="Input Alamat"
                                value={formData.address}
                                onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                                required
                                className="form-input w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label htmlFor="university" className="mb-2 block text-sm font-bold text-gray-300">
                                Universitas
                            </label>
                            <input
                                type="text"
                                name="school"
                                id="school" 
                                placeholder="Input Universitas"
                                value={formData.school}
                                onChange={(e) => setFormData({ ...formData, school: e.target.value })}
                                required
                                className="form-input w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                    </div>
                    <button
                        type="button"
                        onClick={handleSubmit}
                        disabled={isLoading}
                        className="mt-4 transform rounded-full bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-3 text-lg font-bold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:from-blue-600 hover:to-purple-700 hover:shadow-xl"
                    >
                        {isLoading ? 'Submitting...' : 'Submit'}
                    </button>
                </div>

                <script></script>
            </div>
        </>
    );
}
