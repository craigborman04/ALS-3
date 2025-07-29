
import * as React from "react"

export function VialIcon({ color = "#E0E0E0", className }: { color?: string, className?: string }) {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="1"
      strokeLinecap="round"
      strokeLinejoin="round"
      className={className}
    >
      <path d="M5.5 22h13" />
      <path d="M14 2h-4" />
      <path d="M14 2v5.5c0 1.4-1.1 2.5-2.5 2.5h-1C9.1 10 8 8.9 8 7.5V2" />
      <path d="M8 10h8c1.1 0 2 0.9 2 2v8c0 1.1-0.9 2-2 2H6c-1.1 0-2-0.9-2-2v-8c0-1.1 0.9-2 2-2h2" fill={color} strokeWidth="0.5" />
    </svg>
  );
}
