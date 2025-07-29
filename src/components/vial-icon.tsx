import * as React from "react"

export function VialIcon({ color = "#E0E0E0", className }: { color?: string, className?: string }) {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 100 155"
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
      <path d="M40,20 L60,20 C65,20 65,25 65,25 L65,145 C65,150 60,155 55,155 L45,155 C40,155 35,150 35,145 L35,25 C35,25 35,20 40,20 Z" fill="url(#vial-body-gradient)" stroke="#D1D5DB" strokeWidth="0.5"/>
      
      {/* Liquid Content */}
      <path d="M37,50 L63,50 L63,145 C63,149 59,153 55,153 L45,153 C41,153 37,149 37,145 Z" fill={color} opacity="0.7" style={{filter: 'url(#liquid-glow)'}} />
      
      {/* Flip-top cap */}
      <path d="M35,20 L65,20 L65,15 C65,10 60,5 55,5 L45,5 C40,5 35,10 35,15 Z" fill="#E5E7EB" stroke="#B0B0B0" strokeWidth="0.5"/>
      <path d="M45,5 C45,2 55,2 55,5 L55,10 L45,10 Z" fill="#F0F0F0" stroke="#B0B0B0" strokeWidth="0.5" />
      
      {/* Reflection Highlight */}
      <path d="M45,25 C47,55 47,110 45,140" fill="none" stroke="rgba(255,255,255,0.7)" strokeWidth="2" strokeLinecap="round"/>
    </svg>
  );
}
