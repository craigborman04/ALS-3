import * as React from "react"

export function VialIcon({ color = "#E0E0E0", className }: { color?: string, className?: string }) {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 100 100"
      className={className}
      preserveAspectRatio="xMidYMid meet"
    >
      {/* Cap */}
      <rect x="30" y="5" width="40" height="10" rx="2" ry="2" fill="#6B7280" />
      
      {/* Neck */}
      <rect x="38" y="15" width="24" height="10" fill="#D1D5DB" />
      
      {/* Body */}
      <path d="M30 25 V 90 a 5 5 0 0 0 5 5 H 65 a 5 5 0 0 0 5 -5 V 25 z" fill="#E5E7EB" />
      
      {/* Liquid */}
      <path d="M32 40 V 90 a 3 3 0 0 0 3 3 H 65 a 3 3 0 0 0 3 -3 V 40 z" fill={color} />
      
      {/* Reflection */}
      <path d="M 45 30 V 85 C 45 85 40 85 40 80 V 35 C 40 35 45 32 45 30 Z" fill="rgba(255, 255, 255, 0.5)" />
    </svg>
  );
}
