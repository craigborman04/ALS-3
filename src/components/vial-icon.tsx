import * as React from "react"

export function VialIcon({ color = "#E0E0E0", className }: { color?: string, className?: string }) {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 100 120"
      className={className}
      preserveAspectRatio="xMidYMid meet"
    >
      {/* Cap */}
      <rect x="35" y="5" width="30" height="10" rx="2" ry="2" fill="#6B7280" />
      
      {/* Neck */}
      <rect x="41" y="15" width="18" height="10" fill="#D1D5DB" />
      
      {/* Body */}
      <path d="M35 25 V 110 a 5 5 0 0 0 5 5 H 60 a 5 5 0 0 0 5 -5 V 25 z" fill="#E5E7EB" />
      
      {/* Liquid */}
      <path d="M37 50 V 110 a 3 3 0 0 0 3 3 H 60 a 3 3 0 0 0 3 -3 V 50 z" fill={color} />
      
      {/* Reflection */}
      <path d="M 47 30 V 105 C 47 105 42 105 42 100 V 35 C 42 35 47 32 47 30 Z" fill="rgba(255, 255, 255, 0.5)" />
    </svg>
  );
}
