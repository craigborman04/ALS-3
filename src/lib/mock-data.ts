import type { Product } from './types';

export const mockProducts: Product[] = [
  {
    id: 'prod_001',
    name: 'Sterile Centrifuge Tube',
    size: '50ml',
    imageUrl: 'https://placehold.co/600x400',
    variations: 3,
    description: 'Conical bottom, sterile, with a screw cap for secure sealing.',
    'data-ai-hint': 'lab equipment'
  },
  {
    id: 'prod_002',
    name: 'Micro-Pipette Tips',
    size: '200μl',
    imageUrl: 'https://placehold.co/600x400',
    variations: 2,
    description: 'Universal fit, low retention, and certified RNase/DNase free.',
    'data-ai-hint': 'lab equipment'
  },
  {
    id: 'prod_003',
    name: 'Glass Beaker',
    size: '250ml',
    imageUrl: 'https://placehold.co/600x400',
    variations: 1,
    description: 'High-quality borosilicate glass with printed graduations.',
    'data-ai-hint': 'glass beaker'
  },
  {
    id: 'prod_004',
    name: 'Petri Dishes, Polystyrene',
    size: '90mm',
    imageUrl: 'https://placehold.co/600x400',
    variations: 2,
    description: 'Sterilized, disposable dishes perfect for cell culture.',
    'data-ai-hint': 'petri dish'
  },
  {
    id: 'prod_005',
    name: 'Cryogenic Vials',
    size: '2ml',
    imageUrl: 'https://placehold.co/600x400',
    variations: 4,
    description: 'Self-standing vials with internal threads for secure storage.',
    'data-ai-hint': 'lab vials'
  },
  {
    id: 'prod_006',
    name: 'Erlenmeyer Flask',
    size: '500ml',
    imageUrl: 'https://placehold.co/600x400',
    variations: 1,
    description: 'Narrow neck flask made from durable borosilicate glass.',
    'data-ai-hint': 'lab flask'
  },
  {
    id: 'prod_007',
    name: 'Latex Examination Gloves',
    size: 'Medium',
    imageUrl: 'https://placehold.co/600x400',
    variations: 3,
    description: 'Powder-free, non-sterile gloves for general lab use.',
    'data-ai-hint': 'latex gloves'
  },
  {
    id: 'prod_008',
    name: 'Volumetric Flask with Stopper',
    size: '100ml',
    imageUrl: 'https://placehold.co/600x400',
    variations: 2,
    description: 'Class A, calibrated to contain, with a polyethylene stopper.',
    'data-ai-hint': 'lab flask'
  },
];

export const productSizes = [
  ...new Set(mockProducts.map((p) => p.size)),
].sort();
