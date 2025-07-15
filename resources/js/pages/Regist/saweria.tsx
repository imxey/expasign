export default function Saweria({ id, name, nominal }) {
    return (
        <>
            <body className="flex min-h-screen items-center justify-center bg-purple-50 px-4">
                <div className="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-xl">
                    <h1 className="text-2xl font-bold text-purple-700">Hai, {name}! 💖</h1>

                    <p className="mt-4 text-gray-700">
                        Untuk menyelesaikan pendaftaranmu, silakan lakukan pembayaran melalui Saweria dengan klik tombol di bawah ini:
                    </p>

                    <p className="mt-4 text-lg font-semibold text-gray-800">Nominal yang harus dibayar:</p>

                    <div className="mt-2 text-3xl font-bold tracking-wide text-pink-600">Rp {nominal.toLocaleString('id-ID')}</div>

                    <div className="mt-3 rounded-md border border-yellow-400 bg-yellow-100 px-4 py-2 text-sm font-medium text-yellow-800">
                        ⚠️ <strong>Perhatian:</strong> <br></br>
                        <span className="font-bold text-red-600 uppercase">
                            3 angka terakhir <u>WAJIB SAMA</u> persis!
                        </span>
                        <br></br>
                        Sistem hanya memverifikasi pembayaran jika nominal <u>sesuai 100%</u>!
                    </div>

                    <a
                        href="https://saweria.co/Xeyla"
                        target="_blank"
                        className="mt-6 inline-block rounded-full bg-purple-700 px-6 py-3 font-semibold text-white transition-all duration-200 hover:bg-purple-800"
                    >
                        🔗 Bayar Sekarang via Saweria
                    </a>

                    <p className="mt-4 text-xs text-gray-500">
                        Setelah kamu bayar, sistem akan otomatis mencocokkan dan memverifikasi transaksimu 💫
                    </p>
                </div>
            </body>
        </>
    );
}