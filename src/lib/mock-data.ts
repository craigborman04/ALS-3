import type { Product, FilterOptions } from './types';

// Data parsed from wp_als_products.csv and wp_als_product_options_simple.csv
export const mockProducts: Product[] = [
  {
    id: '1',
    name: '1oz Vial',
    size: '1oz',
    color: 'Natural',
    closure: 'Unlined Cap',
    imageUrl: 'https://placehold.co/600x400',
    variations: 4,
    description: 'A standard 1oz vial for various laboratory applications.',
    'data-ai-hint': 'lab vial'
  },
  {
    id: '2',
    name: '2oz Vial',
    size: '2oz',
    color: 'Natural',
    closure: 'Unlined Cap',
    imageUrl: 'https://placehold.co/600x400',
    variations: 4,
    description: 'A standard 2oz vial, suitable for larger samples.',
    'data-ai-hint': 'lab vial'
  },
  {
    id: '3',
    name: '3oz Vial',
    size: '3oz',
    color: 'White',
    closure: 'PTFE Lined Cap',
    imageUrl: 'https://placehold.co/600x400',
    variations: 2,
    description: 'A 3oz vial with a PTFE lined cap for enhanced chemical resistance.',
    'data-ai-hint': 'lab vial'
  },
  {
    id: '4',
    name: '4oz Jar',
    size: '4oz',
    color: 'Clear',
    closure: 'F217 Lined Cap',
    imageUrl: 'https://placehold.co/600x400',
    variations: 3,
    description: 'A 4oz clear jar with a reliable F217 lined cap.',
    'data-ai-hint': 'lab jar'
  },
  {
    id: '5',
    name: '8oz Jar',
    size: '8oz',
    color: 'Clear',
    closure: 'Unlined Cap',
    imageUrl: 'https://placehold.co/600x400',
    variations: 3,
    description: 'An 8oz clear jar for general purpose storage.',
    'data-ai-hint': 'lab jar'
  }
];

// Data based on the unique values from products and their options.
const productSizes = [...new Set(mockProducts.map((p) => p.size))].sort();
const productColors = ["Natural", "White", "Clear"].sort();
const productClosures = ["Unlined Cap", "PTFE Lined Cap", "F217 Lined Cap", "Orifice Reducer"].sort();


export const filterOptions: FilterOptions = {
    sizes: productSizes,
    colors: productColors,
    closures: productClosures
}
