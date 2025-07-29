import * as React from "react"

export function VialIcon({ color = "#E0E0E0", className }: { color?: string, className?: string }) {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 100 150"
      className={className}
      preserveAspectRatio="xMidYMid meet"
    >
      <defs>
        <linearGradient id="vial-body-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
          <stop offset="0%" stopColor="#E5E7EB" />
          <stop offset="50%" stopColor="#F9FAFB" />
          <stop offset="100%" stopColor="#E5E7EB" />
        </linearGradient>
         <filter id="liquid-glow" x="-50%" y="-50%" width="200%" height="200%">
          <feGaussianBlur in="SourceGraphic" stdDeviation="1" result="blur" />
          <feMerge>
            <feMergeNode in="blur" />
            <feMergeNode in="SourceGraphic" />
          </feMerge>
        </filter>
      </defs>
      
      {/* Vial Body */}
      <path d="M25,25 L75,25 L75,140 C75,145 70,150 65,150 L35,150 C30,150 25,145 25,140 Z" fill="url(#vial-body-gradient)" stroke="#D1D5DB" strokeWidth="0.5"/>
      
      {/* Liquid Content */}
      <path d="M27,50 L73,50 L73,140 C73,144 69,148 65,148 L35,148 C31,148 27,144 27,140 Z" fill={color} opacity="0.7" style={{filter: 'url(#liquid-glow)'}} />
      
      {/* Flat Flip-top cap */}
      <rect x="22" y="5" width="56" height="20" rx="5" fill="#F9FAFB" stroke="#B0B0B0" strokeWidth="0.5"/>
      <line x1="22" y1="25" x2="78" y2="25" stroke="#B0B0B0" strokeWidth="0.5" />

      {/* Reflection Highlight */}
      <path d="M35,30 C37,60 37,110 35,140" fill="none" stroke="rgba(255,255,255,0.7)" strokeWidth="2" strokeLinecap="round"/>
    </svg>
  );
}
