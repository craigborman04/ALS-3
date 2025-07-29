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
      
      <g transform="rotate(5 50 75)">
        {/* Vial Body */}
        <path d="M32,25 L68,25 L68,140 C68,145 64,150 59,150 L41,150 C36,150 32,145 32,140 Z" fill="url(#vial-body-gradient)" stroke="#D1D5DB" strokeWidth="0.5"/>
        
        {/* Liquid Content */}
        <path d="M34,50 L66,50 L66,140 C66,144 62.5,148 58,148 L42,148 C37.5,148 34,144 34,140 Z" fill={color} opacity="0.7" style={{filter: 'url(#liquid-glow)'}} />
        
        {/* Flat Flip-top cap */}
        <rect x="29" y="5" width="42" height="20" rx="5" fill="#F9FAFB" stroke="#B0B0B0" strokeWidth="0.5"/>
        <line x1="29" y1="25" x2="71" y2="25" stroke="#B0B0B0" strokeWidth="0.5" />

        {/* Reflection Highlight */}
        <path d="M40,30 C42,60 42,110 40,140" fill="none" stroke="rgba(255,255,255,0.7)" strokeWidth="2" strokeLinecap="round"/>
      </g>
    </svg>
  );
}
