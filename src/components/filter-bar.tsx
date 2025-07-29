"use client";

import { Search, X } from 'lucide-react';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import type { FilterOptions } from '@/lib/types';

interface FilterBarProps {
  searchTerm: string;
  onSearchChange: (value: string) => void;
  selectedSize: string;
  onSizeChange: (value: string) => void;
  selectedColor: string;
  onColorChange: (value: string) => void;
  selectedClosure: string;
  onClosureChange: (value: string) => void;
  onClearFilters: () => void;
  filterOptions: FilterOptions;
}

export function FilterBar({
  searchTerm,
  onSearchChange,
  selectedSize,
  onSizeChange,
  selectedColor,
  onColorChange,
  selectedClosure,
  onClosureChange,
  onClearFilters,
  filterOptions,
}: FilterBarProps) {
  return (
    <div className="mb-8 p-4 bg-card rounded-lg shadow-sm border">
      <div className="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
        <div className="relative md:col-span-2">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Search by product name..."
            value={searchTerm}
            onChange={(e) => onSearchChange(e.target.value)}
            className="pl-10"
          />
        </div>
        <div className="md:col-span-1">
          <Select value={selectedSize} onValueChange={onSizeChange}>
            <SelectTrigger>
              <SelectValue placeholder="Filter by size" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">All Sizes</SelectItem>
              {filterOptions.sizes.map((size) => (
                <SelectItem key={size} value={size}>
                  {size}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <div className="md:col-span-1">
          <Select value={selectedColor} onValueChange={onColorChange}>
            <SelectTrigger>
              <SelectValue placeholder="Filter by color" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem value="">All Colors</SelectItem>
              {filterOptions.colors.map((color) => (
                <SelectItem key={color} value={color}>
                  {color}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <div className="md:col-span-1">
          <Select value={selectedClosure} onValueChange={onClosureChange}>
            <SelectTrigger>
              <SelectValue placeholder="Filter by closure" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem value="">All Closures</SelectItem>
              {filterOptions.closures.map((closure) => (
                <SelectItem key={closure} value={closure}>
                  {closure}
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
