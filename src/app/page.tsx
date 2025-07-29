
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
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { cn } from '@/lib/utils';

interface Message {
  role: 'user' | 'model';
  text: string;
}

export default function Home() {
  const [products, setProducts] = useState<Product[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedSize, setSelectedSize] = useState('');

  const [prompt, setPrompt] = useState('');
  const [messages, setMessages] = useState<Message[]>([]);
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

    const newMessages: Message[] = [...messages, { role: 'user', text: prompt }];
    setMessages(newMessages);
    setPrompt('');

    startTransition(async () => {
      const response = await simplePrompt(prompt);
      setMessages([...newMessages, { role: 'model', text: response }]);
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
            <CardTitle>Chat with Gemini Pro</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4 mb-4 h-64 overflow-y-auto p-4 border rounded-md bg-secondary/50">
              {messages.length === 0 && (
                <div className="flex h-full items-center justify-center">
                  <p className="text-muted-foreground">Ask me anything to get started...</p>
                </div>
              )}
              {messages.map((message, index) => (
                <div key={index} className={cn('flex items-start gap-3', message.role === 'user' ? 'justify-end' : 'justify-start')}>
                  {message.role === 'model' && (
                    <Avatar className="h-8 w-8">
                      <AvatarImage src="https://www.gstatic.com/lamda/images/gemini_sparkle_v002_16x16_1a733b66df22a716ed326b2a3b16a22b.gif" alt="Gemini" />
                      <AvatarFallback>AI</AvatarFallback>
                    </Avatar>
                  )}
                  <div className={cn('p-3 rounded-lg max-w-sm', message.role === 'user' ? 'bg-primary text-primary-foreground' : 'bg-background')}>
                    <p className="text-sm">{message.text}</p>
                  </div>
                   {message.role === 'user' && (
                    <Avatar className="h-8 w-8">
                      <AvatarFallback>U</AvatarFallback>
                    </Avatar>
                  )}
                </div>
              ))}
               {isPending && messages[messages.length -1].role === 'user' && (
                 <div className="flex items-start gap-3 justify-start">
                    <Avatar className="h-8 w-8">
                       <AvatarImage src="https://www.gstatic.com/lamda/images/gemini_sparkle_v002_16x16_1a733b66df22a716ed326b2a3b16a22b.gif" alt="Gemini" />
                      <AvatarFallback>AI</AvatarFallback>
                    </Avatar>
                    <div className="p-3 rounded-lg bg-background">
                      <p className="text-sm text-muted-foreground">Thinking...</p>
                    </div>
                  </div>
               )}
            </div>
            <form onSubmit={handlePromptSubmit} className="flex gap-2">
              <Input
                value={prompt}
                onChange={(e) => setPrompt(e.target.value)}
                placeholder="Ask me anything..."
                disabled={isPending}
              />
              <Button type="submit" disabled={isPending || !prompt}>
                {isPending ? 'Sending...' : 'Send'}
              </Button>
            </form>
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
