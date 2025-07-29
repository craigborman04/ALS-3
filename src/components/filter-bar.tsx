
"use client";

import { Search, X } from 'lucide-react';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import type { FilterOptions, Product } from '@/lib/types';

interface FilterBarProps {
  searchTerm: string;
  onSearchChange: (value: string) => void;
  selectedProduct: string;
  onProductChange: (value: string) => void;
  selectedSize: string;
  onSizeChange: (value: string) => void;
  selectedColor: string;
  onColorChange: (value: string) => void;
  selectedClosure: string;
  onClosureChange: (value: string) => void;
  onClearFilters: () => void;
  filterOptions: FilterOptions;
  products: Product[];
}

export function FilterBar({
  searchTerm,
  onSearchChange,
  selectedProduct,
  onProductChange,
  selectedSize,
  onSizeChange,
  selectedColor,
  onColorChange,
  onClearFilters,
  filterOptions,
  products
}: FilterBarProps) {
  return (
    <div className="mb-8 p-4 bg-card rounded-lg shadow-sm border">
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-center">
        <div className="relative md:col-span-1">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Search by product name..."
            value={searchTerm}
            onChange={(e) => onSearchChange(e.target.value)}
            className="pl-10"
          />
        </div>
        <div className="md:col-span-1">
          <Select value={selectedProduct} onValueChange={onProductChange}>
            <SelectTrigger>
              <SelectValue placeholder="All Products" />
            </SelectTrigger>
            <SelectContent>
              {products.map((product) => (
                <SelectItem key={product.id} value={product.name}>
                  {product.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <div className="md:col-span-1">
          <Select value={selectedColor} onValueChange={onColorChange}>
            <SelectTrigger>
              <SelectValue placeholder="All Colors" />
            </SelectTrigger>
            <SelectContent>
              {filterOptions.colors.map((color) => (
                <SelectItem key={color} value={color}>
                  {color}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <div className="md:col-span-1">
          <Select value={selectedSize} onValueChange={onSizeChange}>
            <SelectTrigger>
              <SelectValue placeholder="All Sizes" />
            </SelectTrigger>
            <SelectContent>
              {filterOptions.sizes.map((size) => (
                <SelectItem key={size} value={size}>
                  {size}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
      </div>
       <div className="flex justify-end mt-4">
          <Button variant="ghost" onClick={onClearFilters} className="w-full md:w-auto">
            <X className="mr-2 h-4 w-4" />
            Clear Filters
          </Button>
        </div>
    </div>
  );
}
