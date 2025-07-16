import { useState, useEffect } from 'react';
import Nav from '../../components/navbar';

export default function Regist() {
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
        email: '',
        phone: '',
        school: '',
        nim: '',
        category: '',
        payment_method: '',
        receipt: null,
        isEdu: false,
    });

    const [errors, setErrors] = useState([]);
    const [successMessage, setSuccessMessage] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const handleInputChange = (e) => {
        const input = e.target;
        const name = input.name;

        let value;

        if (input.type === 'checkbox') {
            value = input.checked;
        } else if (input.type === 'file') {
            value = input.files[0];
        } else {
            value = input.value;
        }

        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrors([]);
        setSuccessMessage('');
        setIsLoading(true);

        const newErrors = [];
        if (!formData.name) newErrors.push('Nama lengkap harus diisi');
        if (!formData.email) newErrors.push('Email harus diisi');
        if (!formData.phone) newErrors.push('Nomor telepon harus diisi');
        if (!formData.school) newErrors.push('Asal sekolah harus diisi');
        if (!formData.nim) newErrors.push('NIM harus diisi');
        if (!formData.category) newErrors.push('Kategori lomba harus dipilih');
        if (!formData.payment_method) newErrors.push('Metode pembayaran harus dipilih');
        if (formData.payment_method === 'transfer' && !formData.receipt) {
            newErrors.push('Bukti pembayaran harus diupload untuk transfer bank');
        }
        if (newErrors.length > 0) {
            setErrors(newErrors);
            setIsLoading(false);
            return;
        }
        const formDataToSend = new FormData();
        for (const key in formData) {
            let value = formData[key];
            if (key === 'isEdu') value = value ? '1' : '0';
            if (key === 'receipt' && formData[key] === null) continue;

            formDataToSend.append(key, value);
        }

        for (const pair of formDataToSend.entries()) {
            console.log(`${pair[0]}:`, pair[1]);
        }
        try {
            const response = await fetch('/api/regist/handle', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                method: 'POST',
                body: formDataToSend,
            });
            if (!response.ok) {
                const errorData = await response.json();
                setErrors(errorData.errors || ['Terjadi kesalahan saat mengirim data. Silakan coba lagi.']);
                console.error('Error response:', errorData);
                setIsLoading(false);
                return;
            }
            const data = await response.json();
            console.log('Response data:', data);
            setSuccessMessage(data.success || 'Pendaftaran berhasil!');
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } catch (error) {
            setErrors(['Terjadi kesalahan saat mengirim data. Silakan coba lagi.', error.message]);
            console.error('Error:', error);
        }
        setIsLoading(false);
    };

    return (
        <>
        <Nav/>
            <div
                className="flex min-h-screen items-center justify-center bg-gray-900 py-12 text-white"
                style={{ fontFamily: "'Orbitron', monospace", fontWeight: 400 }}
            >
                <div className="relative z-10 w-11/12 max-w-2xl rounded-lg border border-gray-700 bg-gray-800 p-8 shadow-2xl md:p-12">
                    <h2 className="mb-8 text-center text-3xl font-bold text-blue-400 md:text-4xl">Pendaftaran Expasign x Edutime 2025</h2>

                    {/* Success message */}
                    {successMessage && <div className="mb-6 rounded-lg bg-green-500 px-4 py-3 text-center text-white">{successMessage}</div>}

                    {/* Error messages */}
                    {errors.length > 0 && (
                        <div className="mb-6 rounded-lg bg-red-500 px-4 py-3 text-white">
                            <ul className="list-disc pl-5">
                                {errors.map((error, index) => (
                                    <li key={index}>{error}</li>
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
                                onChange={handleInputChange}
                                required
                                className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
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
                                onChange={handleInputChange}
                                required
                                className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
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
                                onChange={handleInputChange}
                                required
                                className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            />
                        </div>
                        <div>
                            <label htmlFor="school" className="mb-2 block text-sm font-bold text-gray-300">
                                Asal Sekolah/Universitas
                            </label>
                            <input
                                type="text"
                                name="school"
                                id="school"
                                placeholder="Input Asal Sekolah/Universitas"
                                value={formData.school}
                                onChange={handleInputChange}
                                required
                                className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <label htmlFor="nim" className="mb-2 block text-sm font-bold text-gray-300">
                            NIM (Nomor Induk Mahasiswa)
                        </label>
                        <input
                            type="text"
                            name="nim"
                            id="nim"
                            placeholder="Input NIM Anda"
                            value={formData.nim}
                            onChange={handleInputChange}
                            required
                            className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        />
                    </div>

                    <div>
                        <label htmlFor="category" className="mb-2 block text-sm font-bold text-gray-300">
                            Kategori Lomba
                        </label>
                        <select
                            name="category"
                            id="category"
                            value={formData.category}
                            onChange={handleInputChange}
                            required
                            className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        >
                            <option value="">Pilih kategori lomba</option>
                            <option value="category1">LKTI</option>
                            <option value="category2">Esai</option>
                            <option value="category3">Desain Poster</option>
                        </select>
                    </div>

                    <div className='mb-5 mt-5'>
                        <p className="mb-3 block text-sm font-bold text-gray-300">METODE PEMBAYARAN</p>
                        <div className="flex flex-col gap-4 md:flex-row">
                            <div className="flex items-center gap-2">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    id="auto"
                                    value="auto"
                                    checked={formData.payment_method === 'auto'}
                                    onChange={handleInputChange}
                                    className="h-5 w-5 text-blue-500 focus:ring-blue-500"
                                />
                                <label htmlFor="auto" className="cursor-pointer text-sm text-gray-300">
                                    Auto Payment
                                </label>
                            </div>

                            <div className="flex items-center gap-2">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    id="transfer"
                                    value="transfer"
                                    checked={formData.payment_method === 'transfer'}
                                    onChange={handleInputChange}
                                    className="h-5 w-5 text-blue-500 focus:ring-blue-500"
                                />
                                <label htmlFor="transfer" className="cursor-pointer text-sm text-gray-300">
                                    Transfer Bank
                                </label>
                            </div>
                        </div>
                    </div>

                    {formData.payment_method === 'transfer' && (
                        <div>
                            <label htmlFor="receipt" className="mb-2 block text-sm font-bold text-gray-300">
                                Upload Bukti Pembayaran
                            </label>
                            <input
                                type="file"
                                name="receipt"
                                id="receipt"
                                onChange={handleInputChange}
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                                className="w-full rounded-md border border-gray-600 bg-gray-700 p-2 text-sm text-white file:mr-4 file:rounded-full file:border-0 file:bg-blue-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-600 focus:border-blue-500 focus:ring-blue-500"
                            />
                            <p className="mt-1 text-xs text-gray-400">Ukuran maksimal file: 2MB. Format: JPG, PNG, PDF.</p>
                        </div>
                    )}

                    <div className="flex gap-2 text-sm">
                        <input
                            type="checkbox"
                            name="isEdu"
                            id="isEdu"
                            checked={formData.isEdu}
                            onChange={handleInputChange}
                            className="h-5 w-5 rounded p-2 text-blue-500 focus:ring-blue-500"
                        />
                        <label htmlFor="isEdu" className="text-sm font-bold text-gray-300">
                            Bersedia hadir pada edutime tanggal 32 Agustus 2020?
                        </label>
                    </div>

                    <button
                        type="submit"
                        onClick={handleSubmit}
                        className="mt-4 transform rounded-full bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-3 text-lg font-bold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:from-blue-600 hover:to-purple-700 hover:shadow-xl"
                    >
                        {isLoading ? 'Mengirim...' : 'Daftar Sekarang'}
                    </button>
                </div>
            </div>
        </>
    );
}
