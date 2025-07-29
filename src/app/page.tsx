
"use client";

import { useState, useEffect, useMemo, useTransition } from 'react';
import { FilterBar } from '@/components/filter-bar';
import { ProductGrid } from '@/components/product-grid';
import type { Product } from '@/lib/types';
import { mockProducts } from '@/lib/mock-data';
import { useDebounce } from '@/hooks/use-debounce';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { simplePrompt } from '@/ai/flows/simple-flow';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

export default function Home() {
  const [products, setProducts] = useState<Product[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedSize, setSelectedSize] = useState('');

  const [prompt, setPrompt] = useState('');
  const [aiResponse, setAiResponse] = useState('');
  const [isPending, startTransition] = useTransition();

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
      return searchMatch && sizeMatch;
    });
  }, [products, debouncedSearchTerm, selectedSize]);

  const handleClearFilters = () => {
    setSearchTerm('');
    setSelectedSize('');
  };

  const handlePromptSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!prompt) return;

    startTransition(async () => {
      const response = await simplePrompt(prompt);
      setAiResponse(response);
    });
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
        <Card className="mb-8">
          <CardHeader>
            <CardTitle>Ask Gemini Pro</CardTitle>
          </CardHeader>
          <CardContent>
            <form onSubmit={handlePromptSubmit} className="flex gap-2">
              <Input
                value={prompt}
                onChange={(e) => setPrompt(e.target.value)}
                placeholder="Ask me anything..."
              />
              <Button type="submit" disabled={isPending}>
                {isPending ? 'Thinking...' : 'Submit'}
              </Button>
            </form>
            {aiResponse && (
              <div className="mt-4 p-4 bg-secondary rounded-lg">
                <p className="text-secondary-foreground">{aiResponse}</p>
              </div>
            )}
          </CardContent>
        </Card>

        <FilterBar
          searchTerm={searchTerm}
          onSearchChange={setSearchTerm}
          selectedSize={selectedSize}
          onSizeChange={(size) => setSelectedSize(size === selectedSize ? '' : size)}
          onClearFilters={handleClearFilters}
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
