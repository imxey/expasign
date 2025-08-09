import React, { useEffect, useState } from 'react';
import Nav from '../../components/navbar'; 
import CompetitionSelector from '../../components/compeSelector'; 


interface Member {
    id: number; 
    name: string;
    nim: string;
    email: string;
    phone: string;
    school: string;
    igLink: string;
    followExpa: File | null;
    followEdu: File | null;
    followMp: File | null;
    repostSg: File | null;
}


interface TeamData {
    team_name: string;
    category: string;
    payment_method: string;
    isEdu: boolean;
    receipt: File | null;
}


const translateErrorMessage = (message: string, field: string): string => {
    
    const fieldNames: { [key: string]: string } = {
        team_name: 'Nama Tim',
        receipt: 'Bukti pembayaran',
        name: 'Nama Lengkap',
        nim: 'NIM',
        email: 'Email',
        phone: 'No. Telepon',
        school: 'Asal Sekolah/Universitas',
        igLink: 'Link Profil Instagram',
        followExpa: 'Bukti follow @expasign',
        followEdu: 'Bukti follow @edutime',
        followMp: 'Bukti follow @marsproject',
        repostSg: 'Bukti repost story',
    };

    
    const readableField = Object.keys(fieldNames).find(key => field.includes(key));
    let finalFieldName = readableField ? fieldNames[readableField] : 'Isian';

    
    if (field.startsWith('members.')) {
        const memberIndex = parseInt(field.split('.')[1]) + 1;
        finalFieldName = `${finalFieldName} Anggota ${memberIndex}`;
    }

    
    if (message.includes('is required')) return `${finalFieldName} harus diisi.`;
    if (message.includes('has already been taken')) return `${finalFieldName} sudah terdaftar.`;
    if (message.includes('must be a valid URL')) return `${finalFieldName} harus berupa link yang valid.`;
    if (message.includes('must be a string')) return `${finalFieldName} harus berupa teks.`;
    if (message.includes('must be a number')) return `${finalFieldName} harus berupa angka.`;
    if (message.includes('must be an email')) return `${finalFieldName} harus berupa email yang valid.`;
    if (message.includes('must be a file')) return `${finalFieldName} harus berupa file.`;
    if (message.includes('failed to upload')) return `Gagal mengupload ${finalFieldName}.`;

    
    return message;
};


export default function TeamRegistrationForm() {
    
    const [teamData, setTeamData] = useState<TeamData>({
        team_name: '',
        category: '',
        payment_method: 'auto',
        isEdu: false,
        receipt: null,
    });

    
    const [members, setMembers] = useState<Member[]>([
        {
            id: 1, name: '', nim: '', email: '', phone: '', school: '', igLink: '',
            followExpa: null, followEdu: null, followMp: null, repostSg: null,
        }
    ]);
    
    const [selectedCompe, setSelectedCompe] = useState<string>('');
    const [errors, setErrors] = useState<any>({});
    const [successMessage, setSuccessMessage] = useState<string>('');
    const [isLoading, setIsLoading] = useState<boolean>(false);

    
    useEffect(() => {
        if (selectedCompe) {
            setTeamData(prev => ({ ...prev, category: selectedCompe }));
        }
    }, [selectedCompe]);

    
    const handleTeamChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value, type } = e.target;
        const checked = (e.target as HTMLInputElement).checked;
        const files = (e.target as HTMLInputElement).files;

        setTeamData(prev => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : type === 'file' ? (files ? files[0] : null) : value,
        }));
    };

    
    const handleMemberChange = (index: number, e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value, type } = e.target;
        const files = e.target.files;
        
        const updatedMembers = [...members];
        const memberToUpdate = { ...updatedMembers[index] };

        if (type === 'file' && files) {
            (memberToUpdate as any)[name] = files[0];
        } else {
            (memberToUpdate as any)[name] = value;
        }
        
        updatedMembers[index] = memberToUpdate;
        setMembers(updatedMembers);
    };

    
    const addMember = () => {
        if (members.length < 3) {
            setMembers(prev => [
                ...prev,
                {
                    id: prev.length + 1, name: '', nim: '', email: '', phone: '', school: '', igLink: '',
                    followExpa: null, followEdu: null, followMp: null, repostSg: null,
                }
            ]);
        }
    };

    
    const removeMember = (id: number) => {
        setMembers(prev => prev.filter(member => member.id !== id));
    };

    
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsLoading(true);
        setErrors({});
        setSuccessMessage('');

        const data = new FormData();

        
        data.append('team_name', teamData.team_name);
        data.append('category', teamData.category);
        data.append('payment_method', teamData.payment_method);
        data.append('isEdu', teamData.isEdu ? '1' : '0');
        if (teamData.payment_method === 'transfer' && teamData.receipt) {
            data.append('receipt', teamData.receipt);
        }

        
        members.forEach((member, index) => {
            Object.entries(member).forEach(([key, value]) => {
                if (key !== 'id' && value !== null) {
                    data.append(`members[${index}][${key}]`, value as string | Blob);
                }
            });
        });

        try {
            const response = await fetch('/api/team-regist/handle', { 
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: data,
            });

            const result = await response.json();

            console.log('Server Response:', result);

            if (!response.ok) {
                if (result.message && typeof result.message === 'object') {
                    setErrors(result.message);
                } else {
                    setErrors({ general: result.message || 'Terjadi kesalahan.' });
                }
            } else {
                setSuccessMessage(result.success);
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1500);
                }
            }

        } catch (error) {
            console.error('Submit error:', error);
            setErrors({ general: 'Gagal terhubung ke server. Coba lagi nanti ya.' });
        } finally {
            setIsLoading(false);
        }
    };
    
    
    const getError = (field: string) => {
        if (errors && errors[field] && Array.isArray(errors[field])) {
            
            const translatedMessage = translateErrorMessage(errors[field][0], field);
            return <p className="mt-1 text-xs text-red-400">{translatedMessage}</p>;
        }
        return null;
    };


    return (
        <>
            <Nav />
            <div className="flex min-h-screen items-center justify-center bg-gray-900 py-12 text-white" style={{ fontFamily: "'Orbitron', monospace" }}>
                <div className="relative z-10 w-11/12 max-w-4xl rounded-lg border border-gray-700 bg-gray-800 p-8 shadow-2xl md:p-12">
                    <h2 className="mb-8 text-center text-3xl font-bold text-blue-400 md:text-4xl">Pendaftaran Tim Expasign 2025</h2>

                    {successMessage && <div className="mb-6 rounded-lg bg-green-500 px-4 py-3 text-center text-white">{successMessage}</div>}
                    {errors.general && <div className="mb-6 rounded-lg bg-red-500 px-4 py-3 text-center text-white">{errors.general}</div>}

                    {!selectedCompe ? (
                        <CompetitionSelector selectedCompe={selectedCompe} setSelectedCompe={setSelectedCompe} />
                    ) : (
                        <form onSubmit={handleSubmit} noValidate>
                            {/* --- DATA TIM --- */}
                            <div className="mb-8 rounded-lg border border-blue-400/50 p-6">
                                <h3 className="mb-4 text-xl font-bold text-blue-300">Data Tim</h3>
                                <div>
                                    <label htmlFor="team_name" className="mb-2 block text-sm font-bold text-gray-300">Nama Tim</label>
                                    <input type="text" name="team_name" id="team_name" value={teamData.team_name} onChange={handleTeamChange} required className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500" />
                                    {getError('team_name')}
                                </div>
                            </div>
                            
                            {/* --- DATA ANGGOTA --- */}
                            {members.map((member, index) => (
                                <div key={member.id} className="relative mb-6 rounded-lg border border-purple-400/50 p-6">
                                    <h3 className="mb-4 text-xl font-bold text-purple-300">
                                        Anggota {index + 1} {index === 0 ? '(Ketua Tim)' : ''}
                                    </h3>
                                    {index > 0 && (
                                        <button type="button" onClick={() => removeMember(member.id)} className="absolute top-4 right-4 text-red-400 hover:text-red-300">&times; Hapus</button>
                                    )}
                                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        {/* Name, NIM, Email, Phone, School, IG Link */}
                                        <div>
                                            <input type="text" name="name" placeholder="Nama Lengkap" value={member.name} onChange={e => handleMemberChange(index, e)} className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm" />
                                            {getError(`members.${index}.name`)}
                                        </div>
                                        <div>
                                            <input type="text" name="nim" placeholder="NIM" value={member.nim} onChange={e => handleMemberChange(index, e)} className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm" />
                                            {getError(`members.${index}.nim`)}
                                        </div>
                                        <div>
                                            <input type="email" name="email" placeholder="Email" value={member.email} onChange={e => handleMemberChange(index, e)} className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm" />
                                            {getError(`members.${index}.email`)}
                                        </div>
                                        <div>
                                            <input type="tel" name="phone" placeholder="No. Telepon (WA)" value={member.phone} onChange={e => handleMemberChange(index, e)} className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm" />
                                            {getError(`members.${index}.phone`)}
                                        </div>
                                        <div>
                                            <input type="text" name="school" placeholder="Asal Sekolah/Universitas" value={member.school} onChange={e => handleMemberChange(index, e)} className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm" />
                                            {getError(`members.${index}.school`)}
                                        </div>
                                        <div>
                                            <input type="url" name="igLink" placeholder="Link Profil Instagram" value={member.igLink} onChange={e => handleMemberChange(index, e)} className="w-full rounded-md border border-gray-600 bg-gray-700 px-4 py-2 text-sm" />
                                            {getError(`members.${index}.igLink`)}
                                        </div>
                                    </div>
                                    <div className="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                                        {/* File Uploads */}
                                        <div>
                                            <label className="text-xs">SS Follow @expasign <input type="file" name="followExpa" onChange={e => handleMemberChange(index, e)} className="mt-1 block w-full text-xs" /></label>
                                            {getError(`members.${index}.followExpa`)}
                                        </div>
                                        <div>
                                            <label className="text-xs">SS Follow @edutime <input type="file" name="followEdu" onChange={e => handleMemberChange(index, e)} className="mt-1 block w-full text-xs" /></label>
                                            {getError(`members.${index}.followEdu`)}
                                        </div>
                                        <div>
                                            <label className="text-xs">SS Follow @marsproject <input type="file" name="followMp" onChange={e => handleMemberChange(index, e)} className="mt-1 block w-full text-xs" /></label>
                                            {getError(`members.${index}.followMp`)}
                                        </div>
                                        <div>
                                            <label className="text-xs">SS Repost Story <input type="file" name="repostSg" onChange={e => handleMemberChange(index, e)} className="mt-1 block w-full text-xs" /></label>
                                            {getError(`members.${index}.repostSg`)}
                                        </div>
                                    </div>
                                </div>
                            ))}

                            {members.length < 3 && (
                                <button type="button" onClick={addMember} className="mb-6 w-full rounded-lg border-2 border-dashed border-gray-600 py-3 text-sm text-gray-400 hover:border-gray-500 hover:text-gray-300">
                                    + Tambah Anggota
                                </button>
                            )}

                            {/* --- PEMBAYARAN & SUBMIT --- */}
                            <div className="mt-6">
                                {/* Payment Method, Receipt Upload, isEdu Checkbox */}
                                <p className="mb-3 block text-sm font-bold text-gray-300">METODE PEMBAYARAN</p>
                                {/* ... (radio button auto & transfer, sama seperti kodemu sebelumnya) ... */}
                                <div className="flex items-center gap-2">
                                    <input type="radio" name="payment_method" id="auto" value="auto" checked={teamData.payment_method === 'auto'} onChange={handleTeamChange} />
                                    <label htmlFor="auto">Auto Payment</label>
                                </div>
                                <div className="flex items-center gap-2">
                                    <input type="radio" name="payment_method" id="transfer" value="transfer" checked={teamData.payment_method === 'transfer'} onChange={handleTeamChange} />
                                    <label htmlFor="transfer">Transfer Bank</label>
                                </div>
                                
                                {teamData.payment_method === 'transfer' && (
                                    <div className="mt-4">
                                        <label htmlFor="receipt" className="mb-2 block text-sm font-bold">Upload Bukti Pembayaran Tim</label>
                                        <input type="file" name="receipt" id="receipt" onChange={handleTeamChange} className="w-full text-sm" />
                                        {getError('receipt')}
                                    </div>
                                )}
                            </div>
                            
                            <div className="mt-6 mb-6 flex gap-2 text-sm">
                                <input type="checkbox" name="isEdu" id="isEdu" checked={teamData.isEdu} onChange={handleTeamChange} className="h-5 w-5 rounded p-2" />
                                <label htmlFor="isEdu" className="font-bold">Bersedia hadir pada edutime tanggal 32 Agustus 2030?</label>
                            </div>

                            <button type="submit" disabled={isLoading} className="w-full transform rounded-full bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-3 text-lg font-bold text-white shadow-lg transition-all duration-300 hover:scale-105">
                                {isLoading ? 'Mengirim Data Tim...' : 'Daftarkan Tim'}
                            </button>
                        </form>
                    )}
                </div>
            </div>
        </>
    );
}
