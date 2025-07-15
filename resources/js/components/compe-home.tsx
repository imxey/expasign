import React from "react";
type Props = {
    name: string,
    detail:string
}
export default function CompeHome (props:Props){
    return (
        <div className="transform rounded-xl border border-gray-700 bg-gradient-to-br from-gray-800 to-gray-900 p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div className="mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-gradient-to-br from-cyan-400 to-blue-500">
                <svg className="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
            </div>
            <h3 className="mb-4 text-2xl font-bold text-cyan-400">{props.name}</h3>
            <p className="leading-relaxed text-gray-300">
                {props.detail}
            </p>
        </div>
    );
}