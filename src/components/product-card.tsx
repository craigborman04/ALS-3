
import type { Product } from '@/lib/types';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Layers, Droplet, Zap } from 'lucide-react';
import { VialIcon } from './vial-icon';

interface ProductCardProps {
  product: Product;
}

const colorHexMap: { [key: string]: string } = {
  'Natural': '#E0E0E0',
  'White': '#FFFFFF',
  'Clear': '#F0F8FF', // AliceBlue for a slightly off-white clear
};

export function ProductCard({ product }: ProductCardProps) {
  const vialColor = product.color ? colorHexMap[product.color] || '#E0E0E0' : '#E0E0E0';
  
  return (
    <Card className="flex flex-col overflow-hidden rounded-lg shadow-lg transition-all hover:shadow-xl animate-in fade-in duration-500">
      <CardHeader className="p-0 relative bg-gray-50 flex items-center justify-center h-48">
        <VialIcon color={vialColor} className="w-24 h-24 text-gray-400" />
        <Badge variant="secondary" className="absolute top-2 right-2">
          <Layers className="mr-1 h-3 w-3" />
          {product.variations} Variations
        </Badge>
      </CardHeader>
      <CardContent className="p-4 flex-grow">
        <CardTitle className="text-lg font-semibold mb-1">{product.name}</CardTitle>
        <div className="flex items-center gap-4 text-sm text-muted-foreground mb-2">
          <span>Size: {product.size}</span>
           {product.color && <span><Droplet className="inline-block mr-1 h-4 w-4" />{product.color}</span>}
          {product.closure && product.closure !== 'None' && <span><Zap className="inline-block mr-1 h-4 w-4" />{product.closure}</span>}
        </div>
        <p className="mt-2 text-sm text-foreground/80 line-clamp-2">{product.description}</p>
      </CardContent>
      <CardFooter className="p-4 pt-0 mt-auto">
        <Button className="w-full" variant="outline">
          Request Quote
        </Button>
      </CardFooter>
    </Card>
  );
}
