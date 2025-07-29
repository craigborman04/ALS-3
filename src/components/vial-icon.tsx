import * as React from "react"

export function VialIcon({ color = "#E0E0E0", className }: { color?: string, className?: string }) {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 100 125"
      className={className}
      preserveAspectRatio="xMidYMid meet"
    >
      <defs>
        <linearGradient id="vial-body-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
          <stop offset="0%" stopColor="#E5E7EB" />
          <stop offset="50%" stopColor="#F9FAFB" />
          <stop offset="100%" stopColor="#E5E7EB" />
        </linearGradient>
        <linearGradient id="vial-cap-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
          <stop offset="0%" stopColor="#F3F4F6" />
          <stop offset="50%" stopColor="#FFFFFF" />
          <stop offset="100%" stopColor="#F3F4F6" />
        </linearGradient>
        <filter id="liquid-glow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceGraphic" stdDeviation="2" result="blur" />
            <feMerge>
                <feMergeNode in="blur" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>
      </defs>
      
      {/* Vial Body */}
      <path d="M25,25 C25,23 25,20 28,20 L72,20 C75,20 75,23 75,25 L75,115 C75,120 70,125 65,125 L35,125 C30,125 25,120 25,115 Z" fill="url(#vial-body-gradient)" stroke="#D1D5DB" strokeWidth="0.5"/>
      
      {/* Liquid Content */}
      <path d="M27,50 L73,50 L73,115 C73,119 69,123 65,123 L35,123 C31,123 27,119 27,115 Z" fill={color} opacity="0.7" style={{filter: 'url(#liquid-glow)'}} />
      
      {/* Seal Ring */}
      <rect x="25" y="28" width="50" height="4" rx="1" ry="1" fill="#D1D5DB" opacity="0.6"/>
      
      {/* Cap */}
      <path d="M22,18 C22,13 26,10 30,10 L70,10 C74,10 78,13 78,18 L78,20 L22,20 Z" fill="url(#vial-cap-gradient)" stroke="#D1D5DB" strokeWidth="0.5"/>
      <path d="M22,18 C22,23 26,25 30,25 L70,25 C74,25 78,23 78,18 L78,20 L22,20 Z" fill="#FFFFFF" opacity="0.5" />
      
      {/* Reflection Highlight */}
      <path d="M32,28 C34,45 34,90 32,110" fill="none" stroke="rgba(255,255,255,0.8)" strokeWidth="3" strokeLinecap="round"/>
    </svg>
  );
}
