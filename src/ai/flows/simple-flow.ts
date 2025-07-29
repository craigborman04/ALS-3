'use server';
/**
 * @fileOverview A simple AI flow for demonstrating Genkit.
 *
 * - simplePrompt - A function that takes a string prompt and returns a response from the AI.
 */

import {ai} from '@/ai/genkit';
import {z} from 'genkit';

// Define the input schema for the flow.
const SimpleInputSchema = z.string();

// Define and export the server action.
export async function simplePrompt(prompt: string): Promise<string> {
  return simpleFlow(prompt);
}

// Define the Genkit flow.
const simpleFlow = ai.defineFlow(
  {
    name: 'simpleFlow',
    inputSchema: SimpleInputSchema,
    outputSchema: z.string(),
  },
  async (prompt) => {
    const llmResponse = await ai.generate({
      prompt: prompt,
      model: 'googleai/gemini-pro',
    });

    return llmResponse.text;
  }
);
