'use client';

import { Search, X } from 'lucide-react';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import type { Product } from '@/lib/types';

interface FilterBarProps {
  searchTerm: string;
  onSearchChange: (value: string) => void;
  selectedProduct: string;
  onProductChange: (value: string) => void;
  selectedColor: string;
  onColorChange: (value: string) => void;
  availableColors: string[];
  onClearFilters: () => void;
  products: Product[];
}

export function FilterBar({
  searchTerm,
  onSearchChange,
  selectedProduct,
  onProductChange,
  selectedColor,
  onColorChange,
  availableColors,
  onClearFilters,
  products
}: FilterBarProps) {
  const productNames = [...new Set(products.map((p) => p.name))];
  
  return (
    <div className="mb-8 p-4 bg-card rounded-lg shadow-sm border">
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
        <div className="relative">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Search by product name..."
            value={searchTerm}
            onChange={(e) => onSearchChange(e.target.value)}
            className="pl-10"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-muted-foreground">Product Name</label>
          <Select value={selectedProduct} onValueChange={onProductChange}>
            <SelectTrigger>
              <SelectValue placeholder="Select a Product" />
            </SelectTrigger>
            <SelectContent>
              {productNames.map((name) => (
                <SelectItem key={name} value={name}>
                  {name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        {availableColors.length > 0 && (
          <div>
            <label className="text-sm font-medium text-muted-foreground">Color</label>
            <Select value={selectedColor} onValueChange={onColorChange}>
              <SelectTrigger>
                <SelectValue placeholder="Select a Color" />
              </SelectTrigger>
              <SelectContent>
                {availableColors.map((color) => (
                  <SelectItem key={color} value={color}>
                    {color}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        )}

        <div>
          <Button variant="ghost" onClick={onClearFilters} className="w-full md:w-auto">
            <X className="mr-2 h-4 w-4" />
            Clear Filters
          </Button>
        </div>
      </div>
    </div>
  );
}