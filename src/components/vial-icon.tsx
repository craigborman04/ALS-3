import * as React from "react"

export function VialIcon({ color = "#E0E0E0", className }: { color?: string, className?: string }) {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 105 150"
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
      
      <g transform="rotate(-7 52.5 75)">
        {/* Vial Body */}
        <path d="M40,20 L65,20 C70,20 70,25 70,25 L70,138 C70,143 65,148 60,148 L45,148 C40,148 35,143 35,138 L35,25 C35,25 35,20 40,20 Z" fill="url(#vial-body-gradient)" stroke="#D1D5DB" strokeWidth="0.5"/>
        
        {/* Liquid Content */}
        <path d="M37,50 L68,50 L68,138 C68,142 64,146 60,146 L45,146 C41,146 37,142 37,138 Z" fill={color} opacity="0.7" style={{filter: 'url(#liquid-glow)'}} />
        
        {/* Flip-top cap */}
        <path d="M35,20 L70,20 L70,15 C70,10 65,5 60,5 L45,5 C40,5 35,10 35,15 Z" fill="#E5E7EB" stroke="#B0B0B0" strokeWidth="0.5"/>
        <path d="M45,5 C45,2 60,2 60,5 L60,10 L45,10 Z" fill="#F0F0F0" stroke="#B0B0B0" strokeWidth="0.5" />
        
        {/* Reflection Highlight */}
        <path d="M48,25 C50,55 50,105 48,135" fill="none" stroke="rgba(255,255,255,0.7)" strokeWidth="2" strokeLinecap="round"/>
      </g>
    </svg>
  );
}
