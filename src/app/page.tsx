
"use client";

import { useState, useEffect, useMemo } from 'react';
import { FilterBar } from '@/components/filter-bar';
import { ProductGrid } from '@/components/product-grid';
import type { Product } from '@/lib/types';
import { mockProducts, filterOptions } from '@/lib/mock-data';
import { useDebounce } from '@/hooks/use-debounce';

export default function Home() {
  const [products, setProducts] = useState<Product[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedSize, setSelectedSize] = useState('');
  const [selectedColor, setSelectedColor] = useState('');
  const [selectedClosure, setSelectedClosure] = useState('');

  const debouncedSearchTerm = useDebounce(searchTerm, 300);

  useEffect(() => {
    // Simulate API call
    const timer = setTimeout(() => {
      setProducts(mockProducts);
      setIsLoading(false);
    }, 1000);

    return () => clearTimeout(timer);
  }, []);

  const filteredProducts = useMemo(() => {
    return products.filter((product) => {
      const searchMatch = product.name.toLowerCase().includes(debouncedSearchTerm.toLowerCase());
      const sizeMatch = selectedSize ? product.size === selectedSize : true;
      const colorMatch = selectedColor ? product.color === selectedColor : true;
      const closureMatch = selectedClosure ? product.closure === selectedClosure : true;
      return searchMatch && sizeMatch && colorMatch && closureMatch;
    });
  }, [products, debouncedSearchTerm, selectedSize, selectedColor, selectedClosure]);

  const handleClearFilters = () => {
    setSearchTerm('');
    setSelectedSize('');
    setSelectedColor('');
    setSelectedClosure('');
  };

  return (
    <div className="min-h-screen">
      <header className="bg-card border-b">
        <div className="container mx-auto px-4 py-6">
          <h1 className="text-3xl font-bold text-primary font-headline">ALS Product Catalog</h1>
          <p className="text-muted-foreground mt-1">Browse our extensive range of laboratory products.</p>
        </div>
      </header>
      <main className="container mx-auto px-4 py-8">
        <FilterBar
          searchTerm={searchTerm}
          onSearchChange={setSearchTerm}
          selectedSize={selectedSize}
          onSizeChange={setSelectedSize}
          selectedColor={selectedColor}
          onColorChange={setSelectedColor}
          selectedClosure={selectedClosure}
          onClosureChange={setSelectedClosure}
          onClearFilters={handleClearFilters}
          filterOptions={filterOptions}
        />
        <ProductGrid products={filteredProducts} isLoading={isLoading} />
      </main>
      <footer className="bg-card border-t mt-12 py-6">
        <div className="container mx-auto px-4 text-center text-sm text-muted-foreground">
          <p>&copy; {new Date().getFullYear()} ALS Laboratory. All rights reserved.</p>
        </div>
      </footer>
    </div>
  );
}
